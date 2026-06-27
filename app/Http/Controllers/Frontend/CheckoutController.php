<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // Show address form (from cart "Proceed to Checkout")
    public function address()
    {
        $cartItems = Cart::current()->with(['product', 'comboPack'])->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            app(\App\Services\TempCartService::class)->reserveStockForCheckout();
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        // Determine state for initial shipping/tax calculation
        $state = null;
        $savedAddress = [];
        $userAddresses = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $userAddresses = $user->addresses;

            $defaultAddress = $user->addresses()->where('is_default', true)->first();
            if ($defaultAddress) {
                $savedAddress = [
                    'address_id' => $defaultAddress->id,
                    'name' => $defaultAddress->first_name . ' ' . $defaultAddress->last_name,
                    'door_number' => $defaultAddress->door_number,
                    'address' => $defaultAddress->street,
                    'city' => $defaultAddress->city,
                    'state' => $defaultAddress->state,
                    'pincode' => $defaultAddress->post_code,
                    'phone' => $defaultAddress->phone_number,
                ];
                $state = $defaultAddress->state;
            } else {
                if (!empty($user->address) && is_array($user->address)) {
                    $savedAddress = $user->address;
                    $state = $savedAddress['state'] ?? null;
                }
            }
        }

        if (session()->has('shipping_address')) {
            $sessAddr = session('shipping_address');
            $savedAddress = array_merge($savedAddress, $sessAddr);
            $state = $sessAddr['state'] ?? $state;
        }

        $billingSame = true;
        if (session()->has('billing_same')) {
            $billingSame = session('billing_same');
        }
        if (session()->has('billing_address')) {
            $sessBill = session('billing_address');
            foreach ($sessBill as $k => $v) {
                $savedAddress['billing_' . $k] = $v;
            }
        }

        $totals = $this->calculateTotalsForCheckout($cartItems, $state);

        $subtotal = $totals['subtotal'];
        $discount = $totals['discount'];
        $couponDiscount = $totals['couponDiscount'] ?? 0;
        $coupon = $totals['coupon'] ?? null;
        $totalWeight = $totals['totalWeight'];
        $shipping = $totals['shipping'];
        $tax = $totals['tax'];
        $cgst = $totals['cgst'];
        $sgst = $totals['sgst'];
        $igst = $totals['igst'];
        $total = $totals['total'];
        $itemCount = $totals['itemCount'];

        return view('view.checkout.address', compact('cartItems', 'savedAddress', 'userAddresses', 'subtotal', 'shipping', 'tax', 'cgst', 'sgst', 'igst', 'total', 'itemCount', 'totalWeight', 'discount', 'couponDiscount', 'coupon', 'billingSame'));
    }

    // Save address and redirect to checkout
    public function saveAddress(Request $request)
    {
        $rules = [
            'address_id' => 'nullable',
            'name' => 'required|string|max:255',
            'door_number' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'phone' => 'required|string|max:15',
            'billing_same' => 'nullable',
        ];

        $billingSame = $request->has('billing_same');

        if (!$billingSame) {
            $rules['billing_name'] = 'required|string|max:255';
            $rules['billing_door_number'] = 'nullable|string|max:255';
            $rules['billing_address'] = 'required|string|max:255';
            $rules['billing_city'] = 'required|string|max:100';
            $rules['billing_state'] = 'required|string|max:100';
            $rules['billing_pincode'] = 'required|string|max:10';
            $rules['billing_phone'] = 'required|string|max:15';
        }

        $validated = $request->validate($rules);

        // Prepare shipping address array
        $shippingAddress = [
            'address_id' => $validated['address_id'] ?? null,
            'name' => $validated['name'],
            'door_number' => $validated['door_number'] ?? null,
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'pincode' => $validated['pincode'],
            'phone' => $validated['phone'],
        ];

        // Prepare billing address array
        if ($billingSame) {
            $billingAddress = $shippingAddress;
        } else {
            $billingAddress = [
                'name' => $validated['billing_name'],
                'door_number' => $validated['billing_door_number'] ?? null,
                'address' => $validated['billing_address'],
                'city' => $validated['billing_city'],
                'state' => $validated['billing_state'],
                'pincode' => $validated['billing_pincode'],
                'phone' => $validated['billing_phone'],
            ];
        }

        // Save to session
        session(['shipping_address' => $shippingAddress]);
        session(['billing_address' => $billingAddress]);
        session(['billing_same' => $billingSame]);

        // Optionally save to user profile if logged in
        if (Auth::check()) {
            $user = Auth::user();

            // Update legacy address field
            $user->update([
                'address' => json_encode($shippingAddress),
                'name' => $shippingAddress['name'] ?? $user->name,
                'phone' => $shippingAddress['phone'] ?? $user->phone,
            ]);

            // Save to new UserAddress table
            $nameParts = explode(' ', $shippingAddress['name'], 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            if (!empty($shippingAddress['address_id'])) {
                $user->addresses()->where('id', $shippingAddress['address_id'])->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'door_number' => $shippingAddress['door_number'],
                    'street' => $shippingAddress['address'],
                    'city' => $shippingAddress['city'],
                    'state' => $shippingAddress['state'],
                    'post_code' => $shippingAddress['pincode'],
                    'phone_number' => $shippingAddress['phone'],
                ]);
            } else {
                $user->addresses()->updateOrCreate(
                    [
                        'street' => $shippingAddress['address'],
                        'city' => $shippingAddress['city'],
                        'post_code' => $shippingAddress['pincode'],
                        'phone_number' => $shippingAddress['phone'],
                    ],
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'door_number' => $shippingAddress['door_number'],
                        'state' => $shippingAddress['state'],
                        // If no addresses yet, make this default
                        'is_default' => $user->addresses()->count() === 0,
                    ]
                );
            }
        }

        return redirect()->route('checkout.index')->with('success', 'Address saved successfully!');
    }

    // Show checkout/review page
    public function index()
    {
        $cartItems = Cart::current()->with(['product', 'comboPack'])->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $shippingAddress = session('shipping_address');
        if (!$shippingAddress) {
            return redirect()->route('checkout.address')->with('error', 'Please provide shipping address.');
        }

        $billingAddress = session('billing_address') ?? $shippingAddress;
        $billingSame = session('billing_same', true);

        $totals = $this->calculateTotalsForCheckout($cartItems, $shippingAddress['state']);

        $subtotal = $totals['subtotal'];
        $discount = $totals['discount'];
        $couponDiscount = $totals['couponDiscount'] ?? 0;
        $coupon = $totals['coupon'] ?? null;
        $totalWeight = $totals['totalWeight'];
        $shipping = $totals['shipping'];
        $tax = $totals['tax'];
        $cgst = $totals['cgst'];
        $sgst = $totals['sgst'];
        $igst = $totals['igst'];
        $total = $totals['total'];
        $itemCount = $totals['itemCount'];

        $gstSettings = \App\Models\HeaderFooter::first();

        return view('view.checkout.index', compact('cartItems', 'shippingAddress', 'billingAddress', 'billingSame', 'subtotal', 'shipping', 'tax', 'cgst', 'sgst', 'igst', 'total', 'discount', 'totalWeight', 'itemCount', 'gstSettings', 'couponDiscount', 'coupon'));
    }

    // Place order (confirm and save)
    public function placeOrder(Request $request)
    {
        $cartItems = Cart::current()->with(['product', 'comboPack'])->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $shippingAddress = session('shipping_address');
        if (!$shippingAddress) {
            return redirect()->route('checkout.address')->with('error', 'Please provide shipping address.');
        }

        $totals = $this->calculateTotalsForCheckout($cartItems, $shippingAddress['state']);

        $subtotal = $totals['subtotal'];
        $discount = $totals['discount'];
        $couponDiscount = $totals['couponDiscount'] ?? 0;
        $couponCode = $totals['coupon'] ? $totals['coupon']->coupon_code : null;
        $totalWeight = $totals['totalWeight'];
        $shipping = $totals['shipping'];
        $tax = $totals['tax'];
        $cgst = $totals['cgst'];
        $sgst = $totals['sgst'];
        $igst = $totals['igst'];
        $total = $totals['total'];

        // Prepare Item Data for serialization to Transaction record
        $itemsData = [];
        foreach ($cartItems as $item) {
            $p = $item->combo_pack_id ? $item->comboPack : $item->product;
            $price = $item->calculated_price;
            $regularPrice = $item->combo_pack_id ? $p->total_price : $p->price;
            $unitDiscount = max(0, $regularPrice - $price);

            $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
            $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : (is_string($p->image) ? $p->image : null);

            $itemsData[] = [
                'product_id' => $item->product_id,
                'combo_pack_id' => $item->combo_pack_id,
                'product_name' => $p->name,
                'product_image' => $firstImg,
                'price' => $price,
                'quantity' => $item->quantity,
                'weight' => $item->calculated_weight,
                'discount' => $unitDiscount,
                'total' => $price * $item->quantity,
                'options' => $item->options,
            ];
        }

        // Prepare Custom Combo Details for order_combo_details
        $customComboDetails = [];
        $customComboGroups = $cartItems->filter(function($item) {
            return !empty($item->custom_combo_id);
        })->groupBy('custom_combo_id');

        $slabs = \App\Models\ComboPackDiscountSlab::where('status', true)->orderBy('min_amount', 'asc')->get();
        $gstSettings = \App\Models\HeaderFooter::first();
        $gstPercentage = $gstSettings && $gstSettings->gst_status ? (float)$gstSettings->gst_percentage : 0;

        foreach ($customComboGroups as $comboId => $items) {
            $comboSubtotal = $items->sum(function($item) {
                return $item->calculated_price * $item->quantity;
            });

            $applicableDiscountPercent = 0;
            foreach ($slabs as $slab) {
                if ($comboSubtotal >= $slab->min_amount) {
                    $applicableDiscountPercent = (float) $slab->discount_percentage;
                }
            }

            $comboDiscount = $comboSubtotal * ($applicableDiscountPercent / 100);
            $comboDiscountedTotal = $comboSubtotal - $comboDiscount;
            
            // GST and Shipping proportionate weight share
            $comboGst = ($comboDiscountedTotal * $gstPercentage) / 100;
            
            $comboWeight = $items->sum(function($item) {
                return $item->calculated_weight * $item->quantity;
            });
            $comboShipping = 0;
            if ($totalWeight > 0) {
                $comboShipping = ($comboWeight / $totalWeight) * $shipping;
            }

            $comboFinal = $comboDiscountedTotal + $comboGst + $comboShipping;

            $customComboDetails[] = [
                'custom_combo_id' => $comboId,
                'product_total' => $comboSubtotal,
                'discount_percentage' => $applicableDiscountPercent,
                'discount_amount' => $comboDiscount,
                'discounted_total' => $comboDiscountedTotal,
                'gst_amount' => $comboGst,
                'shipping_amount' => $comboShipping,
                'final_amount' => $comboFinal,
            ];
        }

        // Online Payment Flow - Store all order details in transaction to wait for success
        $transaction = \App\Models\PaymentTransaction::create([
            'user_id' => auth()->id(),
            'transaction_ref' => 'TXN-' . strtoupper(uniqid()),
            'amount' => $total,
            'payment_method' => 'online',
            'status' => 'INITIATED',
            'order_id' => null, // Will be linked after success
            'checkout_data' => [
                'shipping_address' => $shippingAddress,
                'billing_address' => session('billing_address') ?? $shippingAddress,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'tax' => $tax,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'igst' => $igst,
                'total' => $total,
                'total_weight' => $totalWeight,
                'total_discount' => $discount,
                'coupon_code' => $couponCode,
                'coupon_discount' => $couponDiscount,
                'items' => $itemsData,
                'custom_combos' => $customComboDetails
            ]
        ]);

        return redirect()->route('payment.gateway', ['transaction_ref' => $transaction->transaction_ref]);
    }

    private function calculateTotalsForCheckout($cartItems, $state = null)
    {
        $customComboGroups = $cartItems->filter(function($item) {
            return !empty($item->custom_combo_id);
        })->groupBy('custom_combo_id');

        $normalItems = $cartItems->filter(function($item) {
            return empty($item->custom_combo_id);
        });

        $subtotal = 0;
        $discount = 0;
        $totalWeight = 0;

        // Normal items
        foreach ($normalItems as $item) {
            $price = $item->calculated_price;
            
            $subtotal += $price * $item->quantity;
            
            $totalWeight += $item->calculated_weight * $item->quantity;
        }

        // Custom combo groups
        $slabs = \App\Models\ComboPackDiscountSlab::where('status', true)->orderBy('min_amount', 'asc')->get();

        foreach ($customComboGroups as $comboId => $items) {
            $comboSubtotal = $items->sum(function($item) {
                return $item->calculated_price * $item->quantity;
            });

            $applicableDiscountPercent = 0;
            foreach ($slabs as $slab) {
                if ($comboSubtotal >= $slab->min_amount) {
                    $applicableDiscountPercent = (float) $slab->discount_percentage;
                }
            }

            $comboDiscount = $comboSubtotal * ($applicableDiscountPercent / 100);

            $subtotal += $comboSubtotal;
            $discount += $comboDiscount;

            $totalWeight += $items->sum(function($item) {
                return $item->calculated_weight * $item->quantity;
            });
        }

        // Coupon Logic
        $couponDiscount = 0;
        $couponCode = session('coupon_code');
        $coupon = null;

        if ($couponCode) {
            $now = now();
            $coupon = \App\Models\Coupon::where('coupon_code', $couponCode)
                ->where('status', true)
                ->where(function($q) use ($now) {
                    $q->whereNull('valid_from')
                      ->orWhere('valid_from', '<=', $now);
                })
                ->where(function($q) use ($now) {
                    $q->whereNull('valid_to')
                      ->orWhere('valid_to', '>=', $now);
                })
                ->first();

            if ($coupon) {
                $cartValue = $subtotal - $discount;
                $user = Auth::user();

                $isAssigned = $coupon->isValidForUser($user);
                $hasUsed = $user && $coupon->usages()->where('user_id', $user->id)->exists();

                if ($isAssigned && !$hasUsed && $cartValue >= $coupon->minimum_order_amount) {
                    if ($coupon->discount_type === 'percentage') {
                        $couponDiscount = $cartValue * ($coupon->discount_value / 100);
                        if ($coupon->max_discount > 0) {
                            $couponDiscount = min($couponDiscount, $coupon->max_discount);
                        }
                    } else {
                        $couponDiscount = min($coupon->discount_value, $cartValue);
                    }
                } else {
                    session()->forget('coupon_code');
                    $coupon = null;
                }
            } else {
                session()->forget('coupon_code');
            }
        }

        // Shipping
        $shipping = 0;
        if ($state) {
            $shipping = $this->calculateShipping($cartItems, $state);
        }

        // Tax
        $tax = 0;
        $cgst = 0;
        $sgst = 0;
        $igst = 0;
        $gstSettings = \App\Models\HeaderFooter::first();
        if ($gstSettings && $gstSettings->gst_status) {
            $tax = (($subtotal - $discount - $couponDiscount) * $gstSettings->gst_percentage) / 100;
            if ($state) {
                $sellerState = trim($gstSettings->business_state ?? 'Tamil Nadu');
                if (strcasecmp($sellerState, trim($state)) === 0) {
                    $cgst = $tax / 2;
                    $sgst = $tax / 2;
                } else {
                    $igst = $tax;
                }
            }
        }

        $total = ($subtotal - $discount - $couponDiscount) + $shipping + $tax;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'couponDiscount' => $couponDiscount,
            'coupon' => $coupon,
            'totalWeight' => $totalWeight,
            'shipping' => $shipping,
            'tax' => $tax,
            'cgst' => $cgst,
            'sgst' => $sgst,
            'igst' => $igst,
            'total' => $total,
            'itemCount' => $cartItems->sum('quantity')
        ];
    }

    private function calculateShipping($cartItems, $state)
    {
        $totalWeight = $cartItems->sum(function ($item) {
            return $item->calculated_weight * $item->quantity;
        });

        $rate = \App\Models\ShippingRate::where('state_name', $state)->first();
        if (!$rate) {
            $rate = \App\Models\ShippingRate::where('state_name', $state)->first()
                ?? \App\Models\ShippingRate::where('state_name', 'Default')->first()
                ?? \App\Models\ShippingRate::where('state_name', 'All India')->first()
                ?? \App\Models\ShippingRate::first();

            if (!$rate) return 0;
        }

        $shipping = (float) $rate->base_cost;
        if ($totalWeight > $rate->base_weight) {
            $extraWeight = $totalWeight - $rate->base_weight;
            $units = ceil($extraWeight / $rate->additional_weight_unit);
            $shipping += $units * (float) $rate->additional_cost_per_unit;
        }

        return $shipping;
    }

    public function confirmation(Order $order)
    {
        if ($order->user_id !== auth()->id())
            abort(403);
        $order->load('items');
        return view('view.order.confirmation', compact('order'));
    }
}