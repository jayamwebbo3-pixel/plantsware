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

        // Pre-fill address if user has one saved
        $savedAddress = [];
        $userAddresses = collect();

        // Check if user is logged in and has address
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
            } else {
                // FALLBACK if no addresses in user_addresses table yet
                // 'address' is cast to 'array' in User model, so Eloquent auto-decodes it
                if (!empty($user->address) && is_array($user->address)) {
                    $savedAddress = $user->address;
                }
            }
        }

        // Also check session for previously entered address
        if (session()->has('shipping_address')) {
            $savedAddress = array_merge($savedAddress, session('shipping_address'));
        }

        return view('view.checkout.address', compact('cartItems', 'savedAddress', 'userAddresses'));
    }

    // Save address and redirect to checkout
    public function saveAddress(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable',
            'name' => 'required|string|max:255',
            'door_number' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'phone' => 'required|string|max:15',
        ]);

        // Save to session
        session(['shipping_address' => $validated]);

        // Optionally save to user profile if logged in
        if (Auth::check()) {
            $user = Auth::user();
            
            // Update legacy address field
            $user->update([
                'address' => json_encode($validated),
                'name' => $validated['name'] ?? $user->name,
                'phone' => $validated['phone'] ?? $user->phone,
            ]);

            // Save to new UserAddress table
            $nameParts = explode(' ', $validated['name'], 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            if (!empty($validated['address_id'])) {
                $user->addresses()->where('id', $validated['address_id'])->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'door_number' => $validated['door_number'],
                    'street' => $validated['address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'post_code' => $validated['pincode'],
                    'phone_number' => $validated['phone'],
                ]);
            } else {
                $user->addresses()->updateOrCreate(
                    [
                        'street' => $validated['address'],
                        'city' => $validated['city'],
                        'post_code' => $validated['pincode'],
                        'phone_number' => $validated['phone'],
                    ],
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'door_number' => $validated['door_number'],
                        'state' => $validated['state'],
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

        $subtotal = $cartItems->sum(function ($item) {
            return $item->calculated_price * $item->quantity;
        });

        // Calculate Total Savings/Discount
        $discount = $cartItems->sum(function ($item) {
            $p = $item->combo_pack_id ? $item->comboPack : $item->product;
            $regularPrice = $item->combo_pack_id ? $p->total_price : $p->price;
            $savingsPerItem = max(0, $regularPrice - $item->calculated_price);
            return $savingsPerItem * $item->quantity;
        });

        // Calculate Total Weight
        $totalWeight = $cartItems->sum(function ($item) {
            $p = $item->combo_pack_id ? $item->comboPack : $item->product;
            return ($p->weight ?? 0) * $item->quantity;
        });

        $shipping = $this->calculateShipping($cartItems, $shippingAddress['state']);
        
        // ---- GST CALCULATION ----
        $tax = 0;
        $gstSettings = \App\Models\HeaderFooter::first();
        if ($gstSettings && $gstSettings->gst_status) {
            $tax = ($subtotal * $gstSettings->gst_percentage) / 100;
        }

        $total = $subtotal + $shipping + $tax;

        return view('view.checkout.index', compact('cartItems', 'shippingAddress', 'subtotal', 'shipping', 'tax', 'total', 'discount', 'totalWeight', 'gstSettings'));
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

        $subtotal = $cartItems->sum(function ($item) {
            return $item->calculated_price * $item->quantity;
        });

        // Calculate Total Savings/Discount
        $discount = $cartItems->sum(function ($item) {
            $p = $item->combo_pack_id ? $item->comboPack : $item->product;
            $regularPrice = $item->combo_pack_id ? $p->total_price : $p->price;
            $savingsPerItem = max(0, $regularPrice - $item->calculated_price);
            return $savingsPerItem * $item->quantity;
        });

        // Calculate Total Weight
        $totalWeight = $cartItems->sum(function ($item) {
            $p = $item->combo_pack_id ? $item->comboPack : $item->product;
            return ($p->weight ?? 0) * $item->quantity;
        });

        $shipping = $this->calculateShipping($cartItems, $shippingAddress['state']);
        
        // ---- GST CALCULATION ----
        $tax = 0;
        $gstSettings = \App\Models\HeaderFooter::first();
        if ($gstSettings && $gstSettings->gst_status) {
            $tax = ($subtotal * $gstSettings->gst_percentage) / 100;
        }

        $total = $subtotal + $shipping + $tax;

        $order = Order::create([
            'order_number' => 'PLW-' . strtoupper(uniqid()),
            'user_id' => auth()->id(),
            'shipping_address' => $shippingAddress,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'total_weight' => $totalWeight,
            'total_discount' => $discount,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method ?? 'online',
        ]);

        foreach ($cartItems as $item) {
            $p = $item->combo_pack_id ? $item->comboPack : $item->product;
            $price = $item->calculated_price;
            $regularPrice = $item->combo_pack_id ? $p->total_price : $p->price;
            $unitDiscount = max(0, $regularPrice - $price);

            $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
            $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : (is_string($p->image) ? $p->image : null);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'combo_pack_id' => $item->combo_pack_id,
                'product_name' => $p->name,
                'product_image' => $firstImg,
                'price' => $price,
                'quantity' => $item->quantity,
                'weight' => ($p->weight ?? 0),
                'discount' => $unitDiscount,
                'total' => $price * $item->quantity,
                'options' => $item->options,
            ]);
        }

        // Logic split by payment method
        if ($order->payment_method === 'cod') {
            // Clear cart immediately
            Cart::current()->delete();
            session()->forget('shipping_address');
            session(['cart_count' => 0]);

            return redirect()->route('checkout.confirmation', $order->id)->with('success', 'Order placed successfully (Cash on Delivery).');
        }

        // Online Payment Flow
        $transaction = \App\Models\PaymentTransaction::create([
            'user_id' => auth()->id(),
            'transaction_ref' => 'TXN-' . strtoupper(uniqid()),
            'amount' => $total,
            'payment_method' => 'online',
            'status' => 'INITIATED',
            'order_id' => $order->id,
            'checkout_data' => [] 
        ]);

        return redirect()->route('payment.gateway', ['transaction_ref' => $transaction->transaction_ref]);
    }

    private function calculateShipping($cartItems, $state)
    {
        $totalWeight = $cartItems->sum(function ($item) {
            $p = $item->combo_pack_id ? $item->comboPack : $item->product;
            return ($p->weight ?? 0) * $item->quantity;
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
