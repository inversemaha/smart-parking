<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\User\Controllers\Api\AuthController as ApiAuthController;
use App\Domains\User\Controllers\Api\VehicleController as ApiVehicleController;
use App\Domains\User\Controllers\Api\BookingController as ApiBookingController;
use App\Domains\User\Controllers\Api\PaymentController as ApiPaymentController;
use App\Domains\Admin\Controllers\Api\DashboardController as ApiAdminDashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {

    // Authentication routes
    Route::prefix('auth')->name('api.auth.')->group(function () {
        Route::post('/register', [ApiAuthController::class, 'register'])->name('register');
        Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
        Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword'])->name('forgot.password');
        Route::post('/reset-password', [ApiAuthController::class, 'resetPassword'])->name('reset.password');
        Route::post('/verify-email', [ApiAuthController::class, 'verifyEmail'])->name('verify.email');
        Route::post('/resend-verification', [ApiAuthController::class, 'resendVerification'])->name('resend.verification');
    });

    // Public information routes
    Route::get('/parking-locations', [ApiBookingController::class, 'getAvailableLocations'])->name('api.parking.locations');
    Route::get('/parking-rates', [ApiBookingController::class, 'getParkingRates'])->name('api.parking.rates');

    // Gate API routes (for gate operators)
    Route::prefix('gate')->name('api.gate.')->group(function () {
        Route::post('/entry', [\App\Domains\Gate\Controllers\GateController::class, 'vehicleEntry'])->name('entry');
        Route::post('/exit', [\App\Domains\Gate\Controllers\GateController::class, 'vehicleExit'])->name('exit');
        Route::post('/scan', [\App\Domains\Gate\Controllers\GateController::class, 'scanQrCode'])->name('scan');
        Route::get('/logs', [\App\Domains\Gate\Controllers\GateController::class, 'getGateLogs'])->name('logs');
    });
});

// Protected API routes (authentication required)
Route::prefix('v1')->middleware(['auth:sanctum', 'api.rate.limit'])->group(function () {

    // User profile routes
    Route::prefix('user')->name('api.user.')->group(function () {
        Route::get('/profile', [ApiAuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [ApiAuthController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [ApiAuthController::class, 'updatePassword'])->name('password.update');
        Route::post('/avatar', [ApiAuthController::class, 'updateAvatar'])->name('avatar.update');
        Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout');
        Route::delete('/account', [ApiAuthController::class, 'deleteAccount'])->name('account.delete');
    });

    // Vehicle management routes
    Route::prefix('vehicles')->name('api.vehicles.')->group(function () {
        Route::get('/', [ApiVehicleController::class, 'index'])->name('index');
        Route::post('/', [ApiVehicleController::class, 'store'])->name('store');
        Route::get('/{vehicle}', [ApiVehicleController::class, 'show'])->name('show');
        Route::put('/{vehicle}', [ApiVehicleController::class, 'update'])->name('update');
        Route::delete('/{vehicle}', [ApiVehicleController::class, 'destroy'])->name('destroy');
        Route::post('/{vehicle}/documents', [ApiVehicleController::class, 'uploadDocuments'])->name('upload.documents');
        Route::get('/{vehicle}/verification-status', [ApiVehicleController::class, 'getVerificationStatus'])->name('verification.status');
    });

    // Booking management routes
    Route::prefix('bookings')->name('api.bookings.')->group(function () {
        Route::get('/', [ApiBookingController::class, 'index'])->name('index');
        Route::post('/', [ApiBookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [ApiBookingController::class, 'show'])->name('show');
        Route::put('/{booking}', [ApiBookingController::class, 'update'])->name('update');
        Route::delete('/{booking}', [ApiBookingController::class, 'cancel'])->name('cancel');
        Route::post('/{booking}/extend', [ApiBookingController::class, 'extend'])->name('extend');
        Route::post('/{booking}/confirm', [ApiBookingController::class, 'confirm'])->name('confirm');

        // Booking utilities
        Route::get('/calculate-cost', [ApiBookingController::class, 'calculateCost'])->name('calculate.cost');
        Route::get('/available-slots', [ApiBookingController::class, 'getAvailableSlots'])->name('slots.available');
        Route::get('/history', [ApiBookingController::class, 'getBookingHistory'])->name('history');
    });

    // Payment routes
    Route::prefix('payments')->name('api.payments.')->group(function () {
        Route::get('/', [ApiPaymentController::class, 'index'])->name('index');
        Route::post('/booking/{booking}', [ApiPaymentController::class, 'createPayment'])->name('create');
        Route::get('/{payment}', [ApiPaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/receipt', [ApiPaymentController::class, 'receipt'])->name('receipt');
        Route::get('/{payment}/status', [ApiPaymentController::class, 'getPaymentStatus'])->name('status');

        // Payment gateway webhooks (public, but secured)
        Route::post('/webhook/sslcommerz', [ApiPaymentController::class, 'handleSSLCommerzWebhook'])->name('webhook.sslcommerz')->withoutMiddleware(['auth:sanctum']);
    });

    // Device management for API tokens
    Route::prefix('devices')->name('api.devices.')->group(function () {
        Route::get('/', [ApiAuthController::class, 'getDevices'])->name('index');
        Route::post('/register', [ApiAuthController::class, 'registerDevice'])->name('register');
        Route::delete('/{device}', [ApiAuthController::class, 'revokeDevice'])->name('revoke');
        Route::delete('/all', [ApiAuthController::class, 'revokeAllDevices'])->name('revoke.all');
    });
});

// Admin API routes
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'role:admin', 'api.rate.limit.admin'])->name('api.admin.')->group(function () {

    // Dashboard and statistics
    Route::get('/dashboard', [ApiAdminDashboardController::class, 'getDashboardStats'])->name('dashboard');
    Route::get('/analytics', [ApiAdminDashboardController::class, 'getAnalytics'])->name('analytics');

    // Vehicle verification management
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/pending', [ApiAdminDashboardController::class, 'getPendingVehicles'])->name('pending');
        Route::post('/{vehicle}/verify', [ApiAdminDashboardController::class, 'verifyVehicle'])->name('verify');
        Route::post('/{vehicle}/reject', [ApiAdminDashboardController::class, 'rejectVehicle'])->name('reject');
        Route::get('/{vehicle}/documents', [ApiAdminDashboardController::class, 'getVehicleDocuments'])->name('documents');
    });

    // System management
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/health', [ApiAdminDashboardController::class, 'getSystemHealth'])->name('health');
        Route::get('/logs', [ApiAdminDashboardController::class, 'getSystemLogs'])->name('logs');
        Route::post('/cache/clear', [ApiAdminDashboardController::class, 'clearCache'])->name('cache.clear');
        Route::get('/queue/status', [ApiAdminDashboardController::class, 'getQueueStatus'])->name('queue.status');
        Route::post('/queue/restart', [ApiAdminDashboardController::class, 'restartQueue'])->name('queue.restart');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/revenue', [ApiAdminDashboardController::class, 'getRevenueReport'])->name('revenue');
        Route::get('/bookings', [ApiAdminDashboardController::class, 'getBookingReport'])->name('bookings');
        Route::get('/users', [ApiAdminDashboardController::class, 'getUserReport'])->name('users');
        Route::get('/vehicles', [ApiAdminDashboardController::class, 'getVehicleReport'])->name('vehicles');
        Route::get('/export/{type}', [ApiAdminDashboardController::class, 'exportReport'])->name('export');
    });
});

// Fallback route for API 404s
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found',
        'error' => 'ENDPOINT_NOT_FOUND'
    ], 404);
});
