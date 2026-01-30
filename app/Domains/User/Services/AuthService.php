<?php

namespace App\Domains\User\Services;

use App\Domains\User\Models\User;
use App\Domains\User\Models\Role;
use App\Domains\User\Repositories\UserRepository;
use App\Shared\Services\AuditLogService;
use App\Shared\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected AuditLogService $auditLogService,
        protected NotificationService $notificationService
    ) {}

    /**
     * Register a new user
     */
    public function registerUser(array $userData): User
    {
        // Hash password
        $userData['password'] = Hash::make($userData['password']);
        $userData['locale'] = $userData['locale'] ?? 'en';
        $userData['is_active'] = true;

        // Create user
        $user = $this->userRepository->create($userData);

        // Assign default role
        $defaultRole = Role::where('name', 'user')->first();
        if ($defaultRole) {
            $user->roles()->attach($defaultRole->id, [
                'assigned_at' => now(),
                'assigned_by' => null // Self-registration
            ]);
        }

        // Fire registered event for email verification
        event(new Registered($user));

        // Send welcome notification
        $this->notificationService->sendWelcomeNotification($user);

        // Log registration
        $this->auditLogService->log(
            'user_registered',
            'New user registered',
            null,
            $user->id,
            [
                'email' => $user->email,
                'registration_method' => 'web'
            ]
        );

        return $user->fresh(['roles']);
    }

    /**
     * Authenticate user and return user with token
     */
    public function loginUser(string $email, string $password, string $deviceName = 'Unknown Device'): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            // Log failed login attempt
            $this->auditLogService->log(
                'login_failed',
                'Failed login attempt',
                null,
                null,
                [
                    'email' => $email,
                    'ip' => request()->ip(),
                    'user_agent' => request()->header('User-Agent')
                ]
            );

            throw new \InvalidArgumentException(__('auth.invalid_credentials'));
        }

        if (!$user->is_active) {
            throw new \InvalidArgumentException(__('auth.account_deactivated'));
        }

        // Create access token
        $token = $user->createToken($deviceName)->plainTextToken;

        // Update last login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip()
        ]);

        // Log successful login
        $this->auditLogService->log(
            'user_login',
            'User logged in successfully',
            null,
            $user->id,
            [
                'device' => $deviceName,
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent')
            ]
        );

        return [
            'user' => $user->fresh(['roles']),
            'token' => $token
        ];
    }

    /**
     * Logout user and revoke token
     */
    public function logoutUser(User $user, string $tokenId = null): void
    {
        if ($tokenId) {
            // Revoke specific token
            $user->tokens()->where('id', $tokenId)->delete();
        } else {
            // Revoke current token
            $user->currentAccessToken()->delete();
        }

        // Log logout
        $this->auditLogService->log(
            'user_logout',
            'User logged out',
            null,
            $user->id,
            [
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent')
            ]
        );
    }

    /**
     * Logout user from all devices
     */
    public function logoutFromAllDevices(User $user): void
    {
        // Revoke all tokens
        $user->tokens()->delete();

        // Log logout from all devices
        $this->auditLogService->log(
            'logout_all_devices',
            'User logged out from all devices',
            null,
            $user->id,
            [
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent')
            ]
        );
    }

    /**
     * Verify user email
     */
    public function verifyEmail(User $user): void
    {
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            // Log email verification
            $this->auditLogService->log(
                'email_verified',
                'User email verified',
                null,
                $user->id,
                ['email' => $user->email]
            );
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
            'remember_token' => \Illuminate\Support\Str::random(60)
        ]);

        // Revoke all existing tokens for security
        $user->tokens()->delete();

        // Log password reset
        $this->auditLogService->log(
            'password_reset',
            'User password reset',
            null,
            $user->id,
            [
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent')
            ]
        );
    }

    /**
     * Change user password (authenticated user)
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \InvalidArgumentException(__('auth.current_password_incorrect'));
        }

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // Revoke all existing tokens except current one for security
        $currentTokenId = $user->currentAccessToken()->id ?? null;
        $user->tokens()->when($currentTokenId, function ($query) use ($currentTokenId) {
            return $query->where('id', '!=', $currentTokenId);
        })->delete();

        // Log password change
        $this->auditLogService->log(
            'password_changed',
            'User password changed',
            null,
            $user->id,
            [
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent')
            ]
        );
    }

    /**
     * Refresh user token
     */
    public function refreshToken(User $user, string $deviceName = 'Unknown Device'): string
    {
        // Delete current token
        $user->currentAccessToken()->delete();

        // Create new token
        $newToken = $user->createToken($deviceName)->plainTextToken;

        // Log token refresh
        $this->auditLogService->log(
            'token_refreshed',
            'Access token refreshed',
            null,
            $user->id,
            [
                'device' => $deviceName,
                'ip' => request()->ip()
            ]
        );

        return $newToken;
    }

    /**
     * Check if user can access specific feature based on role
     */
    public function canAccessFeature(User $user, string $feature): bool
    {
        // Admin and super-admin can access everything
        if ($user->hasAnyRole(['admin', 'super-admin'])) {
            return true;
        }

        // Define feature access mapping
        $featureAccess = [
            'dashboard' => ['user', 'operator', 'gate-operator', 'accountant', 'viewer'],
            'vehicles' => ['user', 'operator'],
            'bookings' => ['user', 'operator'],
            'payments' => ['user', 'operator', 'accountant'],
            'reports' => ['accountant', 'viewer'],
            'gate_operations' => ['gate-operator'],
            'user_management' => ['admin', 'super-admin'],
            'system_settings' => ['admin', 'super-admin'],
        ];

        $allowedRoles = $featureAccess[$feature] ?? [];

        return $user->hasAnyRole($allowedRoles);
    }

    /**
     * Get user permissions based on roles
     */
    public function getUserPermissions(User $user): array
    {
        $permissions = [];

        foreach ($user->roles as $role) {
            $rolePermissions = $role->permissions->pluck('name')->toArray();
            $permissions = array_merge($permissions, $rolePermissions);
        }

        return array_unique($permissions);
    }
}
