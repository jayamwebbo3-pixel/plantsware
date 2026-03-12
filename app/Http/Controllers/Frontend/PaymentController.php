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

                $order = Order::with(['items.product', 'user'])->findOrFail($transaction->order_id);

                // Re-verify stock before confirming the order
                foreach ($order->items as $item) {
                    $stock = 0;
                    $name = '';
                    if ($item->combo_pack_id) {
                        $combo = \App\Models\ComboPack::find($item->combo_pack_id);
                        $stock = $combo ? $combo->stock_quantity : 0;
                        $name = $combo ? $combo->name : 'Unknown Combo';
                    } else {
                        $stock = $item->product ? $item->product->stock_quantity : 0;
                        $name = $item->product ? $item->product->name : 'Unknown Product';
                    }

                    if ($item->quantity > $stock) {
                        throw new Exception("Insufficient stock for: " . $name);
                    }
                }

                $order->update([
                    'status' => 'confirmed', // Fulfilment starts at confirmed
                    'payment_status' => 'paid', // Or success
                ]);

                foreach ($order->items as $item) {
                    if ($item->combo_pack_id) {
                        $combo = \App\Models\ComboPack::find($item->combo_pack_id);
                        if ($combo) {
                            if ($combo->is_combo_only) {
                                // Combo-only: Subtract from the combo_packs table
                                $combo->decrement('stock_quantity', $item->quantity);
                            } else {
                                // Standard combo: Subtract from the constituent products
                                $comboProducts = \App\Models\ComboPackProduct::where('combo_pack_id', $combo->id)->first();
                                if ($comboProducts && $comboProducts->product_ids) {
                                    $this->subtractConstituentStock($comboProducts->product_ids, $item->quantity);
                                }
                            }
                        }
                    } else {
                        // Regular product stock subtraction
                        Product::where('id', $item->product_id)->decrement('stock_quantity', $item->quantity);
                    }
                }

                $transaction->update([
                    'status' => 'SUCCESS'
                ]);

                DB::commit();

                // Clear cart logic
                try {
                    Cart::where('user_id', $transaction->user_id)->delete();
                    session()->forget('shipping_address');
                } catch (Exception $e) {
                }

                \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\OrderConfirmation($order));
                
                // Also send to Admin
                $headerFooter = \App\Models\HeaderFooter::first();
                if(!empty($headerFooter->email)) {
                    \Illuminate\Support\Facades\Mail::to($headerFooter->email)->send(new \App\Mail\OrderConfirmation($order));
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
    /**
     * Helper to subtract stock for constituent products/combos recursively
     */
    private function subtractConstituentStock(array $productIds, int $orderQuantity)
    {
        foreach ($productIds as $id) {
            if (str_starts_with($id, 'p_')) {
                // Regular product: Subtract stock
                $realId = str_replace('p_', '', $id);
                \App\Models\Product::where('id', $realId)->decrement('stock_quantity', $orderQuantity);
            } elseif (str_starts_with($id, 'co_')) {
                // Combo only product: Subtract stock
                $realId = str_replace('co_', '', $id);
                \App\Models\ComboOnlyProduct::where('id', $realId)->decrement('stock_quantity', $orderQuantity);
            } elseif (str_starts_with($id, 'c_')) {
                // Nested combo: Recursive subtraction or combo stock decrement
                $realId = str_replace('c_', '', $id);
                $nestedCombo = \App\Models\ComboPack::find($realId);
                if ($nestedCombo) {
                    if ($nestedCombo->is_combo_only) {
                        $nestedCombo->decrement('stock_quantity', $orderQuantity);
                    } else {
                        $nestedComboProducts = \App\Models\ComboPackProduct::where('combo_pack_id', $nestedCombo->id)->first();
                        if ($nestedComboProducts && $nestedComboProducts->product_ids) {
                            $this->subtractConstituentStock($nestedComboProducts->product_ids, $orderQuantity);
                        }
                    }
                }
            }
        }
    }
}
