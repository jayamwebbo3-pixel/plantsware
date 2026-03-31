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
        $subtotal = $cartItems->sum(function ($item) {
            return $item->calculated_price * $item->quantity;
        });

        $discount = $cartItems->sum(function ($item) {
            $p = $item->combo_pack_id ? $item->comboPack : $item->product;
            $regularPrice = $item->combo_pack_id ? $p->total_price : $p->price;
            $savingsPerItem = max(0, $regularPrice - $item->calculated_price);
            return $savingsPerItem * $item->quantity;
        });

        $totalWeight = $cartItems->sum(function ($item) {
            $p = $item->combo_pack_id ? $item->comboPack : $item->product;
            return ($p->weight ?? 0) * $item->quantity;
        });

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
            $tax = ($subtotal * $taxPercentage) / 100;
        }

        $total = $subtotal + $shipping + $tax;

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
            $canAdd = $stock - $currentQuantity;
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
        $cartItem->delete();

        $this->updateSessionCounts();

        $cartCount = Cart::current()->sum('quantity') ?? 0;
        $cartItems = Cart::current()->with(['product', 'comboPack'])->get();
        $totals = $this->calculateCartTotals($cartItems);

        if ($request->ajax()) {
            return response()->json(array_merge([
                'success' => true,
                'message' => 'Item removed from cart.',
                'cart_count' => $cartCount,
                'item_id' => $itemId
            ], $totals));
        }

        return back()->with('success', 'Item removed from cart.');
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
}