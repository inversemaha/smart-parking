<?php

use Illuminate\Support\Facades\Route;
use App\Domains\User\Controllers\DashboardController as UserDashboardController;
use App\Domains\User\Controllers\VehicleController as UserVehicleController;
use App\Domains\User\Controllers\BookingController as UserBookingController;
use App\Domains\User\Controllers\PaymentController as UserPaymentController;
use App\Domains\Admin\Controllers\PermissionController;
use App\Domains\Admin\Controllers\AdminDashboardController;
use App\Domains\Admin\Controllers\AdminVehicleController;
use App\Domains\Admin\Controllers\AdminParkingLocationController;
use App\Domains\Admin\Controllers\AdminBookingController;
use App\Domains\Admin\Controllers\AdminUserController;
use App\Domains\Admin\Controllers\AdminPaymentController;
use App\Domains\Admin\Controllers\AdminInvoiceController;
use App\Domains\Admin\Controllers\SystemSettingsController;
use App\Domains\Admin\Controllers\ProfileController;
use App\Domains\User\Controllers\VisitorController;
use App\Domains\Parking\Controllers\ParkingZoneController;
use App\Domains\Parking\Controllers\ParkingFloorController;
use App\Domains\Parking\Controllers\VehicleTypeController;
use App\Domains\Parking\Controllers\ParkingRateController;
use App\Domains\Parking\Controllers\ParkingGateController;
use App\Domains\Parking\Controllers\ParkingQrCodeController;

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

// Language switching
Route::post('/language/{locale}', [VisitorController::class, 'switchLanguage'])->name('language.switch');

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

    // User Management CRUD
    Route::middleware('permission:manage_users')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::post('/{user}/suspend', [AdminUserController::class, 'suspend'])->name('suspend');
        Route::post('/{user}/activate', [AdminUserController::class, 'activate'])->name('activate');
    });

    // Payment Management CRUD
    Route::middleware('permission:manage_payments')->prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
        Route::get('/{payment}', [AdminPaymentController::class, 'show'])->name('show');
        Route::post('/{payment}/refund', [AdminPaymentController::class, 'refund'])->name('refund');
    });

    // Invoice Management CRUD
    Route::middleware('permission:manage_invoices')->prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [AdminInvoiceController::class, 'index'])->name('index');
        Route::get('/{invoice}', [AdminInvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/download', [AdminInvoiceController::class, 'download'])->name('download');
        Route::post('/{invoice}/mark-paid', [AdminInvoiceController::class, 'markPaid'])->name('mark-paid');
    });

    // Booking Management CRUD
    Route::middleware('permission:manage_bookings')->prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [AdminBookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [AdminBookingController::class, 'show'])->name('show');
        Route::post('/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('confirm');
        Route::post('/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('cancel');
    });

    // Parking Location Management CRUD
    Route::middleware('permission:manage_parking')->prefix('parking-locations')->name('parking-locations.')->group(function () {
        Route::get('/', [AdminParkingLocationController::class, 'index'])->name('index');
        Route::get('/create', [AdminParkingLocationController::class, 'create'])->name('create');
        Route::post('/', [AdminParkingLocationController::class, 'store'])->name('store');
        Route::get('/{parkingLocation}', [AdminParkingLocationController::class, 'show'])->name('show');
        Route::get('/{parkingLocation}/edit', [AdminParkingLocationController::class, 'edit'])->name('edit');
        Route::put('/{parkingLocation}', [AdminParkingLocationController::class, 'update'])->name('update');
        Route::delete('/{parkingLocation}', [AdminParkingLocationController::class, 'destroy'])->name('destroy');
    });

    // Phase 1: Parking Zones Management CRUD
    Route::middleware('permission:manage_parking')->prefix('parking-zones')->name('parking-zones.')->group(function () {
        Route::get('/', [ParkingZoneController::class, 'index'])->name('index');
        Route::get('/create', [ParkingZoneController::class, 'create'])->name('create');
        Route::post('/', [ParkingZoneController::class, 'store'])->name('store');
        Route::get('/{zone}', [ParkingZoneController::class, 'show'])->name('show');
        Route::get('/{zone}/edit', [ParkingZoneController::class, 'edit'])->name('edit');
        Route::put('/{zone}', [ParkingZoneController::class, 'update'])->name('update');
        Route::delete('/{zone}', [ParkingZoneController::class, 'destroy'])->name('destroy');
        Route::post('/{zone}/restore', [ParkingZoneController::class, 'restore'])->name('restore');
    });

    // Phase 1: Parking Floors Management CRUD
    Route::middleware('permission:manage_parking')->prefix('parking-floors')->name('parking-floors.')->group(function () {
        Route::get('/', [ParkingFloorController::class, 'index'])->name('index');
        Route::get('/create', [ParkingFloorController::class, 'create'])->name('create');
        Route::post('/', [ParkingFloorController::class, 'store'])->name('store');
        Route::get('/{floor}', [ParkingFloorController::class, 'show'])->name('show');
        Route::get('/{floor}/edit', [ParkingFloorController::class, 'edit'])->name('edit');
        Route::put('/{floor}', [ParkingFloorController::class, 'update'])->name('update');
        Route::delete('/{floor}', [ParkingFloorController::class, 'destroy'])->name('destroy');
        Route::post('/{floor}/restore', [ParkingFloorController::class, 'restore'])->name('restore');
    });

    // Phase 1: Vehicle Types Management CRUD
    Route::middleware('permission:manage_parking')->prefix('vehicle-types')->name('vehicle-types.')->group(function () {
        Route::get('/', [VehicleTypeController::class, 'index'])->name('index');
        Route::get('/create', [VehicleTypeController::class, 'create'])->name('create');
        Route::post('/', [VehicleTypeController::class, 'store'])->name('store');
        Route::get('/{vehicleType}', [VehicleTypeController::class, 'show'])->name('show');
        Route::get('/{vehicleType}/edit', [VehicleTypeController::class, 'edit'])->name('edit');
        Route::put('/{vehicleType}', [VehicleTypeController::class, 'update'])->name('update');
        Route::delete('/{vehicleType}', [VehicleTypeController::class, 'destroy'])->name('destroy');
        Route::post('/{vehicleType}/restore', [VehicleTypeController::class, 'restore'])->name('restore');
        Route::post('/bulk/order', [VehicleTypeController::class, 'updateOrder'])->name('bulk-order');
    });

    // Phase 1: Parking Rates Management CRUD
    Route::middleware('permission:manage_parking')->prefix('parking-rates')->name('parking-rates.')->group(function () {
        Route::get('/', [ParkingRateController::class, 'index'])->name('index');
        Route::get('/create', [ParkingRateController::class, 'create'])->name('create');
        Route::post('/', [ParkingRateController::class, 'store'])->name('store');
        Route::get('/{rate}', [ParkingRateController::class, 'show'])->name('show');
        Route::get('/{rate}/edit', [ParkingRateController::class, 'edit'])->name('edit');
        Route::put('/{rate}', [ParkingRateController::class, 'update'])->name('update');
        Route::delete('/{rate}', [ParkingRateController::class, 'destroy'])->name('destroy');
        Route::post('/{rate}/restore', [ParkingRateController::class, 'restore'])->name('restore');
        Route::get('/zone/{zone}/matrix', [ParkingRateController::class, 'matrixByZone'])->name('matrix-zone');
        Route::get('/import', [ParkingRateController::class, 'importForm'])->name('import-form');
        Route::post('/import', [ParkingRateController::class, 'import'])->name('import');
    });

    // Phase 2: Parking Gates Management CRUD
    Route::middleware('permission:manage_parking')->prefix('parking-gates')->name('parking-gates.')->group(function () {
        Route::get('/', [ParkingGateController::class, 'index'])->name('index');
        Route::get('/create', [ParkingGateController::class, 'create'])->name('create');
        Route::post('/', [ParkingGateController::class, 'store'])->name('store');
        Route::get('/{gate}', [ParkingGateController::class, 'show'])->name('show');
        Route::get('/{gate}/edit', [ParkingGateController::class, 'edit'])->name('edit');
        Route::put('/{gate}', [ParkingGateController::class, 'update'])->name('update');
        Route::delete('/{gate}', [ParkingGateController::class, 'destroy'])->name('destroy');
        Route::post('/{gate}/restore', [ParkingGateController::class, 'restore'])->name('restore');
        Route::patch('/{gate}/status', [ParkingGateController::class, 'updateStatus'])->name('status');
        Route::get('/{gate}/access-logs', [ParkingGateController::class, 'accessLogs'])->name('access-logs');
    });

    // Phase 2: QR Code Management CRUD & Generation
    Route::middleware('permission:manage_parking')->prefix('parking-qr-codes')->name('parking-qr-codes.')->group(function () {
        Route::get('/', [ParkingQrCodeController::class, 'index'])->name('index');
        Route::get('/create', [ParkingQrCodeController::class, 'create'])->name('create');
        Route::post('/', [ParkingQrCodeController::class, 'store'])->name('store');
        Route::get('/{qrCode}', [ParkingQrCodeController::class, 'show'])->name('show');
        Route::get('/{qrCode}/edit', [ParkingQrCodeController::class, 'edit'])->name('edit');
        Route::put('/{qrCode}', [ParkingQrCodeController::class, 'update'])->name('update');
        Route::delete('/{qrCode}', [ParkingQrCodeController::class, 'destroy'])->name('destroy');
        Route::post('/{qrCode}/restore', [ParkingQrCodeController::class, 'restore'])->name('restore');
        Route::get('/{qrCode}/download', [ParkingQrCodeController::class, 'download'])->name('download');
        Route::post('/bulk/generate-for-zone', [ParkingQrCodeController::class, 'bulkGenerateForZone'])->name('bulk-generate');
        Route::get('/stats/statistics', [ParkingQrCodeController::class, 'statistics'])->name('statistics');
    });

    // Vehicle Management CRUD
    Route::middleware('permission:manage_vehicles')->prefix('vehicles')->name('vehicles.')->group(function () {
        // CRUD operations
        Route::get('/', [AdminVehicleController::class, 'index'])->name('index');
        Route::get('/create', [AdminVehicleController::class, 'create'])->name('create');
        Route::post('/', [AdminVehicleController::class, 'store'])->name('store');
        Route::get('/{vehicle}', [AdminVehicleController::class, 'show'])->name('show');
        Route::get('/{vehicle}/edit', [AdminVehicleController::class, 'edit'])->name('edit');
        Route::put('/{vehicle}', [AdminVehicleController::class, 'update'])->name('update');
        Route::delete('/{vehicle}', [AdminVehicleController::class, 'destroy'])->name('destroy');
        
        // Verification management
        Route::middleware('permission:verify_vehicles')->group(function () {
            Route::get('/pending', [AdminDashboardController::class, 'pendingVehicles'])->name('pending');
            Route::post('/{vehicle}/verify', [AdminDashboardController::class, 'verifyVehicle'])->name('verify');
            Route::post('/{vehicle}/reject', [AdminDashboardController::class, 'rejectVehicle'])->name('reject');
        });
    });

    // User Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::post('/logout-all', [ProfileController::class, 'logoutAll'])->name('logout-all');
        Route::delete('/', [ProfileController::class, 'deleteAccount'])->name('delete-account');
    });

    // System Settings Routes
    Route::middleware('permission:manage_settings')->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SystemSettingsController::class, 'index'])->name('index');
        Route::put('/', [SystemSettingsController::class, 'update'])->name('update');
    });

    // System management
    Route::middleware('permission:view_logs')->prefix('system')->name('system.')->group(function () {
        Route::get('/health', [AdminDashboardController::class, 'systemHealth'])->name('health');
        Route::get('/logs', [AdminDashboardController::class, 'systemLogs'])->name('logs');
        Route::middleware('permission:manage_settings')->post('/cache/clear', [AdminDashboardController::class, 'clearCache'])->name('cache.clear');
    });

    // Reports
    Route::middleware('permission:view_reports')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'reports'])->name('index');
        Route::middleware('permission:view_financial_reports')->get('/revenue', [AdminDashboardController::class, 'revenueReport'])->name('revenue');
        Route::get('/bookings', [AdminDashboardController::class, 'bookingReport'])->name('bookings');
        Route::get('/users', [AdminDashboardController::class, 'userReport'])->name('users');
    });
});

require __DIR__.'/auth.php';
require __DIR__.'/visitor.php';
require __DIR__.'/gate.php';

// Public API documentation (optional)
Route::get('/api/docs', function () {
    return view('api.documentation');
})->name('api.docs');
