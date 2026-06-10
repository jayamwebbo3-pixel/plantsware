<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use App\Services\TempCartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index(TempCartService $tempCartService)
    {
        $tempCartService->clearUserTempCarts(Auth::id(), Auth::check() ? null : session()->getId());

        $cartItems = Cart::current()->with(['product', 'comboPack'])->get();
        $totals = $this->calculateCartTotals($cartItems);

        $this->updateSessionCounts();

        return view('view.cart', array_merge(['cartItems' => $cartItems], $totals));
    }

    private function calculateCartTotals($cartItems)
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

        // Process normal items
        foreach ($normalItems as $item) {
            $price = $item->calculated_price;
            $origPrice = $item->original_price;
            
            $subtotal += $origPrice * $item->quantity;
            $discount += max(0, $origPrice - $price) * $item->quantity;
            
            $totalWeight += $item->calculated_weight * $item->quantity;
        }

        // Process custom combo groups
        $slabs = \App\Models\ComboPackDiscountSlab::where('status', true)->orderBy('min_amount', 'asc')->get();

        foreach ($customComboGroups as $comboId => $items) {
            $comboSubtotal = $items->sum(function($item) {
                return $item->calculated_price * $item->quantity;
            });

            $comboOriginalSubtotal = $items->sum(function($item) {
                return $item->original_price * $item->quantity;
            });

            $applicableDiscountPercent = 0;
            foreach ($slabs as $slab) {
                if ($comboSubtotal >= $slab->min_amount) {
                    $applicableDiscountPercent = (float) $slab->discount_percentage;
                }
            }

            $comboDiscount = $comboSubtotal * ($applicableDiscountPercent / 100);

            $subtotal += $comboOriginalSubtotal;
            $discount += $comboDiscount + ($comboOriginalSubtotal - $comboSubtotal);

            $totalWeight += $items->sum(function($item) {
                return $item->calculated_weight * $item->quantity;
            });
        }

        $shipping = 0;
        $defaultRate = \App\Models\ShippingRate::where('state_name', 'Default')->first()
            ?? \App\Models\ShippingRate::where('state_name', 'All India')->first()
            ?? \App\Models\ShippingRate::first();

        if ($defaultRate) {
            $shipping = (float) $defaultRate->base_cost;
            if ($totalWeight > $defaultRate->base_weight) {
                $extraWeight = $totalWeight - $defaultRate->base_weight;
                $units = ceil($extraWeight / $defaultRate->additional_weight_unit);
                $shipping += $units * (float) $defaultRate->additional_cost_per_unit;
            }
        }

        // Tax Calculation
        $settings = \App\Models\HeaderFooter::first();
        $tax = 0;
        $taxPercentage = 0;
        if ($settings && $settings->gst_status) {
            $taxPercentage = (float)$settings->gst_percentage;
            $tax = (($subtotal - $discount) * $taxPercentage) / 100;
        }

        $total = ($subtotal - $discount) + $shipping + $tax;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'totalWeight' => $totalWeight,
            'shipping' => $shipping,
            'tax' => $tax,
            'taxPercentage' => $taxPercentage,
            'total' => $total,
        ];
    }


    public function add(Request $request, Product $product)
    {
        return $this->addToCart($request, $product, 'product');
    }

    /**
     * Add combo pack to cart
     */
    public function addCombo(Request $request, \App\Models\ComboPack $combo)
    {
        return $this->addToCart($request, $combo, 'combo');
    }

    /**
     * Internal helper for adding to cart
     */
    protected function addToCart(Request $request, $item, $type)
    {
        app(TempCartService::class)->clearUserTempCarts(Auth::id(), Auth::check() ? null : session()->getId());

        $quantity = max(1, (int) $request->input('quantity', 1));

        $optionsInput = $request->input('options') ?? $request->input('size');
        $options = null;
        if (is_string($optionsInput) && !is_object(json_decode($optionsInput))) {
            $options = json_encode(['size' => $optionsInput]);
        } elseif ($optionsInput) {
            $options = is_string($optionsInput) ? $optionsInput : json_encode($optionsInput);
        }
        $itemId = $item->id;
        $name = $item->name;
        $stock = $item->stock_quantity;

        $selectedSize = null;
        if ($type === 'product' && $item->has_variants) {
            if ($options) {
                $optionsObj = json_decode($options, true);
                if (isset($optionsObj['size'])) {
                    $selectedSize = $optionsObj['size'];
                }
            }
            if (!$selectedSize && $item->size) {
                $sizesObj = is_string($item->size) ? json_decode($item->size, true) : $item->size;
                if (is_array($sizesObj) && count($sizesObj) > 0) {
                    $selectedSize = array_key_first($sizesObj);
                }
            }
            if ($selectedSize && $item->size) {
                $sizesObj = is_string($item->size) ? json_decode($item->size, true) : $item->size;
                if (is_array($sizesObj)) {
                    $foundSize = null;
                    if (isset($sizesObj[$selectedSize])) {
                        $foundSize = $sizesObj[$selectedSize];
                    } else {
                        foreach ($sizesObj as $key => $val) {
                            if (strcasecmp($key, $selectedSize) === 0) {
                                $foundSize = $val;
                                break;
                            }
                        }
                    }
                    if ($foundSize && is_array($foundSize) && isset($foundSize['stock'])) {
                        if ($foundSize['stock'] !== null && $foundSize['stock'] !== '') {
                            $stock = (int)$foundSize['stock'];
                        }
                    }
                }
            }
        }

        if ($stock < $quantity) {
            $msg = "Only {$stock} item(s) left in stock!";
            return $request->ajax() ? response()->json(['success' => false, 'message' => $msg], 400) : back()->with('error', $msg);
        }

        $query = Cart::current();

        if ($type === 'product') {
            $query->where('product_id', $itemId);

            if ($options) {
                $query->where('options', $options);
            } else {
                $query->whereNull('options');
            }
        } else {
            $query->where('combo_pack_id', $itemId);
        }

        $existingItem = $query->first();
        $currentQuantity = $existingItem ? $existingItem->quantity : 0;
        $newTotalQuantity = $currentQuantity + $quantity;

        if ($newTotalQuantity > $stock) {
            $canAdd = max(0, $stock - $currentQuantity);
            $msg = "You can only add {$canAdd} more item(s) of this item.";
            return $request->ajax() ? response()->json(['success' => false, 'message' => $msg], 400) : back()->with('error', $msg);
        }

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'session_id' => Auth::check() ? null : session()->getId(),
                'product_id' => $type === 'product' ? $itemId : null,
                'combo_pack_id' => $type === 'combo' ? $itemId : null,
                'quantity' => $quantity,
                'options'     => $options,
            ]);
        }

        $this->updateSessionCounts();

        $cartCount = Cart::current()->sum('quantity') ?? 0;
        $cartItems = Cart::current()->with(['product', 'comboPack'])->get();
        $totals = $this->calculateCartTotals($cartItems);

        if ($request->ajax()) {
            return response()->json(array_merge([
                'success' => true,
                'message' => "{$quantity} × {$name} added to cart!",
                'cart_count' => $cartCount
            ], $totals));
        }

        if ($request->input('buy_now')) {
            return redirect()->route('checkout.address')->with('success', "{$quantity} × {$name} added to cart! Proceeding to checkout.");
        }

        return back()->with('success', "{$quantity} × {$name} added to cart!");
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        app(TempCartService::class)->clearUserTempCarts(Auth::id(), Auth::check() ? null : session()->getId());

        $cartItem = Cart::findOrFail($id);
        $this->authorizeCartItem($cartItem);

        $quantity = max(1, (int) $request->input('quantity'));

        if (!$cartItem->relationLoaded('product')) {
            $cartItem->load(['product', 'comboPack']);
        }

        $stock = $cartItem->combo_pack_id ? $cartItem->comboPack->stock_quantity : $cartItem->product->stock_quantity;

        if (!$cartItem->combo_pack_id && $cartItem->product) {
            $selectedSize = null;
            if ($cartItem->product->has_variants) {
                if ($cartItem->options) {
                    $optionsObj = is_string($cartItem->options) ? json_decode($cartItem->options, true) : $cartItem->options;
                    if (isset($optionsObj['size'])) {
                        $selectedSize = $optionsObj['size'];
                    }
                }
                if (!$selectedSize && $cartItem->product->size) {
                    $sizesObj = is_string($cartItem->product->size) ? json_decode($cartItem->product->size, true) : $cartItem->product->size;
                    if (is_array($sizesObj) && count($sizesObj) > 0) {
                        $selectedSize = array_key_first($sizesObj);
                    }
                }
                if ($selectedSize && $cartItem->product->size) {
                    $sizesObj = is_string($cartItem->product->size) ? json_decode($cartItem->product->size, true) : $cartItem->product->size;
                    if (is_array($sizesObj)) {
                        $foundSize = null;
                        if (isset($sizesObj[$selectedSize])) {
                            $foundSize = $sizesObj[$selectedSize];
                        } else {
                            foreach ($sizesObj as $key => $val) {
                                if (strcasecmp($key, $selectedSize) === 0) {
                                    $foundSize = $val;
                                    break;
                                }
                            }
                        }
                        if ($foundSize && is_array($foundSize) && isset($foundSize['stock'])) {
                            if ($foundSize['stock'] !== null && $foundSize['stock'] !== '') {
                                $stock = (int)$foundSize['stock'];
                            }
                        }
                    }
                }
            }
        }

        if ($quantity > $stock) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$stock} item(s) available."
                ], 400);
            }
            return back()->with('error', "Only {$stock} item(s) available.");
        }

        $cartItem->update(['quantity' => $quantity]);
        $this->updateSessionCounts();

        $cartCount = Cart::current()->sum('quantity') ?? 0;
        $cartItems = Cart::current()->with(['product', 'comboPack'])->get();
        $totals = $this->calculateCartTotals($cartItems);

        $priceToUseForItem = $cartItem->calculated_price;
        $itemTotal = $priceToUseForItem * $quantity;

        if ($request->ajax()) {
            return response()->json(array_merge([
                'success' => true,
                'message' => 'Quantity updated successfully!',
                'cart_count' => $cartCount,
                'item_total' => $itemTotal,
                'quantity' => $quantity
            ], $totals));
        }

        return back()->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $id)
    {
        app(TempCartService::class)->clearUserTempCarts(Auth::id(), Auth::check() ? null : session()->getId());

        $cartItem = Cart::findOrFail($id);
        $this->authorizeCartItem($cartItem);

        $itemId = $cartItem->id;
        $customComboId = $cartItem->custom_combo_id;
        $deletedIds = [$itemId];

        if ($customComboId) {
            $itemsToDelete = Cart::current()->get()->filter(function($item) use ($customComboId) {
                return $item->custom_combo_id === $customComboId;
            })->pluck('id')->toArray();
            Cart::whereIn('id', $itemsToDelete)->delete();
            $deletedIds = $itemsToDelete;
        } else {
            $cartItem->delete();
        }

        $this->updateSessionCounts();

        $cartCount = Cart::current()->sum('quantity') ?? 0;
        $cartItems = Cart::current()->with(['product', 'comboPack'])->get();
        $totals = $this->calculateCartTotals($cartItems);

        if ($request->ajax()) {
            return response()->json(array_merge([
                'success' => true,
                'message' => $customComboId ? 'Custom Combo Pack removed from cart.' : 'Item removed from cart.',
                'cart_count' => $cartCount,
                'item_id' => $itemId,
                'deleted_ids' => $deletedIds
            ], $totals));
        }

        return back()->with('success', $customComboId ? 'Custom Combo Pack removed from cart.' : 'Item removed from cart.');
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request)
    {
        app(TempCartService::class)->clearUserTempCarts(Auth::id(), Auth::check() ? null : session()->getId());

        Cart::current()->delete();

        $this->updateSessionCounts();

        $cartCount = 0;
        $totals = $this->calculateCartTotals(collect());

        if ($request->ajax()) {
            return response()->json(array_merge([
                'success' => true,
                'message' => 'Cart cleared successfully!',
                'cart_count' => $cartCount
            ], $totals));
        }

        return back()->with('success', 'Cart cleared successfully!');
    }

    /**
     * Add to wishlist (requires login)
     */
    /**
     * Add to wishlist (requires login)
     */
    public function addToWishlist(Request $request, Product $product)
    {
        return $this->handleAddToWishlist($request, $product, 'product');
    }

    public function addToWishlistCombo(Request $request, \App\Models\ComboPack $combo)
    {
        return $this->handleAddToWishlist($request, $combo, 'combo');
    }

    protected function handleAddToWishlist(Request $request, $item, $type)
    {
        if (!Auth::check()) {
            $msg = 'Please login to add items to your wishlist.';
            return $request->ajax() ? response()->json(['success' => false, 'message' => $msg, 'login_url' => route('login')], 401) : back()->with('error', $msg);
        }

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $type === 'product' ? $item->id : null,
            'combo_pack_id' => $type === 'combo' ? $item->id : null,
        ]);

        $this->updateSessionCounts();
        $wishlistCount = Auth::user()->wishlist()->count() ?? 0;

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$item->name} added to wishlist!",
                'wishlist_count' => $wishlistCount
            ]);
        }

        return back()->with('success', "{$item->name} added to wishlist!");
    }

    /**
     * Remove from wishlist
     */
    public function removeFromWishlist(Request $request, Product $product)
    {
        return $this->handleRemoveFromWishlist($request, $product, 'product');
    }

    public function removeFromWishlistCombo(Request $request, \App\Models\ComboPack $combo)
    {
        return $this->handleRemoveFromWishlist($request, $combo, 'combo');
    }

    protected function handleRemoveFromWishlist(Request $request, $item, $type)
    {
        if (!Auth::check()) {
            $msg = 'Please login to manage wishlist.';
            return $request->ajax() ? response()->json(['success' => false, 'message' => $msg], 401) : back()->with('error', $msg);
        }

        $query = Wishlist::where('user_id', Auth::id());
        if ($type === 'product') {
            $query->where('product_id', $item->id);
        } else {
            $query->where('combo_pack_id', $item->id);
        }
        $query->delete();

        $this->updateSessionCounts();
        $wishlistCount = Auth::user()->wishlist()->count() ?? 0;

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Removed from wishlist.',
                'wishlist_count' => $wishlistCount
            ]);
        }

        return back()->with('success', 'Removed from wishlist.');
    }

    /**
     * Show wishlist page
     */
    /**
     * Show wishlist page
     */
    public function wishlist()
    {
        $wishlistItems = Auth::check()
            ? Auth::user()->wishlist()->with(['product', 'comboPack'])->get()
            : collect();

        // Update session counts for consistency
        $this->updateSessionCounts();

        return view('view.wishlist', compact('wishlistItems'));
    }

    /**
     * Helper: Authorize that the cart item belongs to current user/session
     */
    protected function authorizeCartItem(Cart $cartItem)
    {
        $isOwner = Auth::check()
            ? $cartItem->user_id === Auth::id()
            : $cartItem->session_id === session()->getId();

        if (!$isOwner) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Helper: Update session counts for cart and wishlist
     */
    protected function updateSessionCounts()
    {
        $sessionId = session()->getId();
        cache()->forget("cart_count_{$sessionId}");

        if (Auth::check()) {
            cache()->forget("wishlist_count_" . Auth::id());
        }

        $cartCount = Cart::current()->sum('quantity') ?? 0;
        session(['cart_count' => $cartCount]);

        $wishlistCount = Auth::check() ? Auth::user()->wishlist()->count() ?? 0 : 0;
        session(['wishlist_count' => $wishlistCount]);

        view()->share('cartCount', $cartCount);
        view()->share('wishlistCount', $wishlistCount);
    }

    public function addCustomCombo(Request $request)
    {
        $productIds = $request->input('product_ids');
        $productSizes = $request->input('product_sizes', []);

        if (!is_array($productIds) || count($productIds) < 2) {
            return back()->with('error', 'Please select at least 2 products to build a combo.');
        }

        $settings = \App\Models\ComboPackSetting::first();
        $maxProducts = $settings ? $settings->max_products : 5;

        if (count($productIds) > $maxProducts) {
            return back()->with('error', "Maximum {$maxProducts} products are allowed in a Combo Pack.");
        }

        // Map product ID to its submitted size
        $sizesMap = [];
        if (is_array($productSizes)) {
            foreach ($productIds as $index => $id) {
                $sizesMap[$id] = $productSizes[$index] ?? null;
            }
        }

        // Fetch products and verify all are active & eligible
        $products = Product::whereIn('id', $productIds)
            ->where('combo_pack_eligible', 'Yes')
            ->where('is_active', true)
            ->get();

        if ($products->count() !== count($productIds)) {
            return back()->with('error', 'Some selected products are not eligible for a combo pack.');
        }

        // Check size/variant eligibility if product has variants
        foreach ($products as $product) {
            if ($product->has_variants) {
                $selectedSize = $sizesMap[$product->id] ?? null;
                $sizes = $product->size;
                if (is_string($sizes)) {
                    $sizes = json_decode($sizes, true);
                }
                $sizes = is_array($sizes) ? $sizes : [];

                $matchedSizeKey = null;
                if ($selectedSize) {
                    foreach ($sizes as $k => $v) {
                        if (strcasecmp($k, $selectedSize) === 0) {
                            $matchedSizeKey = $k;
                            break;
                        }
                    }
                }

                if (!$matchedSizeKey || ($sizes[$matchedSizeKey]['combo_eligible'] ?? 'No') !== 'Yes') {
                    return back()->with('error', "Product '{$product->name}' with the selected variant is not eligible for combo packs.");
                }
            }
        }

        // Check stock availability
        foreach ($products as $product) {
            if ($product->stock_quantity < 1) {
                return back()->with('error', "Product '{$product->name}' is out of stock.");
            }
        }

        // Delete old items if we are editing an existing combo
        $editComboId = $request->input('edit_combo_id');
        if ($editComboId) {
            $oldCartItems = Cart::current()->get();
            foreach ($oldCartItems as $item) {
                if ($item->custom_combo_id === $editComboId) {
                    $item->delete();
                }
            }
        }

        // Generate a unique identifier for this custom combo pack instance
        $customComboId = 'cc_' . uniqid();

        // Clear temporary carts
        app(TempCartService::class)->clearUserTempCarts(Auth::id(), Auth::check() ? null : session()->getId());

        // Add each product to the cart
        foreach ($products as $product) {
            $optionsData = [
                'custom_combo_id' => $customComboId,
                'combo_products' => $productIds
            ];
            
            $selectedSize = $sizesMap[$product->id] ?? null;
            
            $sizes = $product->size;
            if (is_string($sizes)) {
                $sizes = json_decode($sizes, true);
            }
            
            $matchedSize = null;
            if ($selectedSize && is_array($sizes)) {
                foreach ($sizes as $k => $v) {
                    if (strcasecmp($k, $selectedSize) === 0) {
                        $matchedSize = $k;
                        break;
                    }
                }
            }
            
            if ($matchedSize) {
                $optionsData['size'] = $matchedSize;
            } elseif (is_array($sizes) && count($sizes) > 0) {
                $optionsData['size'] = array_key_first($sizes);
            }

            Cart::create([
                'user_id' => Auth::id(),
                'session_id' => Auth::check() ? null : session()->getId(),
                'product_id' => $product->id,
                'combo_pack_id' => null,
                'quantity' => 1,
                'options' => json_encode($optionsData)
            ]);
        }

        $this->updateSessionCounts();

        return redirect()->route('cart.index')->with('success', 'Custom Combo Pack added to cart successfully!');
    }
}
