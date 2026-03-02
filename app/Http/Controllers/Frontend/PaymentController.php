<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class PaymentController extends Controller
{
    /**
     * Show dummy gateway page
     */
    public function gateway($transaction_ref)
    {
        $transaction = PaymentTransaction::where('transaction_ref', $transaction_ref)->firstOrFail();

        if ($transaction->status !== 'INITIATED' && $transaction->status !== 'PENDING') {
            return redirect()->route('home')->with('error', 'Invalid transaction status.');
        }

        // Simulating the PENDING status as soon as they reach gateway
        if ($transaction->status === 'INITIATED') {
            $transaction->update(['status' => 'PENDING']);
        }

        return view('view.payment.gateway', compact('transaction'));
    }

    /**
     * Process Gateway Callback
     */
    public function callback(Request $request)
    {
        $transaction_ref = $request->input('transaction_ref');
        $status = $request->input('status'); // SUCCCESS, FAILED

        $transaction = PaymentTransaction::where('transaction_ref', $transaction_ref)->firstOrFail();

        if ($transaction->status !== 'PENDING') {
            return redirect()->route('home')->with('error', 'Transaction is no longer pending or is invalid.');
        }

        // Check if 10 mins have passed
        if ($transaction->created_at->diffInMinutes(now()) > 10) {
            $transaction->update(['status' => 'EXPIRED']);
            return redirect()->route('cart.index')->with('error', 'Payment expired due to 10-minutes timeout.');
        }

        if ($status === 'FAILED') {
            $transaction->update(['status' => 'FAILED']);
            return redirect()->route('cart.index')->with('error', 'Payment failed.');
        }

        if ($status === 'SUCCESS') {
            try {
                // Ensure order is not duplicated
                if ($transaction->order_id) {
                     return redirect()->route('home');
                }

                DB::beginTransaction();

                $checkoutData = $transaction->checkout_data;
                $cartItems = $checkoutData['cart_items'];
                $shippingAddress = $checkoutData['shipping_address'];
                
                // Re-verify stock before creating order
                foreach ($cartItems as $item) {
                    $product = Product::find($item['product_id']);
                    if (!$product || $item['quantity'] > $product->stock_quantity) {
                        throw new Exception("Insufficient stock for one or more items.");
                    }
                }

                $order = Order::create([
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'user_id' => $transaction->user_id,
                    'shipping_address' => json_encode($shippingAddress),
                    'subtotal' => $checkoutData['subtotal'],
                    'shipping' => $checkoutData['shipping'],
                    'tax' => $checkoutData['tax'],
                    'total' => $checkoutData['total'],
                    'status' => 'confirmed', // Fulfilment starts at confirmed
                    'payment_status' => 'paid', // Or success
                    'payment_method' => $transaction->payment_method,
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'product_image' => $item['product_image'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'total' => $item['total'],
                    ]);

                    // Reduce stock
                    Product::where('id', $item['product_id'])->decrement('stock_quantity', $item['quantity']);
                }

                $transaction->update([
                    'status' => 'SUCCESS',
                    'order_id' => $order->id
                ]);

                DB::commit();

                // Cart is cleared in the transaction start placeOrder inside CheckoutController

                return redirect()->route('checkout.confirmation', $order->id)->with('success', 'Payment successful and Order placed!');

            } catch (Exception $e) {
                DB::rollBack();
                $transaction->update(['status' => 'FAILED']);
                return redirect()->route('cart.index')->with('error', $e->getMessage());
            }
        }

        return redirect()->route('home');
    }
}
