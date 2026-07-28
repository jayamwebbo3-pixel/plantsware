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
                    $this->checkConstituentStock($comboProducts->product_ids, $quantity);
                    $this->subtractConstituentStock($comboProducts->product_ids, $quantity);
                }
            }
        } else {
            $product = Product::lockForUpdate()->find($cart->product_id);
            if (!$product) {
                throw new Exception("Product not found.");
            }

            $selectedSize = null;
            if ($cart->options) {
                $optionsObj = is_string($cart->options) ? json_decode($cart->options, true) : $cart->options;
                if (isset($optionsObj['size'])) {
                    $selectedSize = $optionsObj['size'];
                }
            }

            if (!$selectedSize && $product->size) {
                $sizesObj = is_string($product->size) ? json_decode($product->size, true) : $product->size;
                if (is_array($sizesObj) && count($sizesObj) > 0) {
                    $selectedSize = array_key_first($sizesObj);
                }
            }

            if ($selectedSize && $product->size) {
                $sizesObj = is_string($product->size) ? json_decode($product->size, true) : $product->size;
                if (is_array($sizesObj)) {
                    $foundKey = null;
                    if (isset($sizesObj[$selectedSize])) {
                        $foundKey = $selectedSize;
                    } else {
                        foreach ($sizesObj as $key => $val) {
                            if (strcasecmp($key, $selectedSize) === 0) {
                                $foundKey = $key;
                                break;
                            }
                        }
                    }
                    if ($foundKey && isset($sizesObj[$foundKey]['stock'])) {
                        $sizeStock = $sizesObj[$foundKey]['stock'];
                        if ($sizeStock !== null && $sizeStock !== '') {
                            $sizeStock = (int)$sizeStock;
                            if ($sizeStock < $quantity) {
                                throw new Exception("Insufficient stock for {$product->name} ({$selectedSize}).");
                            }
                            $sizesObj[$foundKey]['stock'] = $sizeStock - $quantity;
                            $currentSold = isset($sizesObj[$foundKey]['sold_quantity']) ? (int)$sizesObj[$foundKey]['sold_quantity'] : 0;
                            $sizesObj[$foundKey]['sold_quantity'] = $currentSold + $quantity;
                            $product->size = $sizesObj;
                            $product->save();
                            return;
                        }
                    }
                }
            }

            if ($product->stock_quantity < $quantity) {
                throw new Exception("Insufficient stock for {$product->name}.");
            }
            $product->decrement('stock_quantity', $quantity);
            $product->increment('sold_quantity', $quantity);
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
                $selectedSize = null;
                if ($tempCart->options) {
                    $optionsObj = is_string($tempCart->options) ? json_decode($tempCart->options, true) : $tempCart->options;
                    if (isset($optionsObj['size'])) {
                        $selectedSize = $optionsObj['size'];
                    }
                }
                if (!$selectedSize && $product->size) {
                    $sizesObj = is_string($product->size) ? json_decode($product->size, true) : $product->size;
                    if (is_array($sizesObj) && count($sizesObj) > 0) {
                        $selectedSize = array_key_first($sizesObj);
                    }
                }
                if ($selectedSize && $product->size) {
                    $sizesObj = is_string($product->size) ? json_decode($product->size, true) : $product->size;
                    if (is_array($sizesObj)) {
                        $foundKey = null;
                        if (isset($sizesObj[$selectedSize])) {
                            $foundKey = $selectedSize;
                        } else {
                            foreach ($sizesObj as $key => $val) {
                                if (strcasecmp($key, $selectedSize) === 0) {
                                    $foundKey = $key;
                                    break;
                                }
                            }
                        }
                        if ($foundKey && isset($sizesObj[$foundKey]['stock'])) {
                            $sizeStock = $sizesObj[$foundKey]['stock'];
                            if ($sizeStock !== null && $sizeStock !== '') {
                                $sizesObj[$foundKey]['stock'] = (int)$sizeStock + $quantity;
                                $currentSold = isset($sizesObj[$foundKey]['sold_quantity']) ? (int)$sizesObj[$foundKey]['sold_quantity'] : 0;
                                $newSold = $currentSold - $quantity;
                                $sizesObj[$foundKey]['sold_quantity'] = $newSold >= 0 ? $newSold : 0;
                                $product->size = $sizesObj;
                                $product->save();
                                return;
                            }
                        }
                    }
                }

                $product->increment('stock_quantity', $quantity);
                if ($product->sold_quantity >= $quantity) {
                    $product->decrement('sold_quantity', $quantity);
                } else {
                    $product->sold_quantity = 0;
                    $product->save();
                }
            }
        }
    }

    private function subtractConstituentStock(array $productIds, int $orderQuantity)
    {
        foreach ($productIds as $id) {
            if (str_starts_with($id, 'p_')) {
                $realId = str_replace('p_', '', $id);
                $prod = Product::find($realId);
                if ($prod) {
                    $prod->decrement('stock_quantity', $orderQuantity);
                    $prod->increment('sold_quantity', $orderQuantity);
                }
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
                $prod = Product::find($realId);
                if ($prod) {
                    $prod->increment('stock_quantity', $orderQuantity);
                    if ($prod->sold_quantity >= $orderQuantity) {
                        $prod->decrement('sold_quantity', $orderQuantity);
                    } else {
                        $prod->sold_quantity = 0;
                        $prod->save();
                    }
                }
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

    private function checkConstituentStock(array $productIds, int $quantity)
    {
        foreach ($productIds as $id) {
            if (str_starts_with($id, 'p_')) {
                $realId = str_replace('p_', '', $id);
                $p = Product::find($realId);
                if (!$p || $p->stock_quantity < $quantity) {
                    $name = $p ? $p->name : 'Product';
                    throw new Exception("Insufficient stock for constituent product: {$name}.");
                }
            } elseif (str_starts_with($id, 'co_')) {
                $realId = str_replace('co_', '', $id);
                $co = ComboOnlyProduct::find($realId);
                if (!$co || $co->stock_quantity < $quantity) {
                    $name = $co ? $co->name : 'Product';
                    throw new Exception("Insufficient stock for constituent product: {$name}.");
                }
            } elseif (str_starts_with($id, 'c_')) {
                $realId = str_replace('c_', '', $id);
                $nestedCombo = ComboPack::find($realId);
                if (!$nestedCombo || $nestedCombo->stock_quantity < $quantity) {
                    $name = $nestedCombo ? $nestedCombo->name : 'Combo Pack';
                    throw new Exception("Insufficient stock for constituent combo: {$name}.");
                }
                if (!$nestedCombo->is_combo_only) {
                    $nestedComboProducts = ComboPackProduct::where('combo_pack_id', $nestedCombo->id)->first();
                    if ($nestedComboProducts && $nestedComboProducts->product_ids) {
                        $this->checkConstituentStock($nestedComboProducts->product_ids, $quantity);
                    }
                }
            }
        }
    }
}
