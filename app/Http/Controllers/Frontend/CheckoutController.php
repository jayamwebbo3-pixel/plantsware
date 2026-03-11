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

        // Pre-fill address if user has one saved
        $savedAddress = [];

        // Check if user is logged in and has address
        if (Auth::check()) {
            $user = Auth::user();

            // 'address' is cast to 'array' in User model, so Eloquent auto-decodes it
            if (!empty($user->address) && is_array($user->address)) {
                $savedAddress = $user->address;
            }

            // Fallback to individual user fields if no address array found
            if (empty($savedAddress)) {
                $savedAddress = [
                    'name' => $user->name ?? '',
                    'address' => $user->address_line1 ?? '',
                    'city' => $user->city ?? '',
                    'state' => $user->state ?? '',
                    'pincode' => $user->pincode ?? $user->zip_code ?? '',
                    'phone' => $user->phone ?? $user->mobile ?? '',
                ];
            }
        }

        // Also check session for previously entered address
        if (session()->has('shipping_address')) {
            $savedAddress = array_merge($savedAddress, session('shipping_address'));
        }

        return view('view.checkout.address', compact('cartItems', 'savedAddress'));
    }

    // Save address and redirect to checkout
    public function saveAddress(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
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
            Auth::user()->update([
                'address' => json_encode($validated),
                'name' => $validated['name'] ?? Auth::user()->name,
                'phone' => $validated['phone'] ?? Auth::user()->phone,
            ]);
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
        $shipping = 0; // Fixed or calculate
        $tax = 0; // Fixed or calculate
        $total = $subtotal + $shipping + $tax;

        return view('view.checkout.index', compact('cartItems', 'shippingAddress', 'subtotal', 'shipping', 'tax', 'total'));
    }

    // Place order (confirm and save)
    public function placeOrder(Request $request)
    {
        // dd(auth()->check(), auth()->id());

        $cartItems = Cart::current()->with(['product', 'comboPack'])->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $shippingAddress = session('shipping_address');
        if (!$shippingAddress) {
            return redirect()->route('checkout.address')->with('error', 'Please provide shipping address.');
        }

        // Validate stock again
        foreach ($cartItems as $item) {
            $stock = $item->combo_pack_id ? $item->comboPack->stock_quantity : $item->product->stock_quantity;
            $name = $item->combo_pack_id ? $item->comboPack->name : $item->product->name;
            if ($item->quantity > $stock) {
                return back()->with('error', "Insufficient stock for {$name}.");
            }
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->calculated_price * $item->quantity;
        });
        $shipping = 0;
        $tax = 0;
        $total = $subtotal + $shipping + $tax;

        $checkoutItems = [];
        foreach ($cartItems as $item) {
            $p = $item->combo_pack_id ? $item->comboPack : $item->product;
            $price = $item->calculated_price;

            $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
            $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : (is_string($p->image) ? $p->image : null);

            $checkoutItems[] = [
                'product_id' => $item->product_id,
                'combo_pack_id' => $item->combo_pack_id,
                'product_name' => $p->name,
                'product_image' => $firstImg,
                'price' => $price,
                'quantity' => $item->quantity,
                'total' => $price * $item->quantity,
                'options' => $item->options,
            ];
        }

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'user_id' => auth()->id(),
            'shipping_address' => $shippingAddress, // JSON encoded via Model cast
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'online',
        ]);

        foreach ($checkoutItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'combo_pack_id' => $item['combo_pack_id'],
                'product_name' => $item['product_name'],
                'product_image' => $item['product_image'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['total'],
                'options' => $item['options'],
            ]);
        }

        $transaction = \App\Models\PaymentTransaction::create([
            'user_id' => auth()->id(),
            'transaction_ref' => 'TXN-' . strtoupper(uniqid()),
            'amount' => $total,
            'payment_method' => 'online',
            'status' => 'INITIATED',
            'order_id' => $order->id,
            'checkout_data' => [] // No longer need to save array in JSON, the order contains it all
        ]);

        return redirect()->route('payment.gateway', ['transaction_ref' => $transaction->transaction_ref]);
    }

    public function confirmation(Order $order)
    {
        if ($order->user_id !== auth()->id())
            abort(403);
        return view('view.order.confirmation', compact('order'));
    }
}