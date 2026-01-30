<?php

namespace App\Shared\Middleware;

use App\Domains\Admin\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log the action after successful request
        if ($response->getStatusCode() < 400) {
            $this->logAuditAction($request, $response);
        }

        return $response;
    }

    /**
     * Log audit action.
     */
    protected function logAuditAction(Request $request, $response)
    {
        try {
            $user = Auth::user();

            // Skip if no user or it's a non-auditable request
            if (!$user || !$this->shouldLog($request)) {
                return;
            }

            // Determine the action
            $action = $this->determineAction($request);

            // Get the affected resource
            $resource = $this->getResourceInfo($request);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'auditable_type' => $resource['type'] ?? null,
                'auditable_id' => $resource['id'] ?? null,
                'old_values' => $this->getOldValues($request),
                'new_values' => $this->getNewValues($request),
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'description' => $this->generateDescription($action, $resource['type'] ?? 'resource'),
            ]);
        } catch (\Exception $e) {
            // Don't let audit logging break the application
            Log::error('Audit logging failed', [
                'error' => $e->getMessage(),
                'url' => $request->fullUrl(),
            ]);
        }
    }

    /**
     * Determine if request should be logged.
     */
    protected function shouldLog(Request $request): bool
    {
        $method = $request->method();
        $path = $request->path();

        // Only log state-changing operations
        if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return false;
        }

        // Skip certain paths
        $skipPaths = [
            'api/auth/logout',
            'api/health',
            'api/ping',
        ];

        foreach ($skipPaths as $skipPath) {
            if (str_contains($path, $skipPath)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine the action from the request.
     */
    protected function determineAction(Request $request): string
    {
        $method = $request->method();

        switch ($method) {
            case 'POST':
                return 'created';
            case 'PUT':
            case 'PATCH':
                return 'updated';
            case 'DELETE':
                return 'deleted';
            default:
                return 'accessed';
        }
    }

    /**
     * Get resource information from the request.
     */
    protected function getResourceInfo(Request $request): array
    {
        $path = $request->path();

        // Try to extract resource type and ID from the path
        if (preg_match('/api\/(\w+)\/(\d+)/', $path, $matches)) {
            return [
                'type' => ucfirst($matches[1]),
                'id' => (int) $matches[2],
            ];
        }

        if (preg_match('/api\/(\w+)/', $path, $matches)) {
            return [
                'type' => ucfirst($matches[1]),
                'id' => null,
            ];
        }

        return [];
    }

    /**
     * Get old values for update operations.
     */
    protected function getOldValues(Request $request): ?array
    {
        // This could be enhanced to capture old values from models
        // For now, we'll return null since it requires model state tracking
        return null;
    }

    /**
     * Get new values from the request.
     */
    protected function getNewValues(Request $request): ?array
    {
        $data = $request->all();

        // Remove sensitive fields
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'secret'];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[REDACTED]';
            }
        }

        return !empty($data) ? $data : null;
    }

    /**
     * Generate a human-readable description.
     */
    protected function generateDescription(string $action, string $resource): string
    {
        return "User {$action} {$resource}";
    }
}
