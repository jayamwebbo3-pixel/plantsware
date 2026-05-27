<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Blog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Load all products to accurately identify those with zero stock (base or attribute level)
        $all_products = Product::with(['category', 'subcategory'])->get();
        
        // Define 'Out of Stock' as either base quantity being 0 or any attribute variant being 0
        $out_of_stock_list = $all_products->filter(function($product) {
            // Check base level stock
            if (($product->stock_quantity ?? 0) <= 0) return true;
            
            // Parse attribute JSON for any variant-level stockouts
            if ($product->size) {
                $sizeData = is_array($product->size) ? $product->size : json_decode($product->size, true);
                if (is_array($sizeData)) {
                    foreach ($sizeData as $variant) {
                        if (is_array($variant) && isset($variant['stock']) && $variant['stock'] !== '' && (int)$variant['stock'] <= 0) {
                            return true;
                        }
                    }
                }
            }
            return false;
        })->values();

        // Calculate core dashboard metrics
        $stats = [
            'revenue' => Order::where(function($q) {
                $q->where('payment_status', 'success')
                  ->orWhere('status', 'delivered');
            })->sum('total'),
            'total_orders' => Order::count(),
            'total_customers' => User::count(),
            'pending_orders' => Order::whereNotIn('status', ['shipped', 'delivered', 'cancelled', 'returned'])->count(),
            'total_products' => $all_products->count(),
            'out_of_stock_products' => $out_of_stock_list->count(),
        ];

        // Retrieve top selling products (fallback to latest if none sold)
        $top_sold_ids = DB::table('order_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(8)
            ->pluck('product_id');

        if ($top_sold_ids->isEmpty()) {
            $top_products = Product::latest()->take(8)->get();
        } else {
            // Retrieve them keeping the sorted order
            $top_products = Product::whereIn('id', $top_sold_ids)
                ->get()
                ->sortBy(function($product) use ($top_sold_ids) {
                    return array_search($product->id, $top_sold_ids->toArray());
                });
        }

        // Recent blogs (if still needed, otherwise can remove)
        $recent_blogs = Blog::latest()->take(5)->get();

        // Prepare Sales Analytic Graph Data (Last 7 Days)
        $chart_labels = [];
        $chart_data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chart_labels[] = $date->format('d M');
            
            // Sum revenue for that specific day
            $daily_sum = Order::where(function($q) {
                $q->where('payment_status', 'success')
                  ->orWhere('status', 'delivered');
            })->whereDate('created_at', $date)
              ->sum('total');
            $chart_data[] = $daily_sum;
        }

        return view('admin.dashboard', compact('stats', 'top_products', 'recent_blogs', 'chart_labels', 'chart_data', 'out_of_stock_list'));
    }
}
