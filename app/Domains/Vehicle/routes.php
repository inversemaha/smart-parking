<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Vehicle\Controllers\VehicleController;

// Vehicle management routes
Route::prefix('api/vehicles')->middleware(['auth:sanctum', 'audit.log'])->group(function () {
    Route::get('/', [VehicleController::class, 'index']);
    Route::post('/', [VehicleController::class, 'store']);
    Route::get('/{vehicle}', [VehicleController::class, 'show']);
    Route::put('/{vehicle}', [VehicleController::class, 'update']);
    Route::delete('/{vehicle}', [VehicleController::class, 'destroy']);

    // Vehicle verification
    Route::post('/{vehicle}/verify', [VehicleController::class, 'verifyVehicle']);
    Route::get('/{vehicle}/verification-status', [VehicleController::class, 'getVerificationStatus']);

    // Vehicle documents
    Route::post('/{vehicle}/documents', [VehicleController::class, 'uploadDocuments']);
    Route::get('/{vehicle}/documents', [VehicleController::class, 'getDocuments']);
    Route::delete('/{vehicle}/documents/{document}', [VehicleController::class, 'deleteDocument']);

    // Vehicle history
    Route::get('/{vehicle}/bookings', [VehicleController::class, 'getVehicleBookings']);
    Route::get('/{vehicle}/entries', [VehicleController::class, 'getVehicleEntries']);
    Route::get('/{vehicle}/payments', [VehicleController::class, 'getVehiclePayments']);
});

// BRTA Integration
Route::prefix('api/brta')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/vehicle/{registrationNumber}', [VehicleController::class, 'getBrtaData']);
    Route::post('/verify/{vehicle}', [VehicleController::class, 'verifyWithBrta']);
});

// Vehicle types and categories
Route::prefix('api/vehicle-types')->group(function () {
    Route::get('/', [VehicleController::class, 'getVehicleTypes']);
    Route::get('/categories', [VehicleController::class, 'getVehicleCategories']);
});
