<?php

use Illuminate\Support\Facades\Route;
use App\Domains\User\Controllers\UserController;
use App\Domains\User\Controllers\AuthController;

// Authentication routes (for API)
Route::prefix('api/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.user.auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.user.auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('api.user.auth.logout');
    Route::post('/refresh', [AuthController::class, 'refreshToken'])->middleware('auth:sanctum')->name('api.user.auth.refresh');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum')->name('api.user.auth.me');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('api.user.auth.forgot-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('api.user.auth.reset-password');
});

// User management routes (Admin/Manager access)
Route::prefix('api/users')->middleware(['auth:sanctum', 'audit.log', 'role:admin,manager'])->name('api.users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::post('/{user}/avatar', [UserController::class, 'uploadAvatar'])->name('upload-avatar');
    Route::get('/{user}/vehicles', [UserController::class, 'getUserVehicles'])->name('vehicles');
    Route::get('/{user}/bookings', [UserController::class, 'getUserBookings'])->name('bookings');
});

// Profile management routes (Own profile access)
Route::prefix('api/profile')->middleware(['auth:sanctum', 'role.check'])->name('api.profile.')->group(function () {
    Route::get('/', [UserController::class, 'profile'])->name('index');
    Route::put('/', [UserController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('change-password');
    Route::post('/upload-documents', [UserController::class, 'uploadDocuments'])->name('upload-documents');
    Route::get('/notifications', [UserController::class, 'getNotifications'])->name('notifications');
    Route::put('/notifications/{notification}/read', [UserController::class, 'markNotificationAsRead'])->name('notification-read');
    Route::post('/language', [UserController::class, 'changeLanguage'])->name('change-language');
});
