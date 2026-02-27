<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? ['web'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                // Admin login
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                // User login
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
