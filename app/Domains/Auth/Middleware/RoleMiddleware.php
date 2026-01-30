<?php

namespace App\Domains\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has the required role
        if (!$user->hasRole($role)) {
            abort(403, 'You do not have the required role to access this resource.');
        }

        return $next($request);
    }
}
