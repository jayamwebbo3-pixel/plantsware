<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $admin = auth()->guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admin_user,username,' . $admin->id,
            'current_password' => 'required_with:password|nullable|current_password:admin',
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $admin->name = $validated['name'];
        $admin->username = $validated['username'];

        if (!empty($validated['password'])) {
            $admin->password = $validated['password']; // Will be hashed via setter in AdminUser model
            
            // Logout from other devices
            auth()->guard('admin')->logoutOtherDevices($validated['password']);
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
