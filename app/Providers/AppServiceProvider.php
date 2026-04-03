<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // ✅ ADD THIS
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        
        Paginator::useBootstrapFive();

        // Share Header/Footer data with all views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $headerFooter = \App\Models\HeaderFooter::first();
            $categories = \App\Models\Category::active()->ordered()
                ->with(['subcategories' => function($query) {
                    $query->with(['products' => function($pQuery) {
                        $pQuery->where('is_active', true)->limit(12); 
                    }]);
                }])->get();
            
            $view->with('headerFooter', $headerFooter);
            $view->with('headerCategories', $categories);

            // Dynamically calculate counts to avoid stale session bugs
            $view->with('cartCount', \App\Models\Cart::current()->sum('quantity') ?? 0);
            $view->with('wishlistCount', \Illuminate\Support\Facades\Auth::check() 
                ? \Illuminate\Support\Facades\Auth::user()->wishlist()->count() ?? 0 
                : 0);
        });
    }
}
