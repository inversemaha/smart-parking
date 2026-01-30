<?php

use Illuminate\Support\Facades\Route;
use App\Domains\User\Controllers\DashboardController as UserDashboardController;
use App\Domains\User\Controllers\VehicleController as UserVehicleController;
use App\Domains\User\Controllers\BookingController as UserBookingController;
use App\Domains\User\Controllers\PaymentController as UserPaymentController;
use App\Domains\Auth\Controllers\AuthController;
use App\Domains\Admin\Controllers\PermissionController;
use App\Domains\Admin\Controllers\AdminDashboardController;
use App\Domains\User\Controllers\VisitorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Smart Parking System Routes
| - Guest/Public routes
| - Visitor Panel routes (public users)
| - Admin Panel routes (staff/management)
|
*/

// Public welcome page with parking location overview
Route::get('/', [VisitorController::class, 'welcome'])->name('welcome');

// Default dashboard redirect (detect user type and redirect appropriately)
Route::get('/home', [VisitorController::class, 'redirectToDashboard'])->middleware('auth')->name('home');

// Visitor Panel Routes
require __DIR__.'/visitor.php';

// Language switching
Route::post('/language/{locale}', [VisitorController::class, 'switchLanguage'])->name('language.switch');

// Authentication routes (Laravel Breeze/Fortify handles these)
require __DIR__.'/auth.php';

// Gate operations routes
require __DIR__.'/gate.php';

// Legacy User dashboard routes (for backward compatibility)
// TODO: Migrate existing users to visitor routes gradually
Route::middleware(['auth', 'set.language'])->group(function () {

    // Legacy Dashboard (redirect to visitor dashboard for regular users)
    Route::get('/dashboard', [UserDashboardController::class, 'legacyDashboard'])
        ->name('dashboard.index');

    Route::get('/user/dashboard', [UserDashboardController::class, 'legacyDashboard'])
        ->name('user.dashboard');

    // Vehicle management
    Route::prefix('dashboard/vehicles')->name('vehicles.')->group(function () {
        Route::get('/', [UserVehicleController::class, 'index'])->name('index');
        Route::get('/create', [UserVehicleController::class, 'create'])->name('create');
        Route::post('/', [UserVehicleController::class, 'store'])->name('store');
        Route::get('/{vehicle}', [UserVehicleController::class, 'show'])->name('show');
        Route::get('/{vehicle}/edit', [UserVehicleController::class, 'edit'])->name('edit');
        Route::put('/{vehicle}', [UserVehicleController::class, 'update'])->name('update');
        Route::delete('/{vehicle}', [UserVehicleController::class, 'destroy'])->name('destroy');
        Route::post('/{vehicle}/documents', [UserVehicleController::class, 'uploadDocuments'])->name('upload.documents');
    });

    // Booking management
    Route::prefix('dashboard/bookings')->name('bookings.')->group(function () {
        Route::get('/', [UserBookingController::class, 'index'])->name('index');
        Route::get('/create', [UserBookingController::class, 'create'])->name('create');
        Route::post('/', [UserBookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [UserBookingController::class, 'show'])->name('show');
        Route::put('/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('cancel');
        Route::put('/{booking}/extend', [UserBookingController::class, 'extend'])->name('extend');

        // AJAX routes for dynamic content
        Route::get('/slots/available', [UserBookingController::class, 'getAvailableSlots'])->name('slots.available');
        Route::post('/calculate-cost', [UserBookingController::class, 'calculateCost'])->name('calculate.cost');
    });

    // Payment management
    Route::prefix('dashboard/payments')->name('payments.')->group(function () {
        Route::get('/', [UserPaymentController::class, 'index'])->name('index');
        Route::get('/booking/{booking}', [UserPaymentController::class, 'create'])->name('create');
        Route::post('/booking/{booking}', [UserPaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [UserPaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/receipt', [UserPaymentController::class, 'receipt'])->name('receipt');
    });

    // Payment gateway callbacks (no auth required)
    Route::prefix('payments')->name('payments.gateway.')->group(function () {
        Route::get('/success', [UserPaymentController::class, 'success'])->name('success');
        Route::get('/failure', [UserPaymentController::class, 'failure'])->name('failure');
        Route::get('/cancel', [UserPaymentController::class, 'cancel'])->name('cancel');
    });

    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserDashboardController::class, 'profile'])->name('index');
        Route::put('/', [UserDashboardController::class, 'updateProfile'])->name('update');
        Route::put('/password', [UserDashboardController::class, 'updatePassword'])->name('password.update');
        Route::post('/avatar', [UserDashboardController::class, 'updateAvatar'])->name('avatar.update');
    });
});

// Admin routes with permission-based access
Route::middleware(['auth', 'set.language'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard - permission-based access
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('permission:admin.dashboard.view')
        ->name('dashboard.index');

    // Permission Management Routes
    Route::middleware('permission:manage_permissions')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [PermissionController::class, 'createPermission'])->name('permissions.store');
        Route::get('/permissions/users', [PermissionController::class, 'users'])->name('permissions.users');
        Route::post('/users/{user}/roles', [PermissionController::class, 'assignUserRole'])->name('users.roles.assign');
        Route::delete('/users/{user}/roles', [PermissionController::class, 'removeUserRole'])->name('users.roles.remove');
    });

    // Role Management Routes
    Route::middleware('permission:manage_roles')->group(function () {
        Route::get('/roles', [PermissionController::class, 'roles'])->name('permissions.roles');
        Route::post('/roles', [PermissionController::class, 'createRole'])->name('roles.store');
        Route::put('/roles/{role}', [PermissionController::class, 'updateRole'])->name('roles.update');
    });

    // Vehicle verification management
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/pending', [AdminDashboardController::class, 'pendingVehicles'])->name('pending');
        Route::post('/{vehicle}/verify', [AdminDashboardController::class, 'verifyVehicle'])->name('verify');
        Route::post('/{vehicle}/reject', [AdminDashboardController::class, 'rejectVehicle'])->name('reject');
    });

    // System management
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/health', [AdminDashboardController::class, 'systemHealth'])->name('health');
        Route::get('/logs', [AdminDashboardController::class, 'systemLogs'])->name('logs');
        Route::post('/cache/clear', [AdminDashboardController::class, 'clearCache'])->name('cache.clear');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'reports'])->name('index');
        Route::get('/revenue', [AdminDashboardController::class, 'revenueReport'])->name('revenue');
        Route::get('/bookings', [AdminDashboardController::class, 'bookingReport'])->name('bookings');
        Route::get('/users', [AdminDashboardController::class, 'userReport'])->name('users');
    });
});

// Public API documentation (optional)
Route::get('/api/docs', function () {
    return view('api.documentation');
})->name('api.docs');

// Include visitor routes
require __DIR__.'/visitor.php';
