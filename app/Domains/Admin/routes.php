<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Admin\Controllers\AdminController;
use App\Domains\Admin\Controllers\DashboardController;
use App\Domains\Admin\Controllers\ReportController;
use App\Domains\Admin\Controllers\AuditLogController;

// Admin dashboard routes
Route::prefix('api/admin/dashboard')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/statistics', [DashboardController::class, 'getStatistics']);
    Route::get('/recent-activities', [DashboardController::class, 'getRecentActivities']);
    Route::get('/revenue-stats', [DashboardController::class, 'getRevenueStats']);
    Route::get('/occupancy-stats', [DashboardController::class, 'getOccupancyStats']);
});

// User management
Route::prefix('api/admin/users')->middleware(['auth:sanctum', 'role:admin', 'audit.log'])->group(function () {
    Route::get('/', [AdminController::class, 'getUsers']);
    Route::get('/{user}', [AdminController::class, 'getUser']);
    Route::put('/{user}', [AdminController::class, 'updateUser']);
    Route::delete('/{user}', [AdminController::class, 'deleteUser']);
    Route::post('/{user}/suspend', [AdminController::class, 'suspendUser']);
    Route::post('/{user}/activate', [AdminController::class, 'activateUser']);
    Route::post('/{user}/roles', [AdminController::class, 'assignRoles']);
    Route::delete('/{user}/roles/{role}', [AdminController::class, 'removeRole']);
});

// Role and permission management
Route::prefix('api/admin/roles')->middleware(['auth:sanctum', 'role:admin', 'audit.log'])->group(function () {
    Route::get('/', [AdminController::class, 'getRoles']);
    Route::post('/', [AdminController::class, 'createRole']);
    Route::put('/{role}', [AdminController::class, 'updateRole']);
    Route::delete('/{role}', [AdminController::class, 'deleteRole']);
    Route::post('/{role}/permissions', [AdminController::class, 'assignPermissions']);
});

Route::prefix('api/admin/permissions')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'getPermissions']);
});

// Vehicle verification management
Route::prefix('api/admin/vehicles')->middleware(['auth:sanctum', 'role:admin', 'audit.log'])->group(function () {
    Route::get('/pending-verification', [AdminController::class, 'getPendingVehicles']);
    Route::post('/{vehicle}/verify', [AdminController::class, 'verifyVehicle']);
    Route::post('/{vehicle}/reject', [AdminController::class, 'rejectVehicle']);
    Route::get('/{vehicle}/documents', [AdminController::class, 'getVehicleDocuments']);
});

// System settings
Route::prefix('api/admin/settings')->middleware(['auth:sanctum', 'role:admin', 'audit.log'])->group(function () {
    Route::get('/', [AdminController::class, 'getSystemSettings']);
    Route::put('/', [AdminController::class, 'updateSystemSettings']);
    Route::get('/parking-rates', [AdminController::class, 'getParkingRates']);
    Route::put('/parking-rates', [AdminController::class, 'updateParkingRates']);
});

// Reports
Route::prefix('api/admin/reports')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/revenue', [ReportController::class, 'getRevenueReport']);
    Route::get('/bookings', [ReportController::class, 'getBookingReport']);
    Route::get('/vehicles', [ReportController::class, 'getVehicleReport']);
    Route::get('/users', [ReportController::class, 'getUserReport']);
    Route::get('/occupancy', [ReportController::class, 'getOccupancyReport']);
    Route::post('/export', [ReportController::class, 'exportReport']);
});

// Audit logs
Route::prefix('api/admin/audit-logs')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/', [AuditLogController::class, 'index']);
    Route::get('/{auditLog}', [AuditLogController::class, 'show']);
    Route::get('/user/{user}', [AuditLogController::class, 'getUserLogs']);
    Route::post('/export', [AuditLogController::class, 'export']);
});

// System monitoring
Route::prefix('api/admin/system')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/health', [AdminController::class, 'getSystemHealth']);
    Route::get('/queues', [AdminController::class, 'getQueueStatus']);
    Route::get('/cache', [AdminController::class, 'getCacheStatus']);
    Route::post('/cache/clear', [AdminController::class, 'clearCache']);
    Route::get('/logs', [AdminController::class, 'getSystemLogs']);
});

// Emergency operations
Route::prefix('api/admin/emergency')->middleware(['auth:sanctum', 'role:admin', 'audit.log'])->group(function () {
    Route::post('/force-exit-all', [AdminController::class, 'forceExitAllVehicles']);
    Route::post('/lock-system', [AdminController::class, 'lockSystem']);
    Route::post('/unlock-system', [AdminController::class, 'unlockSystem']);
    Route::post('/broadcast-message', [AdminController::class, 'broadcastMessage']);
});
