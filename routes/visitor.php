<?php

use Illuminate\Support\Facades\Route;
use App\Domains\User\Controllers\VisitorController;
use App\Domains\User\Controllers\VisitorProfileController;
use App\Domains\Vehicle\Controllers\VisitorVehicleController;
use App\Domains\Booking\Controllers\VisitorBookingController;
use App\Domains\Payment\Controllers\VisitorPaymentController;
use App\Domains\Parking\Controllers\VisitorParkingController;

/*
|--------------------------------------------------------------------------
| Visitor Panel Routes
|--------------------------------------------------------------------------
|
| These routes handle the visitor (public user) functionality for the
| Smart Parking System. Visitors are regular end-users who book parking.
|
*/

// Language switching for visitors
Route::post('/language/{locale}', [VisitorController::class, 'switchLanguage'])->name('visitor.language.switch');

// Public parking discovery (no auth required)
Route::prefix('parking')->name('visitor.parking.')->group(function () {
    Route::get('/locations', [VisitorParkingController::class, 'locations'])->name('locations');
    Route::get('/locations/{location}', [VisitorParkingController::class, 'locationDetails'])->name('location.details');
    Route::get('/locations/{location}/availability', [VisitorParkingController::class, 'availability'])->name('availability');
    Route::post('/slots/check-availability', [VisitorParkingController::class, 'checkAvailability'])->name('check.availability');
});

// Guest routes for visitors (not authenticated)
Route::middleware(['guest', 'throttle:auth'])->group(function () {

    // Visitor registration and login
    Route::prefix('visitor')->name('visitor.')->group(function () {
        Route::get('/register', [VisitorController::class, 'showRegister'])->name('register');
        Route::post('/register', [VisitorController::class, 'register'])->name('register.store');

        Route::get('/login', [VisitorController::class, 'showLogin'])->name('login');
        Route::post('/login', [VisitorController::class, 'login'])->name('login.store');

        // OTP verification
        Route::get('/verify-otp', [VisitorController::class, 'showOtpVerification'])->name('verify.otp');
        Route::post('/verify-otp', [VisitorController::class, 'verifyOtp'])->name('verify.otp.store');
        Route::post('/resend-otp', [VisitorController::class, 'resendOtp'])->name('resend.otp');

        // Password reset
        Route::get('/forgot-password', [VisitorController::class, 'showForgotPassword'])->name('password.request');
        Route::post('/forgot-password', [VisitorController::class, 'sendPasswordReset'])->name('password.email');
        Route::get('/reset-password/{token}', [VisitorController::class, 'showResetPassword'])->name('password.reset');
        Route::post('/reset-password', [VisitorController::class, 'resetPassword'])->name('password.store');
    });
});

// Authenticated visitor routes
Route::middleware(['auth', 'verified', 'set.language', 'throttle:api'])->prefix('visitor')->name('visitor.')->group(function () {

    // Visitor dashboard
    Route::get('/dashboard', [VisitorController::class, 'dashboard'])->name('dashboard');

    // Visitor logout
    Route::post('/logout', [VisitorController::class, 'logout'])->name('logout');

    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [VisitorProfileController::class, 'index'])->name('index');
        Route::get('/edit', [VisitorProfileController::class, 'edit'])->name('edit');
        Route::put('/', [VisitorProfileController::class, 'update'])->name('update');
        Route::put('/password', [VisitorProfileController::class, 'updatePassword'])->name('password.update');
        Route::post('/avatar', [VisitorProfileController::class, 'updateAvatar'])->name('avatar.update');
        Route::delete('/avatar', [VisitorProfileController::class, 'removeAvatar'])->name('avatar.remove');
        Route::delete('/', [VisitorProfileController::class, 'destroy'])->name('destroy');
    });

    // Vehicle management
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/', [VisitorVehicleController::class, 'index'])->name('index');
        Route::get('/create', [VisitorVehicleController::class, 'create'])->name('create');
        Route::post('/', [VisitorVehicleController::class, 'store'])->name('store');
        Route::get('/{vehicle}', [VisitorVehicleController::class, 'show'])->name('show');
        Route::get('/{vehicle}/edit', [VisitorVehicleController::class, 'edit'])->name('edit');
        Route::put('/{vehicle}', [VisitorVehicleController::class, 'update'])->name('update');
        Route::delete('/{vehicle}', [VisitorVehicleController::class, 'destroy'])->name('destroy');
        Route::post('/{vehicle}/set-default', [VisitorVehicleController::class, 'setDefault'])->name('set.default');
        Route::post('/{vehicle}/documents', [VisitorVehicleController::class, 'uploadDocuments'])->name('upload.documents');
    });

    // Booking management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [VisitorBookingController::class, 'index'])->name('index');
        Route::get('/create', [VisitorBookingController::class, 'create'])->name('create');
        Route::post('/', [VisitorBookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [VisitorBookingController::class, 'show'])->name('show');
        Route::put('/{booking}/cancel', [VisitorBookingController::class, 'cancel'])->name('cancel');
        Route::put('/{booking}/extend', [VisitorBookingController::class, 'extend'])->name('extend');
        Route::get('/{booking}/receipt', [VisitorBookingController::class, 'receipt'])->name('receipt');

        // AJAX routes for dynamic content
        Route::get('/api/slots/available', [VisitorBookingController::class, 'getAvailableSlots'])->name('api.slots.available');
        Route::post('/api/calculate-cost', [VisitorBookingController::class, 'calculateCost'])->name('api.calculate.cost');
        Route::post('/api/validate-booking', [VisitorBookingController::class, 'validateBooking'])->name('api.validate.booking');
    });

    // Payment management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [VisitorPaymentController::class, 'index'])->name('index');
        Route::get('/booking/{booking}', [VisitorPaymentController::class, 'create'])->name('create');
        Route::post('/booking/{booking}', [VisitorPaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [VisitorPaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/invoice', [VisitorPaymentController::class, 'invoice'])->name('invoice');
        Route::get('/{payment}/download', [VisitorPaymentController::class, 'download'])->name('download');
    });
});

// Payment gateway webhooks and callbacks (no auth required)
Route::prefix('visitor/payments')->name('visitor.payments.')->group(function () {
    // SSLCommerz callbacks
    Route::post('/gateway/success', [VisitorPaymentController::class, 'gatewaySuccess'])->name('gateway.success');
    Route::post('/gateway/failure', [VisitorPaymentController::class, 'gatewayFailure'])->name('gateway.failure');
    Route::post('/gateway/cancel', [VisitorPaymentController::class, 'gatewayCancel'])->name('gateway.cancel');
    Route::post('/gateway/webhook', [VisitorPaymentController::class, 'gatewayWebhook'])->name('gateway.webhook');

    // Payment status checks
    Route::get('/status/{payment}', [VisitorPaymentController::class, 'checkStatus'])->name('status');
});

// Public API endpoints for mobile app (with throttling)
Route::middleware(['throttle:api'])->prefix('api/visitor')->name('api.visitor.')->group(function () {

    // Authentication endpoints
    Route::post('/auth/login', [VisitorController::class, 'apiLogin'])->name('api.login');
    Route::post('/auth/register', [VisitorController::class, 'apiRegister'])->name('api.register');
    Route::post('/auth/refresh', [VisitorController::class, 'apiRefresh'])->name('api.refresh');

    // Protected API endpoints
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/auth/logout', [VisitorController::class, 'apiLogout'])->name('api.logout');
        Route::get('/profile', [VisitorProfileController::class, 'apiProfile'])->name('api.profile');
        Route::put('/profile', [VisitorProfileController::class, 'apiUpdateProfile'])->name('api.profile.update');

        // Vehicles API
        Route::apiResource('vehicles', VisitorVehicleController::class)->names([
            'index' => 'api.vehicles.index',
            'store' => 'api.vehicles.store',
            'show' => 'api.vehicles.show',
            'update' => 'api.vehicles.update',
            'destroy' => 'api.vehicles.destroy',
        ]);

        // Bookings API
        Route::apiResource('bookings', VisitorBookingController::class)->names([
            'index' => 'api.bookings.index',
            'store' => 'api.bookings.store',
            'show' => 'api.bookings.show',
            'update' => 'api.bookings.update',
            'destroy' => 'api.bookings.destroy',
        ]);

        // Payments API
        Route::get('/payments', [VisitorPaymentController::class, 'apiIndex'])->name('api.payments.index');
        Route::get('/payments/{payment}', [VisitorPaymentController::class, 'apiShow'])->name('api.payments.show');
    });

    // Public parking API (no auth)
    Route::get('/parking/locations', [VisitorParkingController::class, 'apiLocations'])->name('api.parking.locations');
    Route::get('/parking/locations/{location}', [VisitorParkingController::class, 'apiLocationDetails'])->name('api.parking.location.details');
    Route::post('/parking/check-availability', [VisitorParkingController::class, 'apiCheckAvailability'])->name('api.parking.check.availability');
});
