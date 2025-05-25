<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect('/auth/login');
        }

        if ($role === 'admin' && auth()->user()->role_id !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        if ($role === 'customer' && auth()->user()->role_id !== 2) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
} 