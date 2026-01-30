<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ApiSecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // API Key validation for external integrations
        if ($this->requiresApiKey($request)) {
            if (!$this->validateApiKey($request)) {
                return $this->unauthorized('Invalid or missing API key');
            }
        }

        // IP Whitelist check for admin endpoints
        if ($this->isAdminEndpoint($request)) {
            if (!$this->isWhitelistedIP($request)) {
                Log::warning('Unauthorized admin access attempt', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                ]);

                return $this->forbidden('Access denied from this IP address');
            }
        }

        // Request signature validation for payment callbacks
        if ($this->isPaymentCallback($request)) {
            if (!$this->validatePaymentSignature($request)) {
                Log::error('Invalid payment callback signature', [
                    'ip' => $request->ip(),
                    'headers' => $request->headers->all(),
                    'body' => $request->getContent(),
                ]);

                return $this->unauthorized('Invalid signature');
            }
        }

        // Basic security headers
        $response = $next($request);

        return $this->addSecurityHeaders($response);
    }

    /**
     * Check if request requires API key.
     */
    private function requiresApiKey(Request $request): bool
    {
        $apiKeyRoutes = [
            '/api/brta/',
            '/api/external/',
        ];

        foreach ($apiKeyRoutes as $route) {
            if (str_contains($request->path(), $route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate API key.
     */
    private function validateApiKey(Request $request): bool
    {
        $apiKey = $request->header('X-API-Key') ?? $request->get('api_key');

        if (!$apiKey) {
            return false;
        }

        $validKeys = config('parking.api_keys', []);

        return in_array($apiKey, $validKeys);
    }

    /**
     * Check if request is for admin endpoint.
     */
    private function isAdminEndpoint(Request $request): bool
    {
        return str_starts_with($request->path(), 'api/admin/');
    }

    /**
     * Check if IP is whitelisted for admin access.
     */
    private function isWhitelistedIP(Request $request): bool
    {
        $whitelist = config('parking.admin_ip_whitelist', []);

        if (empty($whitelist)) {
            return true; // If no whitelist configured, allow all
        }

        $clientIP = $request->ip();

        foreach ($whitelist as $allowedIP) {
            if ($this->ipMatches($clientIP, $allowedIP)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if client IP matches allowed pattern.
     */
    private function ipMatches(string $clientIP, string $pattern): bool
    {
        // Exact match
        if ($clientIP === $pattern) {
            return true;
        }

        // CIDR notation support
        if (str_contains($pattern, '/')) {
            return $this->ipInRange($clientIP, $pattern);
        }

        // Wildcard support (e.g., 192.168.1.*)
        if (str_contains($pattern, '*')) {
            $regex = str_replace('*', '.*', preg_quote($pattern, '/'));
            return preg_match("/^{$regex}$/", $clientIP);
        }

        return false;
    }

    /**
     * Check if IP is in CIDR range.
     */
    private function ipInRange(string $ip, string $range): bool
    {
        [$range, $netmask] = explode('/', $range, 2);
        $range_decimal = ip2long($range);
        $ip_decimal = ip2long($ip);
        $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
        $netmask_decimal = ~ $wildcard_decimal;

        return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
    }

    /**
     * Check if request is a payment callback.
     */
    private function isPaymentCallback(Request $request): bool
    {
        $callbackPaths = [
            'api/payments/sslcommerz/',
            'api/payments/bkash/',
            'api/payments/nagad/',
            'api/payments/rocket/',
        ];

        foreach ($callbackPaths as $path) {
            if (str_contains($request->path(), $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate payment gateway signature.
     */
    private function validatePaymentSignature(Request $request): bool
    {
        // Implement signature validation based on payment gateway
        // This would be specific to each gateway's signature algorithm

        if (str_contains($request->path(), 'sslcommerz')) {
            return $this->validateSslCommerzSignature($request);
        }

        if (str_contains($request->path(), 'bkash')) {
            return $this->validateBkashSignature($request);
        }

        return true; // Default allow if no specific validation
    }

    /**
     * Validate SSLCommerz signature.
     */
    private function validateSslCommerzSignature(Request $request): bool
    {
        $storePassword = config('parking.sslcommerz.store_password');
        $signature = $request->header('X-Signature');

        if (!$signature || !$storePassword) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $request->getContent(), $storePassword);

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Validate bKash signature.
     */
    private function validateBkashSignature(Request $request): bool
    {
        // Implement bKash specific signature validation
        return true; // Placeholder
    }

    /**
     * Add security headers to response.
     */
    private function addSecurityHeaders($response)
    {
        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Content-Security-Policy' => "default-src 'self'",
        ];

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value, false);
        }

        return $response;
    }

    /**
     * Return unauthorized response.
     */
    private function unauthorized(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error_code' => 'UNAUTHORIZED',
        ], 401);
    }

    /**
     * Return forbidden response.
     */
    private function forbidden(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error_code' => 'FORBIDDEN',
        ], 403);
    }
}
