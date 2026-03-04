<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('view.products', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('view.product', compact('product', 'relatedProducts'));
    }

    public function categories(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        
        $query = Product::where('is_active', true);
        $this->applyFilters($query, $request);
        
        $products = $query->paginate(20)->withQueryString();
        
        return view('view.productcategory', compact('categories', 'products'));
    }

    public function category(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        
        $query = Product::where('category_id', $category->id)->where('is_active', true);
        $this->applyFilters($query, $request);
        
        $products = $query->paginate(20)->withQueryString();
        
        return view('view.productcategory', compact('category', 'categories', 'products'));
    }

    public function subcategory(Request $request, $slug)
    {
        $subcategory = Subcategory::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        
        $query = Product::where('subcategory_id', $subcategory->id)->where('is_active', true);
        $this->applyFilters($query, $request);
        
        $products = $query->paginate(20)->withQueryString();
        
        return view('view.productcategory', compact('subcategory', 'categories', 'products'));
    }

    private function applyFilters($query, Request $request)
    {
        // Price Max Filter
        if ($request->filled('price_max')) {
            $priceMax = $request->price_max;
            $query->where(function($q) use ($priceMax) {
                $q->where(function($sq) use ($priceMax) {
                      $sq->whereNull('sale_price')->orWhere('sale_price', 0);
                  })->where('price', '<=', $priceMax)
                  ->orWhere(function($sq) use ($priceMax) {
                      $sq->whereNotNull('sale_price')->where('sale_price', '>', 0)
                          ->where('sale_price', '<=', $priceMax);
                  });
            });
        }

        // Discount Filter
        if ($request->filled('discount') && $request->discount != 'all') {
            switch ($request->discount) {
                case '50':
                    $query->whereRaw('((price - sale_price) / price * 100) >= 50')->where('sale_price', '>', 0);
                    break;
                case '30-50':
                    $query->whereRaw('((price - sale_price) / price * 100) >= 30')
                          ->whereRaw('((price - sale_price) / price * 100) <= 50')
                          ->where('sale_price', '>', 0);
                    break;
                case '10-30':
                    $query->whereRaw('((price - sale_price) / price * 100) >= 10')
                          ->whereRaw('((price - sale_price) / price * 100) < 30')
                          ->where('sale_price', '>', 0);
                    break;
                case 'below10':
                    $query->whereRaw('((price - sale_price) / price * 100) > 0')
                          ->whereRaw('((price - sale_price) / price * 100) < 10')
                          ->where('sale_price', '>', 0);
                    break;
            }
        }

        // Availability Filter
        if ($request->filled('availability')) {
            $availabilities = is_array($request->availability) ? $request->availability : explode(',', $request->availability);
            $query->where(function($q) use ($availabilities) {
                if (in_array('in-stock', $availabilities)) {
                    $q->orWhere('stock_quantity', '>', 0);
                }
                if (in_array('out-of-stock', $availabilities)) {
                    $q->orWhere('stock_quantity', '<=', 0);
                }
            });
        }

        // Sort By Filter
        if ($request->filled('sort')) {
            $sort = $request->sort;
            if ($sort == 'price-low') {
                $query->orderByRaw('COALESCE(NULLIF(sale_price, 0), price) ASC');
            } elseif ($sort == 'price-high') {
                $query->orderByRaw('COALESCE(NULLIF(sale_price, 0), price) DESC');
            } elseif ($sort == 'name-asc') {
                $query->orderBy('name', 'asc');
            } elseif ($sort == 'name-desc') {
                $query->orderBy('name', 'desc');
            } elseif ($sort == 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort == 'discount') {
                $query->orderByRaw('((price - sale_price) / price * 100) DESC');
            } else {
                // Popularity fallback
                $query->orderBy('sort_order')->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }
    }
}
