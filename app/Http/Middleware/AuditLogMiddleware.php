<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Domains\Admin\Models\AuditLog;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log authenticated user actions
        if (Auth::check()) {
            // Skip logging for certain routes to avoid noise
            $skipRoutes = ['admin.logs', 'api.user.profile', 'dashboard.index'];
            $currentRoute = $request->route() ? $request->route()->getName() : null;

            if (!in_array($currentRoute, $skipRoutes) && $this->shouldLog($request)) {
                try {
                    AuditLog::create([
                        'user_id' => Auth::id(),
                        'action' => $this->getAction($request),
                        'model_type' => $this->getModelType($request),
                        'model_id' => $this->getModelId($request),
                        'old_values' => null,
                        'new_values' => $this->getRequestData($request),
                        'url' => $request->fullUrl(),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                } catch (\Exception $e) {
                    // Silently fail to avoid breaking the application
                    \Log::error('Audit log failed: ' . $e->getMessage());
                }
            }
        }

        return $response;
    }

    /**
     * Determine if the request should be logged.
     */
    private function shouldLog(Request $request): bool
    {
        // Only log POST, PUT, PATCH, DELETE requests
        return in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']);
    }

    /**
     * Get the action from the request.
     */
    private function getAction(Request $request): string
    {
        $method = $request->method();
        $route = $request->route() ? $request->route()->getName() : 'unknown';

        return $method . ':' . $route;
    }

    /**
     * Get the model type from the request.
     */
    private function getModelType(Request $request): ?string
    {
        $route = $request->route();

        if (!$route) {
            return null;
        }

        // Try to extract model from route parameters
        foreach ($route->parameters() as $key => $value) {
            if (is_object($value) && method_exists($value, 'getMorphClass')) {
                return $value->getMorphClass();
            }
        }

        return null;
    }

    /**
     * Get the model ID from the request.
     */
    private function getModelId(Request $request): ?int
    {
        $route = $request->route();

        if (!$route) {
            return null;
        }

        // Try to extract model ID from route parameters
        foreach ($route->parameters() as $key => $value) {
            if (is_object($value) && method_exists($value, 'getKey')) {
                return $value->getKey();
            }
        }

        return null;
    }

    /**
     * Get relevant request data.
     */
    private function getRequestData(Request $request): array
    {
        $data = $request->except(['password', 'password_confirmation', '_token', '_method']);

        // Remove sensitive data
        $sensitiveKeys = ['api_key', 'secret', 'token'];
        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '[HIDDEN]';
            }
        }

        return $data;
    }
}
