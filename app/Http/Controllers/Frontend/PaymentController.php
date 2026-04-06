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

        // [DEMO FEATURE] Test 10 Minute Cron Job Timeout
        if ($status === 'EXPIRE_DEMO') {
            // 1. Backdate the Transaction 11 minutes
            $transaction->created_at = now()->subMinutes(11);
            $transaction->save();

            // 2. Backdate the TempCart records so the Cron will pick them up
            \App\Models\TempCart::where('user_id', $transaction->user_id)
                ->where('status', 'pending')
                ->update(['created_at' => now()->subMinutes(11)]);

            // 3. Trigger the Laravel schedule manually to run the 1-minute cron
            \Illuminate\Support\Facades\Artisan::call('schedule:run');

            return redirect()->route('cart.index')->with('error', 'Cron Job Triggered successfully! Over 10 minutes passed. Transaction expired and Stock restored automatically.');
        }

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
            app(\App\Services\TempCartService::class)->clearUserTempCarts($transaction->user_id, null);
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
            app(\App\Services\TempCartService::class)->clearUserTempCarts($transaction->user_id, null);
            return redirect()->route('cart.index')->with('error', 'Payment failed. Order was not created.');
        }

        if ($status === 'SUCCESS') {
            try {
                // Ensure transaction hasn't already been processed
                if ($transaction->status === 'SUCCESS') {
                    return redirect()->route('home');
                }

                DB::beginTransaction();

                // Get checkout data from transaction
                $checkoutData = $transaction->checkout_data;
                if (!$checkoutData || !isset($checkoutData['items'])) {
                    throw new Exception("Invalid checkout data.");
                }

                // 1. Create the Final Order
                $order = Order::create([
                    'order_number' => 'PLW-' . strtoupper(uniqid()),
                    'user_id' => $transaction->user_id,
                    'shipping_address' => $checkoutData['shipping_address'] ?? [],
                    'subtotal' => $checkoutData['subtotal'] ?? 0,
                    'shipping' => $checkoutData['shipping'] ?? 0,
                    'tax' => $checkoutData['tax'] ?? 0,
                    'total' => $checkoutData['total'] ?? 0,
                    'total_weight' => $checkoutData['total_weight'] ?? 0,
                    'total_discount' => $checkoutData['total_discount'] ?? 0,
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'payment_method' => $transaction->payment_method ?? 'online',
                ]);

                // 2. Create Order Items
                foreach ($checkoutData['items'] as $itemData) {
                    OrderItem::create(array_merge($itemData, ['order_id' => $order->id]));
                }

                // 3. Link transaction back to order
                $transaction->update([
                    'status' => 'SUCCESS',
                    'order_id' => $order->id
                ]);

                // 4. Update TempCarts to paid since stock was already reduced during checkout initiation
                \App\Models\TempCart::where('user_id', $transaction->user_id)
                    ->where('status', 'pending')
                    ->update(['status' => 'paid']);

                DB::commit();

                // Clear live cart
                try {
                    Cart::where('user_id', $transaction->user_id)->delete();
                    session()->forget('shipping_address');
                    session(['cart_count' => 0]);
                } catch (Exception $e) {
                }

                // Send Confirmation Emails
                try {
                    \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\OrderConfirmation($order));
                    $headerFooter = \App\Models\HeaderFooter::first();
                    if (!empty($headerFooter->email)) {
                        \Illuminate\Support\Facades\Mail::to($headerFooter->email)->send(new \App\Mail\OrderConfirmation($order));
                    }
                } catch (Exception $e) {
                    // Log mail error but don't fail the order
                }

                return redirect()->route('checkout.confirmation', $order->id)->with('success', 'Payment successful and Order placed!');
            } catch (Exception $e) {
                DB::rollBack();
                $transaction->update(['status' => 'FAILED']);
                return redirect()->route('cart.index')->with('error', $e->getMessage());
            }
        }

        return redirect()->route('home');
    }
    // helper methods removed, originally handled constituent stock reduction
}
