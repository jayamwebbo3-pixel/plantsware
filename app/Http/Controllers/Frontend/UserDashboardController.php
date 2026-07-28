<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = now();
        $isNewUser = $user && $user->created_at >= now()->subMonths(5)->startOfMonth();

        $coupons = \App\Models\Coupon::where('status', 1)
            ->where(function($q) use ($now) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('valid_to')->orWhere('valid_to', '>=', $now);
            })
            ->where(function($q) use ($user, $isNewUser) {
                $q->where(function($sq) use ($isNewUser) {
                    if ($isNewUser) {
                        $sq->where('is_public', 1);
                    } else {
                        $sq->whereRaw('1 = 0');
                    }
                })
                ->orWhereHas('users', function($uq) use ($user) {
                    $uq->where('users.id', $user->id);
                });
            })
            ->get();

        $usedCouponIds = \App\Models\CouponUsage::where('user_id', $user->id)->pluck('coupon_id')->toArray();
        foreach ($coupons as $coupon) {
            $coupon->is_used = in_array($coupon->id, $usedCouponIds);
        }

        return view('view.userdashboard', [
            'user' => $user,
            'addresses' => $user->addresses()->latest()->get(),
            'orders' => $user->orders()->with('items')->latest()->get(),
            'wishlist' => $user->wishlist()->with('product')->get(),
            'coupons' => $coupons,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return response()->json([
            'success' => true,
            'name' => $user->name,
            'message' => 'Profile updated successfully.'
        ]);
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'door_number' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'post_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:15',
            'alternative_number' => 'nullable|string|max:15',
        ]);

        $user = Auth::user();
        
        // If this is the first address, make it default
        $isDefault = $user->addresses()->count() === 0;

        $user->addresses()->create(array_merge($validated, ['is_default' => $isDefault]));

        return back()->with('success', 'Address added successfully.');
    }

    public function updateAddress(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'door_number' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'post_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:15',
            'alternative_number' => 'nullable|string|max:15',
        ]);

        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        $address->update($validated);

        return back()->with('success', 'Address updated successfully.');
    }

    public function setDefaultAddress($id)
    {
        $user = Auth::user();
        
        // Reset all addresses to not default
        $user->addresses()->update(['is_default' => false]);
        
        // Set the selected one as default
        $address = $user->addresses()->findOrFail($id);
        $address->update(['is_default' => true]);

        return back();
    }

    public function deleteAddress($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);
        
        if ($address->is_default) {
            $address->delete();
            // Assign a new default if any addresses left
            $newDefault = $user->addresses()->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        } else {
            $address->delete();
        }

        return back()->with('success', 'Address deleted successfully.');
    }

    public function cancelOrder(\Illuminate\Http\Request $request, $id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if (in_array($order->status, ['shipped', 'delivered', 'cancelled'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $tempCartService = app(\App\Services\TempCartService::class);
        foreach ($order->items as $item) {
            $tempCartService->restoreStock($item);
        }

        $order->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->input('cancel_reason')
        ]);

        return back()->with('success', 'Order has been successfully cancelled.');
    }
    public function returnOrder(\Illuminate\Http\Request $request, $id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Only delivered orders can be returned.');
        }

        if (!$order->delivered_at || $order->delivered_at->diffInHours(now()) > 24) {
            return back()->with('error', 'Return window (24 hours) has expired.');
        }

        $request->validate([
            'reason' => 'required|string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:1024',
            'returned_items' => 'required|array|min:1'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            if (!file_exists(storage_path('app/public/return_images'))) {
                mkdir(storage_path('app/public/return_images'), 0755, true);
            }
            foreach ($request->file('images') as $image) {
                $filename = 'return_images/' . uniqid() . '_' . time() . '.webp';
                $fullPath = storage_path('app/public/' . $filename);
                $img = $manager->read($image->getRealPath());
                $img->toWebp(80)->save($fullPath);
                $imagePaths[] = $filename;
            }
        }

        $returnedItemsDetails = [];
        if (is_array($request->returned_items)) {
            $orderItems = $order->items()->whereIn('id', $request->returned_items)->get();
            foreach ($orderItems as $item) {
                $optionsStr = '';
                if (!empty($item->options)) {
                    $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;
                    if (is_array($options) && isset($options['size'])) {
                        $optionsStr = ' (Size: ' . $options['size'] . ')';
                    } elseif (is_string($options)) {
                        $optionsStr = ' (Size: ' . $options . ')';
                    }
                }
                $returnQty = isset($request->return_quantities[$item->id]) ? (int)$request->return_quantities[$item->id] : $item->quantity;
                if ($returnQty > $item->quantity) {
                     $returnQty = $item->quantity;
                }
                if ($returnQty < 1) {
                     $returnQty = 1;
                }

                $returnedItemsDetails[] = [
                    'id' => $item->id,
                    'name' => $item->product_name . $optionsStr,
                    'price' => $item->price,
                    'quantity' => $returnQty,
                    'total' => $item->price * $returnQty
                ];
            }
        }

        $order->update([
            'status' => 'return_requested',
            'return_requested_at' => now(),
            'return_reason' => $request->reason,
            'return_images' => $imagePaths,
            'returned_items' => $returnedItemsDetails
        ]);

        return back()->with('success', 'Return request submitted successfully.');
    }

    public function getOrderItems($id)
    {
        $user = Auth::user();
        $order = $user->orders()->with('items')->findOrFail($id);

        return response()->json([
            'items' => $order->items->map(function ($item) use ($user, $order) {
                $review = \App\Models\ProductReview::where('user_id', $user->id)
                    ->where('order_id', $order->id)
                    ->where(function ($query) use ($item) {
                        if ($item->product_id) {
                            $query->where('product_id', $item->product_id);
                        } else {
                            $query->where('combo_pack_id', $item->combo_pack_id);
                        }
                    })->first();

                $isEditable = true;
                if ($review) {
                    $isEditable = $review->updated_at->diffInDays(now()) <= 30;
                }

                $optionsStr = '';
                if (!empty($item->options)) {
                    $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;
                    if (is_array($options) && isset($options['size'])) {
                        $optionsStr = ' (Size: ' . $options['size'] . ')';
                    } elseif (is_string($options)) {
                        $optionsStr = ' (Size: ' . $options . ')';
                    }
                }

                return [
                    'id' => $item->id,
                    'name' => $item->product_name . $optionsStr,
                    'product_id' => $item->product_id,
                    'combo_pack_id' => $item->combo_pack_id,
                    'existing_rating' => $review ? $review->rating : null,
                    'existing_review' => $review ? $review->review : null,
                    'is_editable' => $isEditable,
                    'quantity' => $item->quantity,
                ];
            })
        ]);
    }

    public function showOrder($id)
    {
        $order = \App\Models\Order::with(['items.product', 'items.comboPack'])->findOrFail($id);
        
        if ($order->user_id != \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        return view('view.order.details', compact('order'));
    }

    public function downloadInvoice(\App\Models\Order $order)
    {
        $user = Auth::user();

        // Security check: Ensure order belongs to current user
        if ($order->user_id != $user->id) {
            abort(403, 'Unauthorized access to this invoice. This order belongs to a different account. You are currently logged in as ' . ($user->email ?? 'Guest') . '.');
        }

        if (!in_array($order->payment_status, ['paid', 'refunded'])) {
            // Some stores allow invoices for pending payment too, but following OrderController.php logic
            // return back()->with('error', 'Invoice is available only after payment.');
        }

        $order->load(['items.product', 'items.comboPack', 'couponUsage.coupon']);

        $gstSettings = \App\Models\HeaderFooter::first();

        $billingAddress = $order->billing_address;
        if (empty($billingAddress) || !isset($billingAddress['name'])) {
            $billingAddress = $order->shipping_address;
        }

        $shippingAddress = $order->shipping_address;
        if (empty($shippingAddress) || empty($shippingAddress['address'])) {
            $shippingAddress = $billingAddress;
        }

        $data = [
            'invoice_number'   => $order->order_number,
            'order_date'       => $order->created_at->format('d/M/Y'),
            'payment_status'   => $order->payment_status,
            'store_logo'       => asset('assets/images/logo/logo.png'),
            'store_name'       => $gstSettings->header_title ?? 'Plantsware',
            'store_address'    => $gstSettings->address ?? 'Plantsware Admin, Tamil Nadu',
            'store_email'      => $gstSettings->email ?? 'support@plantsware.in',
            'store_phone'      => $gstSettings->mobile_no ?? '+91 98765 43210',
            'customer_name'    => ($billingAddress['name'] ?? ($user->name ?? 'Guest')),
            'customer_email'   => $user->email ?? 'N/A',
            'customer_phone'   => ($billingAddress['phone'] ?? 'N/A'),
            'customer_address' => $billingAddress,
            'shipping_address' => $shippingAddress,
            'order_items'      => $order->items,
            'subtotal'         => $order->subtotal,
            'discount_amount'  => $order->discount,
            'coupon_code'      => $order->couponUsage && $order->couponUsage->coupon ? $order->couponUsage->coupon->coupon_code : null,
            'coupon_discount'  => $order->couponUsage ? $order->couponUsage->discount_amount : 0,
            'shipping_amount'  => $order->shipping,
            'tax_amount'       => $order->tax,
            'cgst'             => $order->cgst ?? 0,
            'sgst'             => $order->sgst ?? 0,
            'igst'             => $order->igst ?? 0,
            'grand_total'      => $order->total,
        ];

        $pdf = PDF::loadView('invoices.order_invoice', $data);
        return $pdf->download('Invoice_' . $order->order_number . '.pdf');
    }
}
