<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    public function index()
    {
        $products   = Product::where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(20);
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
            ->take(10)->get();
        return view('view.product', compact('product', 'relatedProducts'));
    }

    public function categories(Request $request)
    {
        $categories   = Category::where('is_active', true)->orderBy('sort_order')->get();
        $baseQuery    = Product::where('is_active', true);
        $filterCounts = $this->buildFilterCounts(clone $baseQuery);

        $query = Product::where('is_active', true);
        $this->applyFilters($query, $request);
        $products = $query->paginate(20)->withQueryString();

        return view('view.productcategory', compact('categories', 'products', 'filterCounts'));
    }

    public function category(Request $request, $slug)
    {
        $category   = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $baseQuery    = Product::where('category_id', $category->id)->where('is_active', true);
        $filterCounts = $this->buildFilterCounts(clone $baseQuery);

        $query = Product::where('category_id', $category->id)->where('is_active', true);
        $this->applyFilters($query, $request);
        $products = $query->paginate(20)->withQueryString();

        return view('view.productcategory', compact('category', 'categories', 'products', 'filterCounts'));
    }

    public function subcategory(Request $request, $slug)
    {
        $subcategory = Subcategory::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $categories  = Category::where('is_active', true)->orderBy('sort_order')->get();

        $baseQuery    = Product::where('subcategory_id', $subcategory->id)->where('is_active', true);
        $filterCounts = $this->buildFilterCounts(clone $baseQuery);

        $query = Product::where('subcategory_id', $subcategory->id)->where('is_active', true);
        $this->applyFilters($query, $request);
        $products = $query->paginate(20)->withQueryString();

        return view('view.productcategory', compact('subcategory', 'categories', 'products', 'filterCounts'));
    }

    /* ------------------------------------------------------------------ */
    /*  Build dynamic counts for each filter option (scoped to base query)  */
    /* ------------------------------------------------------------------ */
    private function buildFilterCounts(Builder $base): array
    {
        $ep = "COALESCE(NULLIF(sale_price, 0), price)"; // effective price expression

        return [
            'price' => [
                'under500'   => (clone $base)->whereRaw("$ep < 500")->count(),
                '500to1000'  => (clone $base)->whereRaw("$ep >= 500 AND $ep < 1000")->count(),
                '1000to3000' => (clone $base)->whereRaw("$ep >= 1000 AND $ep < 3000")->count(),
                '3000to5000' => (clone $base)->whereRaw("$ep >= 3000 AND $ep < 5000")->count(),
                'above5000'  => (clone $base)->whereRaw("$ep >= 5000")->count(),
            ],
            'discount' => [
                'all'     => (clone $base)->count(),
                '50'      => (clone $base)->where('sale_price', '>', 0)->whereRaw('((price - sale_price) / price * 100) >= 50')->count(),
                '30-50'   => (clone $base)->where('sale_price', '>', 0)->whereRaw('((price - sale_price) / price * 100) >= 30')->whereRaw('((price - sale_price) / price * 100) < 50')->count(),
                '10-30'   => (clone $base)->where('sale_price', '>', 0)->whereRaw('((price - sale_price) / price * 100) >= 10')->whereRaw('((price - sale_price) / price * 100) < 30')->count(),
                'below10' => (clone $base)->where('sale_price', '>', 0)->whereRaw('((price - sale_price) / price * 100) > 0')->whereRaw('((price - sale_price) / price * 100) < 10')->count(),
            ],
            'availability' => [
                'in-stock'     => (clone $base)->where('stock_quantity', '>', 0)->count(),
                'out-of-stock' => (clone $base)->where('stock_quantity', '<=', 0)->count(),
            ],
            'shape' => [
                'Circular'    => (clone $base)->where('shape', 'Circular')->count(),
                'Rectangular' => (clone $base)->where('shape', 'Rectangular')->count(),
            ],
            'material' => [
                'HDPE'      => (clone $base)->where('material', 'HDPE')->count(),
                'Fabric'    => (clone $base)->where('material', 'Fabric')->count(),
                'Non-woven' => (clone $base)->where('material', 'Non-woven')->count(),
            ],
            'weight' => [
                'under0.5' => (clone $base)->whereNotNull('weight')->where('weight', '<', 0.5)->count(),
                '0.5to1'   => (clone $base)->whereNotNull('weight')->whereBetween('weight', [0.5, 1])->count(),
                '1to3'     => (clone $base)->whereNotNull('weight')->whereBetween('weight', [1.001, 3])->count(),
                'above3'   => (clone $base)->whereNotNull('weight')->where('weight', '>', 3)->count(),
            ],
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Apply selected filters to an existing query                         */
    /* ------------------------------------------------------------------ */
    private function applyFilters($query, Request $request): void
    {
        $ep = "COALESCE(NULLIF(sale_price, 0), price)";

        // ── Price range (radio bands) ──────────────────────────────────
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'under500':   $query->whereRaw("$ep < 500");                              break;
                case '500to1000':  $query->whereRaw("$ep >= 500 AND $ep < 1000");              break;
                case '1000to3000': $query->whereRaw("$ep >= 1000 AND $ep < 3000");             break;
                case '3000to5000': $query->whereRaw("$ep >= 3000 AND $ep < 5000");             break;
                case 'above5000':  $query->whereRaw("$ep >= 5000");                            break;
            }
        } elseif ($request->filled('price_max')) {
            $query->whereRaw("$ep <= ?", [(float) $request->price_max]);
        }

        // ── Discount ──────────────────────────────────────────────────
        if ($request->filled('discount') && $request->discount !== 'all') {
            switch ($request->discount) {
                case '50':
                    $query->where('sale_price', '>', 0)->whereRaw('((price - sale_price) / price * 100) >= 50'); break;
                case '30-50':
                    $query->where('sale_price', '>', 0)
                          ->whereRaw('((price - sale_price) / price * 100) >= 30')
                          ->whereRaw('((price - sale_price) / price * 100) < 50'); break;
                case '10-30':
                    $query->where('sale_price', '>', 0)
                          ->whereRaw('((price - sale_price) / price * 100) >= 10')
                          ->whereRaw('((price - sale_price) / price * 100) < 30'); break;
                case 'below10':
                    $query->where('sale_price', '>', 0)
                          ->whereRaw('((price - sale_price) / price * 100) > 0')
                          ->whereRaw('((price - sale_price) / price * 100) < 10'); break;
            }
        }

        // ── Availability ──────────────────────────────────────────────
        if ($request->filled('availability')) {
            $avail = (array) $request->availability;
            $query->where(function ($q) use ($avail) {
                if (in_array('in-stock',     $avail)) $q->orWhere('stock_quantity', '>', 0);
                if (in_array('out-of-stock', $avail)) $q->orWhere('stock_quantity', '<=', 0);
            });
        }

        // ── Shape ──────────────────────────────────────────────────────
        if ($request->filled('shape')) {
            $query->where('shape', $request->shape);
        }

        // ── Material ──────────────────────────────────────────────────
        if ($request->filled('material')) {
            $query->whereIn('material', (array) $request->material);
        }

        // ── Weight range ──────────────────────────────────────────────
        if ($request->filled('weight_range')) {
            switch ($request->weight_range) {
                case 'under0.5': $query->whereNotNull('weight')->where('weight', '<', 0.5);          break;
                case '0.5to1':   $query->whereNotNull('weight')->whereBetween('weight', [0.5, 1]);    break;
                case '1to3':     $query->whereNotNull('weight')->whereBetween('weight', [1.001, 3]);  break;
                case 'above3':   $query->whereNotNull('weight')->where('weight', '>', 3);             break;
            }
        }

        // ── Sort ──────────────────────────────────────────────────────
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price-low':  $query->orderByRaw("$ep ASC");                                            break;
                case 'price-high': $query->orderByRaw("$ep DESC");                                           break;
                case 'name-asc':   $query->orderBy('name', 'asc');                                           break;
                case 'name-desc':  $query->orderBy('name', 'desc');                                          break;
                case 'newest':     $query->orderBy('created_at', 'desc');                                    break;
                case 'discount':   $query->orderByRaw('((price - COALESCE(NULLIF(sale_price,0), price)) / price * 100) DESC'); break;
                default:           $query->orderBy('sort_order')->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }
    }
}
