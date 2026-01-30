<?php

use Illuminate\Support\Facades\Route;
use App\Domains\User\Controllers\UserController;
use App\Domains\User\Controllers\AuthController;

// Authentication routes
Route::prefix('api/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/refresh', [AuthController::class, 'refreshToken'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// User management routes
Route::prefix('api/users')->middleware(['auth:sanctum', 'audit.log'])->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
    Route::post('/{user}/avatar', [UserController::class, 'uploadAvatar']);
    Route::get('/{user}/vehicles', [UserController::class, 'getUserVehicles']);
    Route::get('/{user}/bookings', [UserController::class, 'getUserBookings']);
});

// Profile management routes
Route::prefix('api/profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserController::class, 'profile']);
    Route::put('/', [UserController::class, 'updateProfile']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post('/upload-documents', [UserController::class, 'uploadDocuments']);
    Route::get('/notifications', [UserController::class, 'getNotifications']);
    Route::put('/notifications/{notification}/read', [UserController::class, 'markNotificationAsRead']);
    Route::post('/language', [UserController::class, 'changeLanguage']);
});
