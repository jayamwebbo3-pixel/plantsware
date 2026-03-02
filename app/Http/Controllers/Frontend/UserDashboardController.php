<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('view.userdashboard', [
            'user'      => $user,
            'addresses' => $user->addresses,
            'orders'    => $user->orders()->latest()->get(),
            'wishlist'  => $user->wishlist()->with('product')->get(),
        ]);
    }

    public function cancelOrder($id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        if (in_array($order->status, ['shipped', 'delivered', 'cancelled'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Order has been successfully cancelled.');
    }
}

