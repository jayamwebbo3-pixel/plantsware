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
}

