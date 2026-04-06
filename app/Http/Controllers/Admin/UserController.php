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
}
