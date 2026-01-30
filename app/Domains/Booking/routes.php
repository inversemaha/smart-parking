<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Booking\Controllers\BookingController;
use App\Domains\Booking\Controllers\VehicleEntryController;
use App\Domains\Booking\Controllers\VehicleExitController;

// Booking management routes
Route::prefix('api/bookings')->middleware(['auth:sanctum', 'audit.log'])->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::post('/', [BookingController::class, 'store']);
    Route::get('/{booking}', [BookingController::class, 'show']);
    Route::put('/{booking}', [BookingController::class, 'update']);
    Route::delete('/{booking}', [BookingController::class, 'cancel']);

    // Booking actions
    Route::post('/{booking}/extend', [BookingController::class, 'extendBooking']);
    Route::post('/{booking}/confirm', [BookingController::class, 'confirmBooking']);
    Route::post('/{booking}/cancel', [BookingController::class, 'cancelBooking']);
    Route::get('/{booking}/qr-code', [BookingController::class, 'generateQrCode']);

    // Booking history and receipts
    Route::get('/{booking}/receipt', [BookingController::class, 'generateReceipt']);
    Route::get('/{booking}/timeline', [BookingController::class, 'getBookingTimeline']);
});

// Real-time booking management
Route::prefix('api/bookings')->middleware('auth:sanctum')->group(function () {
    Route::get('/active', [BookingController::class, 'getActiveBookings']);
    Route::get('/upcoming', [BookingController::class, 'getUpcomingBookings']);
    Route::get('/history', [BookingController::class, 'getBookingHistory']);
    Route::post('/check-availability', [BookingController::class, 'checkAvailability']);
    Route::post('/calculate-cost', [BookingController::class, 'calculateBookingCost']);
});

// Vehicle entry management
Route::prefix('api/vehicle-entries')->middleware(['auth:sanctum', 'audit.log'])->group(function () {
    Route::get('/', [VehicleEntryController::class, 'index']);
    Route::post('/', [VehicleEntryController::class, 'store']);
    Route::get('/{entry}', [VehicleEntryController::class, 'show']);
    Route::put('/{entry}', [VehicleEntryController::class, 'update']);

    // QR Code scanning
    Route::post('/scan-qr', [VehicleEntryController::class, 'scanQrCode']);
    Route::post('/manual-entry', [VehicleEntryController::class, 'manualEntry']);
});

// Vehicle exit management
Route::prefix('api/vehicle-exits')->middleware(['auth:sanctum', 'audit.log'])->group(function () {
    Route::get('/', [VehicleExitController::class, 'index']);
    Route::post('/', [VehicleExitController::class, 'store']);
    Route::get('/{exit}', [VehicleExitController::class, 'show']);

    // Exit processing
    Route::post('/process-exit', [VehicleExitController::class, 'processExit']);
    Route::post('/calculate-charges', [VehicleExitController::class, 'calculateExitCharges']);
    Route::post('/scan-exit-qr', [VehicleExitController::class, 'scanExitQrCode']);
});

// Admin booking management
Route::prefix('api/admin/bookings')->middleware(['auth:sanctum', 'role:admin', 'audit.log'])->group(function () {
    Route::get('/all', [BookingController::class, 'getAllBookings']);
    Route::get('/statistics', [BookingController::class, 'getBookingStatistics']);
    Route::post('/{booking}/force-cancel', [BookingController::class, 'forceCancel']);
    Route::post('/{booking}/override-time', [BookingController::class, 'overrideTime']);
});
