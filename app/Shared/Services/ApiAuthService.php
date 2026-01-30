<?php

namespace App\Shared\Services;

use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class ApiAuthService
{
    /**
     * Authenticate user and create token.
     */
    public function authenticate(array $credentials, string $deviceName = 'API'): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'account' => ['Your account has been suspended. Please contact support.'],
            ]);
        }

        // Create token with specific abilities based on user role
        $abilities = $this->getTokenAbilities($user);
        $expiresAt = now()->addMinutes(config('sanctum.expiration', 1440));

        $token = $user->createToken($deviceName, $abilities, $expiresAt);

        // Log successful authentication
        \Illuminate\Support\Facades\Log::channel('audit')->info('User authenticated via API', [
            'user_id' => $user->id,
            'email' => $user->email,
            'device_name' => $deviceName,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return [
            'user' => $user->load('roles', 'permissions'),
            'token' => $token->plainTextToken,
            'expires_at' => $expiresAt->toISOString(),
            'abilities' => $abilities,
        ];
    }

    /**
     * Get token abilities based on user role.
     */
    protected function getTokenAbilities(User $user): array
    {
        $abilities = ['api:access'];

        if ($user->hasRole('admin')) {
            $abilities = array_merge($abilities, [
                'admin:read',
                'admin:write',
                'admin:delete',
                'reports:generate',
                'system:manage',
            ]);
        }

        if ($user->hasRole('operator')) {
            $abilities = array_merge($abilities, [
                'vehicles:verify',
                'bookings:manage',
                'payments:process',
            ]);
        }

        if ($user->hasRole('user')) {
            $abilities = array_merge($abilities, [
                'profile:read',
                'profile:write',
                'vehicles:read',
                'vehicles:write',
                'bookings:read',
                'bookings:write',
                'payments:read',
            ]);
        }

        return $abilities;
    }

    /**
     * Refresh user token.
     */
    public function refreshToken(User $user, string $deviceName = null): array
    {
        // Get current token
        $currentToken = $user->currentAccessToken();

        if (!$currentToken) {
            throw new \Exception('No active token found');
        }

        $deviceName = $deviceName ?? $currentToken->name;

        // Revoke current token
        $currentToken->delete();

        // Create new token
        $abilities = $this->getTokenAbilities($user);
        $expiresAt = now()->addMinutes(config('sanctum.expiration', 1440));

        $token = $user->createToken($deviceName, $abilities, $expiresAt);

        return [
            'user' => $user->load('roles', 'permissions'),
            'token' => $token->plainTextToken,
            'expires_at' => $expiresAt->toISOString(),
            'abilities' => $abilities,
        ];
    }

    /**
     * Revoke user token.
     */
    public function revokeToken(User $user, string $tokenId = null): bool
    {
        if ($tokenId) {
            // Revoke specific token
            $token = $user->tokens()->where('id', $tokenId)->first();
            if ($token) {
                $token->delete();
                return true;
            }
            return false;
        }

        // Revoke current token
        $currentToken = $user->currentAccessToken();
        if ($currentToken) {
            $currentToken->delete();
            return true;
        }

        return false;
    }

    /**
     * Revoke all tokens for user.
     */
    public function revokeAllTokens(User $user): int
    {
        $count = $user->tokens()->count();
        $user->tokens()->delete();

        \Illuminate\Support\Facades\Log::channel('audit')->info('All tokens revoked for user', [
            'user_id' => $user->id,
            'tokens_revoked' => $count,
        ]);

        return $count;
    }

    /**
     * Get active tokens for user.
     */
    public function getActiveTokens(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $user->tokens()
            ->where('expires_at', '>', now())
            ->orderBy('last_used_at', 'desc')
            ->get();
    }

    /**
     * Validate token abilities.
     */
    public function hasAbility(User $user, string $ability): bool
    {
        $currentToken = $user->currentAccessToken();

        if (!$currentToken) {
            return false;
        }

        return $currentToken->can($ability);
    }

    /**
     * Get token usage statistics.
     */
    public function getTokenStats(User $user): array
    {
        $tokens = $user->tokens();

        return [
            'total_tokens' => $tokens->count(),
            'active_tokens' => $tokens->where('expires_at', '>', now())->count(),
            'expired_tokens' => $tokens->where('expires_at', '<=', now())->count(),
            'last_used' => $tokens->whereNotNull('last_used_at')
                ->orderBy('last_used_at', 'desc')
                ->first()?->last_used_at,
        ];
    }

    /**
     * Clean up expired tokens.
     */
    public function cleanupExpiredTokens(): int
    {
        return PersonalAccessToken::where('expires_at', '<', now())->delete();
    }

    /**
     * Validate API key for external integrations.
     */
    public function validateApiKey(string $apiKey): ?User
    {
        // For external integrations, we can create service users
        // with specific API keys stored in a separate table

        // This is a placeholder - implement based on your needs
        $serviceUsers = [
            'brta_service' => config('parking.brta.api_key'),
            'gateway_service' => config('parking.gateway.api_key'),
        ];

        foreach ($serviceUsers as $username => $key) {
            if ($key && hash_equals($key, $apiKey)) {
                return User::where('email', $username . '@parking.system')->first();
            }
        }

        return null;
    }
}
