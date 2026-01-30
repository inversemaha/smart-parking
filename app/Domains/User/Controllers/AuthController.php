<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Models\User;
use App\Domains\User\Services\AuthService;
use App\Shared\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected AuditLogService $auditLogService
    ) {
    }

    /**
     * User registration API
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'locale' => ['sometimes', 'in:en,bn']
        ]);

        try {
            $user = $this->authService->registerUser($request->all());

            // Create access token
            $token = $user->createToken('auth-token')->plainTextToken;

            // Log registration
            $this->auditLogService->log(
                'user_registered',
                'User registered successfully',
                null,
                $user->id,
                ['email' => $user->email]
            );

            return response()->json([
                'success' => true,
                'message' => __('auth.registration_successful'),
                'data' => [
                    'user' => $user->fresh(['roles']),
                    'token' => $token
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('auth.registration_failed'),
                'errors' => ['general' => $e->getMessage()]
            ], 422);
        }
    }

    /**
     * User login API
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'sometimes|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // Log failed login attempt
            $this->auditLogService->log(
                'login_failed',
                'Failed login attempt',
                null,
                null,
                ['email' => $request->email, 'ip' => $request->ip()]
            );

            return response()->json([
                'success' => false,
                'message' => __('auth.invalid_credentials')
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => __('auth.account_deactivated')
            ], 403);
        }

        // Create access token
        $deviceName = $request->device_name ?? $request->header('User-Agent') ?? 'Unknown Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        // Log successful login
        $this->auditLogService->log(
            'user_login',
            'User logged in successfully',
            null,
            $user->id,
            ['device' => $deviceName, 'ip' => $request->ip()]
        );

        return response()->json([
            'success' => true,
            'message' => __('auth.login_successful'),
            'data' => [
                'user' => $user->fresh(['roles']),
                'token' => $token
            ]
        ]);
    }

    /**
     * User logout API
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        // Log logout
        $this->auditLogService->log(
            'user_logout',
            'User logged out successfully',
            null,
            $user->id,
            ['ip' => $request->ip()]
        );

        return response()->json([
            'success' => true,
            'message' => __('auth.logout_successful')
        ]);
    }

    /**
     * Refresh token API
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $user = $request->user();

        // Delete current token
        $request->user()->currentAccessToken()->delete();

        // Create new token
        $deviceName = $request->device_name ?? $request->header('User-Agent') ?? 'Unknown Device';
        $newToken = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => __('auth.token_refreshed'),
            'data' => [
                'token' => $newToken
            ]
        ]);
    }

    /**
     * Get authenticated user info
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['roles', 'vehicles', 'bookings']);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Forgot password API
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            // Log password reset request
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $this->auditLogService->log(
                    'password_reset_requested',
                    'Password reset link requested',
                    null,
                    $user->id,
                    ['email' => $request->email, 'ip' => $request->ip()]
                );
            }

            return response()->json([
                'success' => true,
                'message' => __('passwords.sent')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status)
        ], 422);
    }

    /**
     * Reset password API
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()]
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(\Illuminate\Support\Str::random(60));

                $user->save();

                // Log password reset
                $this->auditLogService->log(
                    'password_reset',
                    'Password reset successfully',
                    null,
                    $user->id,
                    ['email' => $user->email, 'ip' => $request->ip()]
                );
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => __('passwords.reset')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status)
        ], 422);
    }
}
