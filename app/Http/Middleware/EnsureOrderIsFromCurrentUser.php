<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnsureOrderIsFromCurrentUser
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (auth()->user()->id == $request->route('order')->user_id) {
            return $next($request);
        }

        return redirect(route('orders.index'));
    }
}
