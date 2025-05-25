<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role_id === 1) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'Unauthorized access');
    }
} 