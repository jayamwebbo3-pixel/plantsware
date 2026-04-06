<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\TempCart;
use App\Models\Product;
use App\Models\ComboPack;
use App\Models\ComboOnlyProduct;
use App\Models\ComboPackProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class TempCartService
{
    /**
     * Reserve stock from Carts table.
     * Prevents duplicate reduction by clearing existing pending temp carts first.
     */
    public function reserveStockForCheckout()
    {
        $carts = Cart::current()->with(['product', 'comboPack'])->get();
        if ($carts->isEmpty()) return true;

        $userId = Auth::id();
        $sessionId = Auth::check() ? null : session()->getId();

        DB::beginTransaction();
        try {
            // First, clear existing pending temp carts for this user/session to avoid duplicates
            // This also restores their stock. Thus stock rollback happens exactly once.
            $this->clearUserTempCarts($userId, $sessionId);

            // Now, reserve fresh stock
            foreach ($carts as $cart) {
                // Check and reduce stock immediately
                $this->checkAndReduceStock($cart);

                TempCart::create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $cart->product_id,
                    'combo_pack_id' => $cart->combo_pack_id,
                    'quantity' => $cart->quantity,
                    'options' => $cart->options,
                    'status' => 'pending',
                ]);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reduces stock
     */
    private function checkAndReduceStock($cart)
    {
        $quantity = $cart->quantity;
        if ($cart->combo_pack_id) {
            $combo = ComboPack::lockForUpdate()->find($cart->combo_pack_id);
            if (!$combo || $combo->stock_quantity < $quantity) {
                throw new Exception("Insufficient stock for {$combo->name}.");
            }
            if ($combo->is_combo_only) {
                $combo->decrement('stock_quantity', $quantity);
            } else {
                $comboProducts = ComboPackProduct::where('combo_pack_id', $combo->id)->first();
                if ($comboProducts && $comboProducts->product_ids) {
                    $this->subtractConstituentStock($comboProducts->product_ids, $quantity);
                }
            }
        } else {
            $product = Product::lockForUpdate()->find($cart->product_id);
            if (!$product || $product->stock_quantity < $quantity) {
                throw new Exception("Insufficient stock for {$product->name}.");
            }
            $product->decrement('stock_quantity', $quantity);
        }
    }

    /**
     * Unreserve (restore) stock for specific user (e.g. they abandon checkout and return to cart)
     */
    public function clearUserTempCarts($userId, $sessionId)
    {
        $query = TempCart::where('status', 'pending');
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $pendingCarts = $query->get();
        foreach ($pendingCarts as $temp) {
            $this->restoreStock($temp);
            $temp->update(['status' => 'expired']); // Keep record in table instead of deleting
        }
    }

    /**
     * Cron job calls this to cleanup auto-expired temp carts after 10 mins
     */
    public function cleanupExpiredTempCarts($minutes = 10)
    {
        $expired = TempCart::where('status', 'pending')
                    ->where('created_at', '<=', now()->subMinutes($minutes))
                    ->get();
        
        foreach ($expired as $temp) {
            $this->restoreStock($temp);
            $temp->update(['status' => 'expired']);
            // The record remains in temp_carts as expired, or we can delete it. Mark expired is fine.
        }
    }

    /**
     * Restore stock logic
     */
    public function restoreStock($tempCart)
    {
        $quantity = $tempCart->quantity;
        if ($tempCart->combo_pack_id) {
            $combo = ComboPack::find($tempCart->combo_pack_id);
            if ($combo) {
                if ($combo->is_combo_only) {
                    $combo->increment('stock_quantity', $quantity);
                } else {
                    $comboProducts = ComboPackProduct::where('combo_pack_id', $combo->id)->first();
                    if ($comboProducts && $comboProducts->product_ids) {
                        $this->addConstituentStock($comboProducts->product_ids, $quantity);
                    }
                }
            }
        } else {
            $product = Product::find($tempCart->product_id);
            if ($product) {
                $product->increment('stock_quantity', $quantity);
            }
        }
    }

    private function subtractConstituentStock(array $productIds, int $orderQuantity)
    {
        foreach ($productIds as $id) {
            if (str_starts_with($id, 'p_')) {
                $realId = str_replace('p_', '', $id);
                Product::where('id', $realId)->decrement('stock_quantity', $orderQuantity);
            } elseif (str_starts_with($id, 'co_')) {
                $realId = str_replace('co_', '', $id);
                ComboOnlyProduct::where('id', $realId)->decrement('stock_quantity', $orderQuantity);
            } elseif (str_starts_with($id, 'c_')) {
                $realId = str_replace('c_', '', $id);
                $nestedCombo = ComboPack::find($realId);
                if ($nestedCombo) {
                    if ($nestedCombo->is_combo_only) {
                        $nestedCombo->decrement('stock_quantity', $orderQuantity);
                    } else {
                        $nestedComboProducts = ComboPackProduct::where('combo_pack_id', $nestedCombo->id)->first();
                        if ($nestedComboProducts && $nestedComboProducts->product_ids) {
                            $this->subtractConstituentStock($nestedComboProducts->product_ids, $orderQuantity);
                        }
                    }
                }
            }
        }
    }

    private function addConstituentStock(array $productIds, int $orderQuantity)
    {
        foreach ($productIds as $id) {
            if (str_starts_with($id, 'p_')) {
                $realId = str_replace('p_', '', $id);
                Product::where('id', $realId)->increment('stock_quantity', $orderQuantity);
            } elseif (str_starts_with($id, 'co_')) {
                $realId = str_replace('co_', '', $id);
                ComboOnlyProduct::where('id', $realId)->increment('stock_quantity', $orderQuantity);
            } elseif (str_starts_with($id, 'c_')) {
                $realId = str_replace('c_', '', $id);
                $nestedCombo = ComboPack::find($realId);
                if ($nestedCombo) {
                    if ($nestedCombo->is_combo_only) {
                        $nestedCombo->increment('stock_quantity', $orderQuantity);
                    } else {
                        $nestedComboProducts = ComboPackProduct::where('combo_pack_id', $nestedCombo->id)->first();
                        if ($nestedComboProducts && $nestedComboProducts->product_ids) {
                            $this->addConstituentStock($nestedComboProducts->product_ids, $orderQuantity);
                        }
                    }
                }
            }
        }
    }
}
