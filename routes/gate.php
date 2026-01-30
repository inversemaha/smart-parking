<?php

use App\Domains\Gate\Controllers\GateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Gate Management Routes
|--------------------------------------------------------------------------
|
| Gate operation routes for vehicle entry/exit management.
| These routes require authentication and gate operation permissions.
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('gate')->name('gate.')->group(function () {
        // Vehicle entry/exit operations
        Route::post('entry', [GateController::class, 'vehicleEntry'])->name('entry');
        Route::post('exit', [GateController::class, 'vehicleExit'])->name('exit');

        // QR code scanning
        Route::post('scan', [GateController::class, 'scanQrCode'])->name('scan');

        // Gate operation logs
        Route::get('logs', [GateController::class, 'getGateLogs'])->name('logs');
    });

    // Web routes for gate operations (for admin panel)
    Route::middleware(['auth', 'can:operate.gates'])->prefix('admin/gates')->name('admin.gates.')->group(function () {
        Route::get('/', [GateController::class, 'index'])->name('index');
        Route::get('entries', [GateController::class, 'entries'])->name('entries');
        Route::get('exits', [GateController::class, 'exits'])->name('exits');
    });

    Route::middleware(['auth', 'can:view.gate.logs'])->prefix('admin/gate-entries')->name('admin.gate-entries.')->group(function () {
        Route::get('/', [GateController::class, 'entryLogs'])->name('index');
    });

    Route::middleware(['auth', 'can:view.gate.logs'])->prefix('admin/gate-exits')->name('admin.gate-exits.')->group(function () {
        Route::get('/', [GateController::class, 'exitLogs'])->name('index');
    });
});
