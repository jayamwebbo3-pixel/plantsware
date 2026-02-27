<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class UpdateCartCounts
{
    public function handle(Request $request, Closure $next)
    {
        // Only for normal page loads
        if ($request->isMethod('get') && !$request->ajax()) {

            // ---- CART COUNT ----
            if (Auth::check()) {
                $userId = Auth::id();

                $cartCount = cache()->remember(
                    'cart_count_user_' . $userId,
                    300,
                    function () use ($userId) {
                        return Cart::where('user_id', $userId)->sum('quantity');
                    }
                );
            } else {
                // Guest users
                $cartCount = 0;
            }

            // ---- WISHLIST COUNT ----
            if (Auth::check()) {
                $wishlistCount = cache()->remember(
                    'wishlist_count_user_' . Auth::id(),
                    300,
                    fn () => Auth::user()->wishlist()->count()
                );
            } else {
                $wishlistCount = 0;
            }

            // Share to views ONLY (do not write session)
            view()->share('cart_count', $cartCount);
            view()->share('wishlist_count', $wishlistCount);
        }

        return $next($request);
    }
}
