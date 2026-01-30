<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $type = 'general'): Response
    {
        $key = $this->getRateLimitKey($request, $type);
        $limits = $this->getLimits($type);

        if (RateLimiter::tooManyAttempts($key, $limits['attempts'])) {
            $seconds = RateLimiter::availableIn($key);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Too Many Requests',
                    'message' => "Too many {$type} attempts. Please try again in {$seconds} seconds.",
                    'retry_after' => $seconds
                ], 429);
            }

            abort(429, "Too many {$type} attempts. Please try again in {$seconds} seconds.");
        }

        RateLimiter::hit($key, $limits['decay']);

        $response = $next($request);

        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $limits['attempts']);
        $response->headers->set('X-RateLimit-Remaining', max(0, $limits['attempts'] - RateLimiter::attempts($key)));
        $response->headers->set('X-RateLimit-Reset', now()->addSeconds($limits['decay'])->timestamp);

        return $response;
    }

    /**
     * Get rate limit key.
     */
    private function getRateLimitKey(Request $request, string $type): string
    {
        $ip = $request->ip();
        $userId = auth()->id() ?? 'guest';

        return "rate_limit:{$type}:{$ip}:{$userId}";
    }

    /**
     * Get rate limits for different types.
     */
    private function getLimits(string $type): array
    {
        // Get limits from config first, then fallback to defaults
        $configLimits = config('parking.rate_limits.' . $type);

        if ($configLimits) {
            return $configLimits;
        }

        // Default limits
        $limits = [
            'general' => ['attempts' => 100, 'decay' => 60], // 100 per minute
            'auth' => ['attempts' => 5, 'decay' => 300], // 5 per 5 minutes
            'api' => ['attempts' => 60, 'decay' => 60], // 60 per minute
            'booking' => ['attempts' => 10, 'decay' => 60], // 10 per minute
            'payment' => ['attempts' => 3, 'decay' => 600], // 3 per 10 minutes
            'brta' => ['attempts' => 10, 'decay' => 3600], // 10 per hour
            'gate' => ['attempts' => 20, 'decay' => 60], // 20 per minute
        ];

        return $limits[$type] ?? $limits['general'];
    }
}
