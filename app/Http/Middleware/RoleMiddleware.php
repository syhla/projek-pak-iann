<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle request dan cek role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (Auth::check() && Auth::user()->hasRole($role)) {
            return $next($request);
        }

        // Optional: bisa abort 403 biar lebih aman
        abort(403, 'Unauthorized');
    }
}
