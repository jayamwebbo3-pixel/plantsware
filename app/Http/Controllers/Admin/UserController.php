<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 10);
        
        $users = User::when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->appends(request()->query());
            
        return view('admin.users.index', compact('users'));
    }

    public function report(User $user)
    {
        $orders = $user->orders()->with('items')->latest()->get();

        $totalOrders = $orders->count();

        $completedOrders = $orders->filter(function ($order) {
            return in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered', 'completed'])
                && $order->payment_status === 'paid';
        });
        
        $totalCompletedOrders = $completedOrders->count();
        $totalPurchaseValue = $user->total_purchase_value;

        $formattedOrders = $orders->map(function ($order) {
            return [
                'date' => $order->created_at->format('d M Y h:i A'),
                'order_number' => $order->order_number,
                'items_summary' => $order->items->map(function ($item) {
                    return $item->quantity . ' x ' . $item->product_name;
                })->implode(', '),
                'total' => number_format($order->total, 2),
                'status' => ucfirst($order->status),
                'payment_status' => ucfirst($order->payment_status),
                'badge_class' => $order->getStatusBadgeClass()
            ];
        });

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'N/A',
            ],
            'summary' => [
                'total_orders' => $totalOrders,
                'total_completed_orders' => $totalCompletedOrders,
                'total_purchase_value' => number_format($totalPurchaseValue, 2),
            ],
            'orders' => $formattedOrders
        ]);
    }
}
