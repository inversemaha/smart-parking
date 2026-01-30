<?php

namespace App\Domains\User\Services;

use App\Domains\User\Models\User;
use App\Domains\User\DTOs\VisitorRegistrationData;
use App\Domains\User\DTOs\VisitorLoginData;
use App\Domains\User\Repositories\UserRepository;
use App\Shared\Services\CacheService;
use App\Shared\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VisitorService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected CacheService $cacheService,
        protected NotificationService $notificationService
    ) {}

    /**
     * Register a new visitor
     */
    public function register(VisitorRegistrationData $data): User
    {
        return DB::transaction(function () use ($data) {
            // Create user with visitor role
            $user = $this->userRepository->create([
                'name' => $data->name,
                'email' => $data->email,
                'mobile' => $data->mobile,
                'password' => Hash::make($data->password),
                'email_verified_at' => now(), // Auto verify for now
                'mobile_verified_at' => now(), // Auto verify for now
                'preferred_language' => $data->language ?? 'en',
                'status' => 'active',
                'user_type' => 'visitor'
            ]);

            // Assign visitor role
            $user->assignRole('visitor');

            // Create default settings
            $this->createDefaultUserSettings($user);

            return $user;
        });
    }

    /**
     * Login visitor
     */
    public function login(VisitorLoginData $data): bool
    {
        // Determine if login is email or mobile
        $field = filter_var($data->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        $credentials = [
            $field => $data->login,
            'password' => $data->password
        ];

        // Add additional checks
        $credentials['status'] = 'active';
        $credentials['user_type'] = 'visitor';

        if (Auth::attempt($credentials, $data->remember)) {
            // Log successful login
            $this->logUserActivity(Auth::user(), 'login', [
                'login_method' => $field,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return true;
        }

        return false;
    }

    /**
     * API Login for mobile
     */
    public function apiLogin(VisitorLoginData $data): array
    {
        $field = filter_var($data->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        $user = User::where($field, $data->login)
                   ->where('status', 'active')
                   ->where('user_type', 'visitor')
                   ->first();

        if ($user && Hash::check($data->password, $user->password)) {
            // Delete old tokens for this device
            $user->tokens()->where('name', $data->deviceName)->delete();

            // Create new token
            $token = $user->createToken($data->deviceName)->plainTextToken;

            // Log successful login
            $this->logUserActivity($user, 'api_login', [
                'device_name' => $data->deviceName,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return [
                'success' => true,
                'user' => $user,
                'token' => $token
            ];
        }

        return ['success' => false];
    }

    /**
     * API Register for mobile
     */
    public function apiRegister(VisitorRegistrationData $data): array
    {
        $user = $this->register($data);

        // Create token for API access
        $token = $user->createToken($data->deviceName ?? 'mobile-app')->plainTextToken;

        // Send welcome notification
        $this->notificationService->sendWelcomeNotification($user);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Send password reset link
     */
    public function sendPasswordResetLink(string $email): bool
    {
        $user = User::where('email', $email)
                   ->where('user_type', 'visitor')
                   ->where('status', 'active')
                   ->first();

        if (!$user) {
            return false;
        }

        $status = Password::sendResetLink(['email' => $email]);

        return $status === Password::RESET_LINK_SENT;
    }

    /**
     * Reset password
     */
    public function resetPassword(array $data): bool
    {
        $status = Password::reset($data, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET;
    }

    /**
     * Verify OTP (if OTP system is implemented)
     */
    public function verifyOtp(string $otp): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Get stored OTP from cache
        $storedOtp = $this->cacheService->get("otp_{$user->id}");

        if ($storedOtp && $storedOtp === $otp) {
            // Mark mobile as verified
            $user->update(['mobile_verified_at' => now()]);

            // Clear OTP from cache
            $this->cacheService->forget("otp_{$user->id}");

            return true;
        }

        return false;
    }

    /**
     * Resend OTP
     */
    public function resendOtp(): bool
    {
        $user = auth()->user();

        if (!$user || $user->mobile_verified_at) {
            return false;
        }

        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store in cache for 5 minutes
        $this->cacheService->put("otp_{$user->id}", $otp, 300);

        // Send OTP via SMS
        $this->notificationService->sendSmsOtp($user, $otp);

        return true;
    }

    /**
     * Get visitor statistics
     */
    public function getVisitorStats(int $userId): array
    {
        return $this->cacheService->remember("visitor_stats_{$userId}", 300, function () use ($userId) {
            $user = $this->userRepository->find($userId);

            return [
                'total_bookings' => $user->bookings()->count(),
                'active_bookings' => $user->bookings()->where('status', 'active')->count(),
                'completed_bookings' => $user->bookings()->where('status', 'completed')->count(),
                'total_vehicles' => $user->vehicles()->count(),
                'total_spent' => $user->payments()->where('status', 'paid')->sum('amount'),
                'member_since' => $user->created_at->format('M Y'),
                'last_booking' => $user->bookings()->latest()->first()?->created_at,
            ];
        });
    }

    /**
     * Update visitor profile
     */
    public function updateProfile(int $userId, array $data): User
    {
        $user = $this->userRepository->find($userId);

        // Clear cache
        $this->clearVisitorCache($userId);

        return $this->userRepository->update($user->id, $data);
    }

    /**
     * Update visitor password
     */
    public function updatePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        $user = $this->userRepository->find($userId);

        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $this->userRepository->update($user->id, [
            'password' => Hash::make($newPassword),
            'remember_token' => Str::random(60),
        ]);

        // Send notification about password change
        $this->notificationService->sendPasswordChangedNotification($user);

        return true;
    }

    /**
     * Deactivate visitor account
     */
    public function deactivateAccount(int $userId): bool
    {
        $user = $this->userRepository->find($userId);

        DB::transaction(function () use ($user) {
            // Cancel active bookings
            $user->bookings()
                 ->whereIn('status', ['pending', 'confirmed'])
                 ->update(['status' => 'cancelled', 'cancelled_at' => now()]);

            // Deactivate user
            $user->update(['status' => 'inactive', 'deactivated_at' => now()]);

            // Revoke all tokens
            $user->tokens()->delete();
        });

        // Clear cache
        $this->clearVisitorCache($userId);

        return true;
    }

    /**
     * Get active visitors count
     */
    public function getActiveVisitorsCount(): int
    {
        return $this->cacheService->remember('active_visitors_count', 300, function () {
            return User::where('user_type', 'visitor')
                      ->where('status', 'active')
                      ->count();
        });
    }

    /**
     * Create default user settings
     */
    protected function createDefaultUserSettings(User $user): void
    {
        $defaultSettings = [
            'notifications' => [
                'email_booking_confirmation' => true,
                'email_payment_confirmation' => true,
                'sms_booking_reminder' => true,
                'sms_payment_reminder' => true,
                'push_notifications' => true,
            ],
            'preferences' => [
                'default_vehicle' => null,
                'preferred_payment_method' => 'card',
                'auto_extend_booking' => false,
            ],
            'privacy' => [
                'show_profile_to_others' => false,
                'allow_marketing_emails' => false,
            ]
        ];

        $user->settings()->create([
            'settings' => $defaultSettings
        ]);
    }

    /**
     * Log user activity
     */
    protected function logUserActivity(User $user, string $action, array $metadata = []): void
    {
        $user->activityLogs()->create([
            'action' => $action,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'performed_at' => now()
        ]);
    }

    /**
     * Clear visitor cache
     */
    protected function clearVisitorCache(int $userId): void
    {
        $this->cacheService->forget("visitor_stats_{$userId}");
        $this->cacheService->forget('active_visitors_count');
    }
}
