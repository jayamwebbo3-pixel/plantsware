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
        // Calculate core ecommerce metrics
        $stats = [
            'revenue' => Order::where(function($q) {
                $q->where('payment_status', 'success')
                  ->orWhere('status', 'delivered');
            })->sum('total'),
            'total_orders' => Order::count(),
            'total_customers' => User::count(),
            'pending_orders' => Order::whereNotIn('status', ['shipped', 'delivered', 'cancelled', 'returned'])->count(),
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

        return view('admin.dashboard', compact('stats', 'top_products', 'recent_blogs', 'chart_labels', 'chart_data'));
    }
}
