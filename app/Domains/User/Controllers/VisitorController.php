<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Services\VisitorService;
use App\Domains\User\DTOs\VisitorRegistrationData;
use App\Domains\User\DTOs\VisitorLoginData;
use App\Shared\Services\ParkingLocationService;
use App\Domains\Booking\Services\BookingService;
use App\Domains\Vehicle\Services\VehicleService;
use App\Domains\Payment\Services\PaymentService;
use App\Shared\Services\CacheService;
use App\Shared\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;

class VisitorController extends Controller
{
    public function __construct(
        protected VisitorService $visitorService,
        protected ParkingLocationService $parkingLocationService,
        protected BookingService $bookingService,
        protected VehicleService $vehicleService,
        protected PaymentService $paymentService,
        protected CacheService $cacheService,
        protected NotificationService $notificationService
    ) {
        // Apply middleware conditionally
        $this->middleware('guest')->only(['showRegister', 'register', 'showLogin', 'login', 'showForgotPassword', 'sendPasswordReset', 'showResetPassword', 'resetPassword']);
        $this->middleware('auth')->only(['dashboard', 'logout']);
        $this->middleware('throttle:auth')->only(['login', 'register', 'sendPasswordReset']);
        $this->middleware('set.language');
    }

    /**
     * Show welcome page with parking overview
     */
    public function welcome(): View
    {
        // Get basic statistics
        $totalLocations = $this->parkingLocationService->getActiveLocationCount();
        $totalSlots = $this->parkingLocationService->getTotalSlotCount();
        $availableSlots = $this->parkingLocationService->getTotalAvailableSlotCount();

        // Get featured/popular locations for display
        $featuredLocations = $this->parkingLocationService->getFeaturedLocations(6);

        return view('visitor.welcome', compact(
            'totalLocations',
            'totalSlots',
            'availableSlots',
            'featuredLocations'
        ));
    }

    /**
     * Redirect to appropriate dashboard based on user role
     */
    public function redirectToDashboard(): RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['admin', 'super-admin', 'manager'])) {
            return redirect()->route('admin.dashboard.index');
        }

        return redirect()->route('visitor.dashboard');
    }

    /**
     * Show visitor registration form
     */
    public function showRegister(): View
    {
        return view('visitor.auth.register');
    }

    /**
     * Handle visitor registration
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'string', 'regex:/^01[3-9]\d{8}$/', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        DB::beginTransaction();
        try {
            $registrationData = new VisitorRegistrationData(
                name: $request->name,
                email: $request->email,
                mobile: $request->mobile,
                password: $request->password,
                language: session('locale', 'en')
            );

            $user = $this->visitorService->register($registrationData);

            event(new Registered($user));

            // Auto login after registration
            Auth::login($user);

            DB::commit();

            // Send welcome notification
            $this->notificationService->sendWelcomeNotification($user);

            return redirect()->route('visitor.dashboard')->with('success', __('auth.registration_successful'));

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['email' => __('auth.registration_failed')])
                        ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Show visitor login form
     */
    public function showLogin(): View
    {
        return view('visitor.auth.login');
    }

    /**
     * Handle visitor login
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'], // Can be email or mobile
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        $loginData = new VisitorLoginData(
            login: $request->login,
            password: $request->password,
            remember: $request->boolean('remember')
        );

        if ($this->visitorService->login($loginData)) {
            $request->session()->regenerate();

            return redirect()->intended(route('visitor.dashboard'));
        }

        throw ValidationException::withMessages([
            'login' => __('auth.failed'),
        ]);
    }

    /**
     * Handle visitor logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('visitor.login')->with('success', __('auth.logged_out'));
    }

    /**
     * Show visitor dashboard
     */
    public function dashboard(): View
    {
        $user = auth()->user();

        // Get visitor statistics
        $stats = [
            'total_vehicles' => $this->vehicleService->getUserVehicleCount($user->id),
            'active_bookings' => $this->bookingService->getActiveBookingCount($user->id),
            'total_bookings' => $this->bookingService->getUserBookingCount($user->id),
            'total_spent' => $this->paymentService->getUserTotalSpent($user->id),
        ];

        // Get recent bookings
        $recentBookings = $this->bookingService->getUserRecentBookings($user->id, 5);

        // Get vehicles
        $vehicles = $this->vehicleService->getUserVehicles($user->id);

        // Get upcoming bookings
        $upcomingBookings = $this->bookingService->getUserUpcomingBookings($user->id, 3);

        return view('visitor.dashboard', compact(
            'user',
            'stats',
            'recentBookings',
            'vehicles',
            'upcomingBookings'
        ));
    }

    /**
     * Handle language switching
     */
    public function switchLanguage(string $locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'bn'])) {
            session(['locale' => $locale]);

            // Update user preference if logged in
            if (auth()->check()) {
                auth()->user()->update(['preferred_language' => $locale]);
            }
        }

        return redirect()->back();
    }

    /**
     * Show OTP verification form
     */
    public function showOtpVerification(): View
    {
        return view('visitor.auth.verify-otp');
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if ($this->visitorService->verifyOtp($request->otp)) {
            return redirect()->route('visitor.dashboard')->with('success', __('auth.otp_verified'));
        }

        return back()->withErrors(['otp' => __('auth.invalid_otp')]);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request): JsonResponse
    {
        if ($this->visitorService->resendOtp()) {
            return response()->json([
                'success' => true,
                'message' => __('auth.otp_sent')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('auth.otp_failed')
        ], 400);
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword(): View
    {
        return view('visitor.auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendPasswordReset(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($this->visitorService->sendPasswordResetLink($request->email)) {
            return back()->with('status', __('passwords.sent'));
        }

        return back()->withErrors(['email' => __('passwords.user')]);
    }

    /**
     * Show reset password form
     */
    public function showResetPassword(Request $request, string $token): View
    {
        return view('visitor.auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($this->visitorService->resetPassword($request->only('email', 'password', 'password_confirmation', 'token'))) {
            return redirect()->route('visitor.login')->with('status', __('passwords.reset'));
        }

        return back()->withErrors(['email' => __('passwords.user')]);
    }

    // API Methods for mobile app

    /**
     * API Login
     */
    public function apiLogin(Request $request): JsonResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string'],
        ]);

        $loginData = new VisitorLoginData(
            login: $request->login,
            password: $request->password,
            deviceName: $request->device_name
        );

        $result = $this->visitorService->apiLogin($loginData);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $result['user'],
                    'token' => $result['token']
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('auth.failed')
        ], 401);
    }

    /**
     * API Register
     */
    public function apiRegister(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'string', 'regex:/^01[3-9]\d{8}$/', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'device_name' => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $registrationData = new VisitorRegistrationData(
                name: $request->name,
                email: $request->email,
                mobile: $request->mobile,
                password: $request->password,
                deviceName: $request->device_name
            );

            $result = $this->visitorService->apiRegister($registrationData);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $result['user'],
                    'token' => $result['token']
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => __('auth.registration_failed'),
                'errors' => ['general' => [$e->getMessage()]]
            ], 422);
        }
    }

    /**
     * API Logout
     */
    public function apiLogout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => __('auth.logged_out')
        ]);
    }

    /**
     * API Refresh Token
     */
    public function apiRefresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // Delete old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken($request->device_name ?? 'mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }
}
