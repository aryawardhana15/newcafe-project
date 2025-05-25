<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || $request->user()->role_id != 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Anda tidak memiliki akses ke halaman ini.'
                ], 403);
            }
            return redirect('/')->with('error', 'Unauthorized. Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
} 