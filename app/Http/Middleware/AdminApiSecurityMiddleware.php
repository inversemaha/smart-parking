<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Domains\Admin\Models\AuditLog;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

/**
 * AdminApiSecurityMiddleware
 *
 * Enhanced security middleware specifically for admin API endpoints.
 * Includes rate limiting, IP whitelisting, session validation, and audit logging.
 */
class AdminApiSecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $action = 'general'): Response
    {
        // Check if user has admin role
        if (!$request->user() || !$request->user()->hasRole('admin')) {
            return $this->unauthorizedResponse($request, 'Insufficient privileges');
        }

        // Apply rate limiting
        if ($this->isRateLimited($request, $action)) {
            return $this->rateLimitResponse($request, $action);
        }

        // Check IP whitelist (if configured)
        if (!$this->isIpAllowed($request)) {
            $this->logSecurityEvent($request, 'ip_blocked', [
                'ip' => $request->ip(),
                'action' => $action
            ]);
            return $this->forbiddenResponse($request, 'IP address not allowed');
        }

        // Validate session and check for concurrent sessions
        if (!$this->isSessionValid($request)) {
            return $this->unauthorizedResponse($request, 'Invalid session');
        }

        // Check for suspicious activity
        if ($this->isSuspiciousActivity($request, $action)) {
            $this->logSecurityEvent($request, 'suspicious_activity', [
                'action' => $action,
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);
            return $this->forbiddenResponse($request, 'Suspicious activity detected');
        }

        // Log admin API access
        $this->logApiAccess($request, $action);

        // Process the request
        $response = $next($request);

        // Log response if it's an error
        if ($response->status() >= 400) {
            $this->logSecurityEvent($request, 'api_error', [
                'action' => $action,
                'status_code' => $response->status(),
                'response_size' => strlen($response->getContent())
            ]);
        }

        return $response;
    }

    /**
     * Check if request is rate limited.
     */
    protected function isRateLimited(Request $request, string $action): bool
    {
        $key = $this->getRateLimitKey($request, $action);
        $limits = $this->getRateLimits($action);

        return RateLimiter::tooManyAttempts($key, $limits['attempts']);
    }

    /**
     * Get rate limit key for the request.
     */
    protected function getRateLimitKey(Request $request, string $action): string
    {
        $user = $request->user();
        return "admin_api:{$action}:{$user->id}:{$request->ip()}";
    }

    /**
     * Get rate limits based on action type.
     */
    protected function getRateLimits(string $action): array
    {
        $limits = [
            'general' => ['attempts' => 100, 'decay' => 60], // 100 per minute
            'dashboard' => ['attempts' => 200, 'decay' => 60], // 200 per minute
            'reports' => ['attempts' => 30, 'decay' => 60], // 30 per minute (resource intensive)
            'users' => ['attempts' => 50, 'decay' => 60], // 50 per minute
            'vehicles' => ['attempts' => 50, 'decay' => 60], // 50 per minute
            'system' => ['attempts' => 20, 'decay' => 60], // 20 per minute (critical operations)
            'emergency' => ['attempts' => 5, 'decay' => 300], // 5 per 5 minutes (emergency operations)
            'cache' => ['attempts' => 10, 'decay' => 300], // 10 per 5 minutes
            'export' => ['attempts' => 10, 'decay' => 300], // 10 per 5 minutes
        ];

        return $limits[$action] ?? $limits['general'];
    }

    /**
     * Check if IP is allowed (if whitelist is configured).
     */
    protected function isIpAllowed(Request $request): bool
    {
        $whitelist = config('security.admin_ip_whitelist', []);

        // If no whitelist configured, allow all
        if (empty($whitelist)) {
            return true;
        }

        $clientIp = $request->ip();

        foreach ($whitelist as $allowedIp) {
            if ($this->ipMatches($clientIp, $allowedIp)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP matches pattern (supports CIDR notation).
     */
    protected function ipMatches(string $clientIp, string $allowedIp): bool
    {
        // Exact match
        if ($clientIp === $allowedIp) {
            return true;
        }

        // CIDR notation
        if (strpos($allowedIp, '/') !== false) {
            return $this->ipInCidr($clientIp, $allowedIp);
        }

        return false;
    }

    /**
     * Check if IP is in CIDR range.
     */
    protected function ipInCidr(string $ip, string $cidr): bool
    {
        list($subnet, $mask) = explode('/', $cidr);

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = -1 << (32 - (int)$mask);

        return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
    }

    /**
     * Validate session and check for anomalies.
     */
    protected function isSessionValid(Request $request): bool
    {
        $user = $request->user();

        // Check if user is suspended
        if ($user->status === 'suspended') {
            return false;
        }

        // Check for concurrent sessions (if enabled)
        if (config('security.prevent_concurrent_admin_sessions', true)) {
            $activeSessions = $user->userSessions()
                ->where('is_active', true)
                ->where('id', '!=', $request->session()->getId())
                ->count();

            if ($activeSessions > 0) {
                return false;
            }
        }

        // Check session age
        $maxAge = config('security.admin_session_lifetime', 3600); // 1 hour default
        $sessionAge = time() - $request->session()->get('login_time', time());

        if ($sessionAge > $maxAge) {
            return false;
        }

        return true;
    }

    /**
     * Check for suspicious activity patterns.
     */
    protected function isSuspiciousActivity(Request $request, string $action): bool
    {
        $user = $request->user();
        $now = Carbon::now();

        // Check for rapid sequential requests from different IPs
        $recentLogs = AuditLog::where('user_id', $user->id)
            ->where('created_at', '>', $now->subMinutes(5))
            ->distinct('ip_address')
            ->count();

        if ($recentLogs > 3) { // More than 3 different IPs in 5 minutes
            return true;
        }

        // Check for unusual user agent changes
        $lastUserAgent = AuditLog::where('user_id', $user->id)
            ->whereNotNull('user_agent')
            ->orderBy('created_at', 'desc')
            ->value('user_agent');

        if ($lastUserAgent && $lastUserAgent !== $request->userAgent()) {
            // User agent changed - potentially suspicious
            $userAgentChanges = AuditLog::where('user_id', $user->id)
                ->where('created_at', '>', $now->subHour())
                ->distinct('user_agent')
                ->count();

            if ($userAgentChanges > 2) { // More than 2 user agent changes in an hour
                return true;
            }
        }

        // Check for unusual activity patterns (e.g., admin accessing system at odd hours)
        $hour = $now->hour;
        if (config('security.admin_time_restrictions') && ($hour < 6 || $hour > 22)) {
            return true;
        }

        return false;
    }

    /**
     * Log API access for audit trail.
     */
    protected function logApiAccess(Request $request, string $action): void
    {
        try {
            AuditLog::createLog(
                action: 'api_access',
                resourceType: 'admin_api',
                additionalData: [
                    'endpoint' => $request->path(),
                    'method' => $request->method(),
                    'action_type' => $action,
                    'query_parameters' => $request->query(),
                    'content_length' => $request->header('Content-Length', 0)
                ]
            );
        } catch (\Exception $e) {
            // Don't let audit logging break the request
            logger()->error('Failed to log admin API access', [
                'error' => $e->getMessage(),
                'endpoint' => $request->path()
            ]);
        }
    }

    /**
     * Log security events.
     */
    protected function logSecurityEvent(Request $request, string $event, array $data = []): void
    {
        try {
            AuditLog::createLog(
                action: 'security_event',
                resourceType: 'admin_security',
                additionalData: array_merge([
                    'event_type' => $event,
                    'endpoint' => $request->path(),
                    'method' => $request->method(),
                    'timestamp' => now()->toISOString()
                ], $data)
            );
        } catch (\Exception $e) {
            logger()->critical('Failed to log security event', [
                'error' => $e->getMessage(),
                'event' => $event,
                'endpoint' => $request->path()
            ]);
        }
    }

    /**
     * Return rate limit exceeded response.
     */
    protected function rateLimitResponse(Request $request, string $action): Response
    {
        $key = $this->getRateLimitKey($request, $action);
        $seconds = RateLimiter::availableIn($key);

        $this->logSecurityEvent($request, 'rate_limit_exceeded', [
            'action' => $action,
            'retry_after' => $seconds
        ]);

        return response()->json([
            'success' => false,
            'error' => 'Rate limit exceeded',
            'message' => "Too many {$action} requests. Please try again in {$seconds} seconds.",
            'retry_after' => $seconds,
            'error_code' => 'RATE_LIMIT_EXCEEDED'
        ], 429, [
            'X-RateLimit-Limit' => $this->getRateLimits($action)['attempts'],
            'X-RateLimit-Remaining' => 0,
            'X-RateLimit-Reset' => time() + $seconds,
            'Retry-After' => $seconds
        ]);
    }

    /**
     * Return unauthorized response.
     */
    protected function unauthorizedResponse(Request $request, string $reason): Response
    {
        $this->logSecurityEvent($request, 'unauthorized_access', [
            'reason' => $reason
        ]);

        return response()->json([
            'success' => false,
            'error' => 'Unauthorized',
            'message' => 'Access denied: ' . $reason,
            'error_code' => 'UNAUTHORIZED'
        ], 401);
    }

    /**
     * Return forbidden response.
     */
    protected function forbiddenResponse(Request $request, string $reason): Response
    {
        return response()->json([
            'success' => false,
            'error' => 'Forbidden',
            'message' => 'Access denied: ' . $reason,
            'error_code' => 'FORBIDDEN'
        ], 403);
    }
}
