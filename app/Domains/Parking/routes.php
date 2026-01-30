<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Parking\Controllers\ParkingAreaController;
use App\Domains\Parking\Controllers\ParkingSlotController;

// Parking area routes
Route::prefix('api/parking-areas')->group(function () {
    Route::get('/', [ParkingAreaController::class, 'index']);
    Route::get('/{parkingArea}', [ParkingAreaController::class, 'show']);
    Route::get('/{parkingArea}/slots', [ParkingAreaController::class, 'getSlots']);
    Route::get('/{parkingArea}/availability', [ParkingAreaController::class, 'checkAvailability']);
    Route::get('/{parkingArea}/pricing', [ParkingAreaController::class, 'getPricing']);
});

// Admin-only parking area management
Route::prefix('api/admin/parking-areas')->middleware(['auth:sanctum', 'role:admin', 'audit.log'])->group(function () {
    Route::post('/', [ParkingAreaController::class, 'store']);
    Route::put('/{parkingArea}', [ParkingAreaController::class, 'update']);
    Route::delete('/{parkingArea}', [ParkingAreaController::class, 'destroy']);
    Route::post('/{parkingArea}/upload-image', [ParkingAreaController::class, 'uploadImage']);
});

// Parking slot routes
Route::prefix('api/parking-slots')->group(function () {
    Route::get('/', [ParkingSlotController::class, 'index']);
    Route::get('/available', [ParkingSlotController::class, 'getAvailableSlots']);
    Route::get('/{parkingSlot}', [ParkingSlotController::class, 'show']);
    Route::get('/{parkingSlot}/availability', [ParkingSlotController::class, 'checkSlotAvailability']);
});

// Admin-only parking slot management
Route::prefix('api/admin/parking-slots')->middleware(['auth:sanctum', 'role:admin', 'audit.log'])->group(function () {
    Route::post('/', [ParkingSlotController::class, 'store']);
    Route::put('/{parkingSlot}', [ParkingSlotController::class, 'update']);
    Route::delete('/{parkingSlot}', [ParkingSlotController::class, 'destroy']);
    Route::post('/{parkingSlot}/toggle-status', [ParkingSlotController::class, 'toggleStatus']);
    Route::post('/bulk-create', [ParkingSlotController::class, 'bulkCreate']);
});

// Real-time availability
Route::prefix('api/parking')->group(function () {
    Route::get('/search', [ParkingAreaController::class, 'searchNearbyParking']);
    Route::get('/live-availability', [ParkingAreaController::class, 'getLiveAvailability']);
    Route::get('/occupancy-stats', [ParkingAreaController::class, 'getOccupancyStats']);
});
