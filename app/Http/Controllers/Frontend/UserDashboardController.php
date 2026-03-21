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

        return view('view.userdashboard', [
            'user' => $user,
            'addresses' => $user->addresses()->latest()->get(),
            'orders' => $user->orders()->latest()->get(),
            'wishlist' => $user->wishlist()->with('product')->get(),
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

        return back()->with('success', 'Default address updated.');
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

    public function cancelOrder($id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if (in_array($order->status, ['shipped', 'delivered', 'cancelled'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

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
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('return_images', 'public');
            }
        }

        $order->update([
            'status' => 'return_requested',
            'return_requested_at' => now(),
            'return_reason' => $request->reason,
            'return_images' => $imagePaths
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

                return [
                    'id' => $item->id,
                    'name' => $item->product_name,
                    'product_id' => $item->product_id,
                    'combo_pack_id' => $item->combo_pack_id,
                    'existing_rating' => $review ? $review->rating : null,
                    'existing_review' => $review ? $review->review : null,
                    'is_editable' => $isEditable,
                ];
            })
        ]);
    }

    public function downloadInvoice(Order $order)
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

        $shippingAddress = $order->shipping_address;

        $data = [
            'invoice_number'   => $order->order_number,
            'order_date'       => $order->created_at->format('d/M/Y'),
            'payment_status'   => $order->payment_status,
            'store_logo'       => asset('assets/images/logo/logo.png'),
            'store_name'       => 'Plantsware',
            'store_address'    => 'Plantsware Admin, Tamil Nadu',
            'store_email'      => 'support@plantsware.in',
            'store_phone'      => '+91 98765 43210',
            'customer_name'    => ($shippingAddress['name'] ?? ($user->name ?? 'Guest')),
            'customer_email'   => $user->email ?? 'N/A',
            'customer_phone'   => ($shippingAddress['phone'] ?? 'N/A'),
            'customer_address' => $shippingAddress,
            'order_items'      => $order->items,
            'subtotal'         => $order->subtotal,
            'discount_amount'  => $order->discount,
            'tax_amount'       => $order->shipping, 
            'grand_total'      => $order->total,
        ];

        $pdf = PDF::loadView('invoices.order_invoice', $data);
        return $pdf->download('Invoice_' . $order->order_number . '.pdf');
    }
}
