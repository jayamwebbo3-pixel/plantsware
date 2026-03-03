<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default 10 to match your screenshot
        $search = $request->get('search');
        $status = $request->get('status'); // e.g., pending, processing, etc.

        // Base query with user and items count
        $query = Order::query()
            ->with('user')
            ->withCount('items');

        // Search: order number, customer name, email, phone, address
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereJsonContains('shipping_address->phone', $search)
                  ->orWhereJsonContains('shipping_address->address', "%{$search}%")
                  ->orWhereJsonContains('shipping_address->city', "%{$search}%");
            });
        }

        // Filter by status
        if ($status && in_array($status, ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'returned', 'return_requested', 'return_rejected', 'completed'])) {
            $query->where('status', $status);
        }

        // Sort by latest
        $orders = $query->orderBy('created_at', 'desc')
                        ->paginate($perPage)
                        ->appends($request->query());

        // Stats for Top Bar
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'returned' => Order::where('status', 'returned')->count(),
            'return_requested' => Order::where('status', 'return_requested')->count(),
            'return_rejected' => Order::where('status', 'return_rejected')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,returned,return_requested,return_rejected,completed',
            'return_rejection_reason' => 'nullable|string'
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'delivered' && !$order->delivered_at) {
            $data['delivered_at'] = now();
        }

        if ($request->status === 'return_rejected') {
            $data['return_rejection_reason'] = $request->input('return_rejection_reason', 'Rejected by admin');
        }

        $order->update($data);

        return back()->with('success', 'Order status updated successfully.');
    }

    // Optional: Update Payment Status (for "Change Payment Status" button)
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Payment status updated successfully.');
    }
}