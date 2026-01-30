<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Shared\Models\AuditLog;

class ComprehensiveAuditMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        // Process the request
        $response = $next($request);

        // Calculate performance metrics
        $executionTime = round((microtime(true) - $startTime) * 1000, 2); // milliseconds
        $memoryUsed = memory_get_usage() - $startMemory;

        // Log the request details
        $this->logRequest($request, $response, $executionTime, $memoryUsed);

        return $response;
    }

    /**
     * Log request details for auditing.
     */
    protected function logRequest(Request $request, $response, float $executionTime, int $memoryUsed)
    {
        try {
            $user = Auth::user();
            $statusCode = $response->getStatusCode();

            // Determine if this should be logged
            if (!$this->shouldLog($request, $statusCode)) {
                return;
            }

            // Prepare log data
            $logData = [
                'user_id' => $user?->id,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'route_name' => $request->route()?->getName(),
                'controller_action' => $this->getControllerAction($request),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status_code' => $statusCode,
                'execution_time_ms' => $executionTime,
                'memory_used_bytes' => $memoryUsed,
                'request_size_bytes' => strlen($request->getContent()),
                'response_size_bytes' => $response->headers->get('content-length', 0),
                'session_id' => session()->getId(),
                'request_id' => $request->header('X-Request-ID', uniqid()),
            ];

            // Add request/response data for important operations
            if ($this->shouldLogData($request, $statusCode)) {
                $logData['request_data'] = $this->sanitizeData($request->all());

                if ($statusCode >= 400) {
                    $logData['response_data'] = $this->getResponseContent($response);
                }
            }

            // Add error details for failed requests
            if ($statusCode >= 400) {
                $logData['error_details'] = $this->getErrorDetails($response);
            }

            // Store in audit log
            AuditLog::create([
                'user_id' => $user?->id,
                'action' => $this->determineAction($request),
                'auditable_type' => $this->getResourceType($request),
                'auditable_id' => $this->getResourceId($request),
                'old_values' => null,
                'new_values' => $logData['request_data'] ?? null,
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'description' => $this->generateDescription($request, $statusCode),
            ]);

            // Log to Laravel's logging system
            $this->logToChannel($logData, $statusCode);

        } catch (\Exception $e) {
            // Don't let audit logging break the application
            Log::error('Audit logging failed', [
                'error' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
        }
    }

    /**
     * Determine if request should be logged.
     */
    protected function shouldLog(Request $request, int $statusCode): bool
    {
        // Always log errors
        if ($statusCode >= 400) {
            return true;
        }

        // Log state-changing operations
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return true;
        }

        // Log important GET requests
        $importantPaths = [
            '/api/admin',
            '/api/payments',
            '/api/brta',
        ];

        foreach ($importantPaths as $path) {
            if (str_contains($request->path(), $path)) {
                return true;
            }
        }

        // Skip health checks and assets
        $skipPaths = [
            '/health',
            '/ping',
            '/metrics',
            '.css',
            '.js',
            '.png',
            '.jpg',
            '.ico',
        ];

        foreach ($skipPaths as $path) {
            if (str_contains($request->path(), $path)) {
                return false;
            }
        }

        return false;
    }

    /**
     * Determine if request/response data should be logged.
     */
    protected function shouldLogData(Request $request, int $statusCode): bool
    {
        // Log data for POST/PUT/PATCH operations
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            return true;
        }

        // Log data for errors
        if ($statusCode >= 400) {
            return true;
        }

        return false;
    }

    /**
     * Sanitize sensitive data from request.
     */
    protected function sanitizeData(array $data): array
    {
        $sensitiveFields = config('parking.audit.sensitive_fields', [
            'password', 'password_confirmation', 'token', 'secret', 'key',
            'pin', 'otp', 'cvv', 'card_number'
        ]);

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[REDACTED]';
            }
        }

        return $data;
    }

    /**
     * Get controller action from request.
     */
    protected function getControllerAction(Request $request): ?string
    {
        $route = $request->route();

        if (!$route) {
            return null;
        }

        $action = $route->getAction();

        if (isset($action['controller'])) {
            return $action['controller'];
        }

        return null;
    }

    /**
     * Determine action type from request.
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
            case 'GET':
                return 'viewed';
            default:
                return strtolower($method);
        }
    }

    /**
     * Get resource type from request.
     */
    protected function getResourceType(Request $request): ?string
    {
        $path = $request->path();

        // Extract resource from API path
        if (preg_match('/api\/(\w+)/', $path, $matches)) {
            return ucfirst($matches[1]);
        }

        return null;
    }

    /**
     * Get resource ID from request.
     */
    protected function getResourceId(Request $request): ?int
    {
        $path = $request->path();

        // Extract ID from path
        if (preg_match('/\/(\d+)(?:\/|$)/', $path, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    /**
     * Generate human-readable description.
     */
    protected function generateDescription(Request $request, int $statusCode): string
    {
        $action = $this->determineAction($request);
        $resource = $this->getResourceType($request) ?? 'resource';

        if ($statusCode >= 400) {
            return "Failed to {$action} {$resource} (HTTP {$statusCode})";
        }

        return "Successfully {$action} {$resource}";
    }

    /**
     * Get response content for logging.
     */
    protected function getResponseContent($response): mixed
    {
        $content = $response->getContent();

        // Try to decode JSON response
        $decoded = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return $content;
    }

    /**
     * Get error details from response.
     */
    protected function getErrorDetails($response): array
    {
        $content = $this->getResponseContent($response);

        if (is_array($content)) {
            return [
                'message' => $content['message'] ?? 'Unknown error',
                'errors' => $content['errors'] ?? null,
                'exception' => $content['exception'] ?? null,
            ];
        }

        return [
            'message' => 'HTTP ' . $response->getStatusCode(),
            'content' => $content,
        ];
    }

    /**
     * Log to specific channel based on status code.
     */
    protected function logToChannel(array $logData, int $statusCode): void
    {
        $level = $this->getLogLevel($statusCode);
        $channel = $this->getLogChannel($statusCode);

        Log::channel($channel)->log($level, 'API Request', $logData);
    }

    /**
     * Get log level based on status code.
     */
    protected function getLogLevel(int $statusCode): string
    {
        if ($statusCode >= 500) {
            return 'error';
        } elseif ($statusCode >= 400) {
            return 'warning';
        } elseif ($statusCode >= 300) {
            return 'info';
        }

        return 'debug';
    }

    /**
     * Get log channel based on status code.
     */
    protected function getLogChannel(int $statusCode): string
    {
        if ($statusCode >= 500) {
            return 'error';
        } elseif ($statusCode >= 400) {
            return 'warning';
        }

        return 'audit';
    }
}
