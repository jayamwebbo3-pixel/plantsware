<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login page
     */
    public function showLoginForm()
    {
        if (Auth::check() && !empty(Auth::user()->name)) {
            return redirect()->route('user.dashboard');
        }
        return view('view.login');
    }

    /**
     * Send OTP to user email
     */
    public function sendOtp(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $otp = rand(100000, 999999);
        $user = \App\Models\User::where('email', $request->email)->first();
        $isNew = !$user;

        if (!$user) {
            $user = \App\Models\User::create([
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16))
            ]);
        }

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($otp));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP. Please try again.');
        }

        return back()->with([
            'success' => $isNew ? 'OTP sent to your email.' : 'A new OTP has been sent to your email.',
            'step' => 'otp',
            'email' => $request->email,
            'is_new_user' => $isNew
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $user = \App\Models\User::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->with([
                'error' => 'Invalid or expired OTP.',
                'step' => 'otp',
                'email' => $request->email
            ]);
        }

        $isNewUser = $request->input('is_new_user') === '1';

        // Clear OTP after successful verification
        $user->update([
            'otp' => null,
            'otp_expires_at' => null
        ]);

        Auth::login($user, true);
        request()->session()->regenerate();

        if ($isNewUser || empty($user->name)) {
            return back()->with([
                'success' => 'OTP verified. Please set your name.',
                'step' => 'set-name'
            ]);
        }

        return redirect()->route('user.dashboard');
    }

    /**
     * Set user name
     */
    public function setName(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $user = Auth::user();
        $user->update(['name' => $request->name]);

        return redirect()->route('user.dashboard')->with('success', 'Profile updated successfully.');
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
