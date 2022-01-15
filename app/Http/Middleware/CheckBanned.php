<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBanned
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->banned_at) {
            auth('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', 'Your Account is suspended, please contact Admin.');
        }

        return $next($request);
    }
}
