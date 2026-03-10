<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ComboPack;
use App\Models\Category;
use App\Models\Product;
use App\Models\ComboOnlyProduct;
use Illuminate\Http\Request;

class ComboPackController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $query = ComboPack::with(['comboProduct'])->where('is_active', true);

        $this->applyFilters($query, $request);

        $comboPacks = $query->paginate(12)->withQueryString();

        return view('view.combo_packs', compact('comboPacks', 'categories'));
    }

    public function show($slug)
    {
        $comboPack = ComboPack::with(['comboProduct'])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Build a list of [slug, url] for each constituent product (standard combo)
        $productLinks = [];
        if ($comboPack->comboProduct && $comboPack->comboProduct->product_ids) {
            foreach ($comboPack->comboProduct->product_ids as $pid) {
                if (str_starts_with($pid, 'p_')) {
                    $realId = str_replace('p_', '', $pid);
                    $product = Product::select('id', 'slug')->find($realId);
                    $productLinks[] = $product ? route('product.show', $product->slug) : null;
                } elseif (str_starts_with($pid, 'co_')) {
                    $realId = str_replace('co_', '', $pid);
                    $coProduct = ComboOnlyProduct::select('id', 'slug')->find($realId);
                    // Combo only products might not have their own frontend detail page?
                    // For now we treat them as part of the combo description.
                    $productLinks[] = null;
                } else {
                    $productLinks[] = null; // nested combos don't have a product page
                }
            }
        }

        return view('view.combo_pack_details', compact('comboPack', 'productLinks'));
    }

    private function applyFilters($query, Request $request)
    {
        // Category Filter
        if ($request->filled('category')) {
            $catId = $request->category;
            $query->where(function ($q) use ($catId) {
                $q->whereJsonContains('category_id', (string) $catId)
                    ->orWhereJsonContains('category_id', (int) $catId)
                    ->orWhere('category_id', $catId)
                    ->orWhere('category_id', 'LIKE', '%"' . $catId . '"%');
            });
        }

        // Price Max Filter
        if ($request->filled('price_max')) {
            $priceMax = $request->price_max;
            $query->where('offer_price', '<=', $priceMax);
        }

        // Discount Filter
        if ($request->filled('discount') && $request->discount != 'all') {
            switch ($request->discount) {
                case '50':
                    $query->whereRaw('total_price > 0 AND ((total_price - offer_price) / total_price * 100) >= 50');
                    break;
                case '30-50':
                    $query->whereRaw('total_price > 0 AND ((total_price - offer_price) / total_price * 100) >= 30')
                        ->whereRaw('total_price > 0 AND ((total_price - offer_price) / total_price * 100) <= 50');
                    break;
                case '10-30':
                    $query->whereRaw('total_price > 0 AND ((total_price - offer_price) / total_price * 100) >= 10')
                        ->whereRaw('total_price > 0 AND ((total_price - offer_price) / total_price * 100) < 30');
                    break;
                case 'below10':
                    $query->whereRaw('total_price > 0 AND ((total_price - offer_price) / total_price * 100) > 0')
                        ->whereRaw('total_price > 0 AND ((total_price - offer_price) / total_price * 100) < 10');
                    break;
            }
        }

        // Availability Filter
        if ($request->filled('availability')) {
            $availabilities = is_array($request->availability) ? $request->availability : explode(',', $request->availability);

            // Optimization & Fix: We must clone the query to respect previous filters 
            // (Category, Price, Discount) before evaluating the dynamic stock accessor.
            $baseQuery = clone $query;
            $matchedIds = $baseQuery->get()->filter(function ($combo) use ($availabilities) {
                $stock = $combo->stock_quantity; // Accesses dynamic calculations
                if (in_array('in-stock', $availabilities) && in_array('out-of-stock', $availabilities)) {
                    return true;
                }
                if (in_array('in-stock', $availabilities)) {
                    return $stock > 0;
                }
                if (in_array('out-of-stock', $availabilities)) {
                    return $stock <= 0;
                }
                return true;
            })->pluck('id');

            $query->whereIn('id', $matchedIds);
        }

        // Sort By Filter
        if ($request->filled('sort')) {
            $sort = $request->sort;
            if ($sort == 'price-low') {
                $query->orderBy('offer_price', 'asc');
            } elseif ($sort == 'price-high') {
                $query->orderBy('offer_price', 'desc');
            } elseif ($sort == 'name-asc') {
                $query->orderBy('name', 'asc');
            } elseif ($sort == 'name-desc') {
                $query->orderBy('name', 'desc');
            } elseif ($sort == 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort == 'discount') {
                $query->orderByRaw('((total_price - offer_price) / total_price * 100) DESC');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
    }
}
