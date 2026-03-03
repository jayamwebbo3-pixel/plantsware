<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart;
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
            if ($transaction->order_id) {
                $orderToFail = Order::find($transaction->order_id);
                if ($orderToFail) {
                    $orderToFail->items()->delete();
                    $orderToFail->delete();
                }
            }
            return redirect()->route('cart.index')->with('error', 'Payment expired due to 10-minutes timeout. Order was not created.');
        }

        if ($status === 'FAILED') {
            $transaction->update(['status' => 'FAILED']);
            if ($transaction->order_id) {
                $orderToFail = Order::find($transaction->order_id);
                if ($orderToFail) {
                    $orderToFail->items()->delete();
                    $orderToFail->delete();
                }
            }
            return redirect()->route('cart.index')->with('error', 'Payment failed. Order was not created.');
        }

        if ($status === 'SUCCESS') {
            try {
                // Ensure transaction hasn't already been processed
                if ($transaction->status === 'SUCCESS') {
                     return redirect()->route('home');
                }

                DB::beginTransaction();

                $order = Order::with('items.product')->findOrFail($transaction->order_id);
                
                // Re-verify stock before confirming the order
                foreach ($order->items as $item) {
                    if (!$item->product || $item->quantity > $item->product->stock_quantity) {
                        throw new Exception("Insufficient stock for one or more items: " . ($item->product->name ?? 'Unknown item'));
                    }
                }

                $order->update([
                    'status' => 'confirmed', // Fulfilment starts at confirmed
                    'payment_status' => 'paid', // Or success
                ]);

                foreach ($order->items as $item) {
                    // Reduce stock
                    Product::where('id', $item->product_id)->decrement('stock_quantity', $item->quantity);
                }

                $transaction->update([
                    'status' => 'SUCCESS'
                ]);

                DB::commit();

                // Clear cart logic
                try {
                    Cart::where('user_id', $transaction->user_id)->delete();
                    session()->forget('shipping_address');
                } catch (Exception $e) { }

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
