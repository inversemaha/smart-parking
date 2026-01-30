<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Shared\Services\AuditLogService;
use Symfony\Component\HttpFoundation\Response;

class RoleCheckMiddleware
{
    public function __construct(protected AuditLogService $auditLogService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * This middleware provides flexible role-based access control
     * and logs access attempts for security auditing.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow guest access to public endpoints
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Check if user account is active
        if (!$user->is_active) {
            $this->auditLogService->log(
                'access_denied_inactive',
                'Access denied for inactive user',
                null,
                $user->id,
                [
                    'route' => $request->route()->getName(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->header('User-Agent')
                ]
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('auth.account_deactivated')
                ], 403);
            }

            Auth::logout();
            return redirect()->route('login')
                ->with('error', __('auth.account_deactivated'));
        }

        // Log successful access for audit trail
        $this->auditLogService->log(
            'route_accessed',
            'User accessed route',
            null,
            $user->id,
            [
                'route' => $request->route()->getName(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]
        );

        return $next($request);
    }
}
