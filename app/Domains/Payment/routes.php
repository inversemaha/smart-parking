<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Payment\Controllers\PaymentController;

// Payment processing routes
Route::prefix('api/payments')->middleware(['auth:sanctum', 'audit.log'])->group(function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::post('/initiate', [PaymentController::class, 'initiatePayment']);
    Route::get('/{payment}', [PaymentController::class, 'show']);
    Route::get('/{payment}/receipt', [PaymentController::class, 'downloadReceipt']);

    // Payment history
    Route::get('/user/history', [PaymentController::class, 'getUserPaymentHistory']);
    Route::get('/booking/{booking}', [PaymentController::class, 'getBookingPayments']);
});

// SSLCommerz payment gateway
Route::prefix('api/payments/sslcommerz')->group(function () {
    Route::post('/initiate', [PaymentController::class, 'initiateSslCommerzPayment']);
    Route::post('/success', [PaymentController::class, 'sslCommerzSuccess']);
    Route::post('/fail', [PaymentController::class, 'sslCommerzFail']);
    Route::post('/cancel', [PaymentController::class, 'sslCommerzCancel']);
    Route::post('/ipn', [PaymentController::class, 'sslCommerzIpn']); // Instant Payment Notification
});

// bKash payment gateway
Route::prefix('api/payments/bkash')->group(function () {
    Route::post('/initiate', [PaymentController::class, 'initiateBkashPayment']);
    Route::post('/callback', [PaymentController::class, 'bkashCallback']);
    Route::get('/token', [PaymentController::class, 'getBkashToken']);
});

// Nagad payment gateway
Route::prefix('api/payments/nagad')->group(function () {
    Route::post('/initiate', [PaymentController::class, 'initiateNagadPayment']);
    Route::post('/callback', [PaymentController::class, 'nagadCallback']);
});

// Rocket payment gateway
Route::prefix('api/payments/rocket')->group(function () {
    Route::post('/initiate', [PaymentController::class, 'initiateRocketPayment']);
    Route::post('/callback', [PaymentController::class, 'rocketCallback']);
});

// Payment verification and refunds
Route::prefix('api/payments')->middleware(['auth:sanctum', 'audit.log'])->group(function () {
    Route::post('/{payment}/verify', [PaymentController::class, 'verifyPayment']);
    Route::post('/{payment}/refund', [PaymentController::class, 'initiateRefund']);
    Route::get('/{payment}/status', [PaymentController::class, 'checkPaymentStatus']);
});

// Admin payment management
Route::prefix('api/admin/payments')->middleware(['auth:sanctum', 'role:admin', 'audit.log'])->group(function () {
    Route::get('/all', [PaymentController::class, 'getAllPayments']);
    Route::get('/statistics', [PaymentController::class, 'getPaymentStatistics']);
    Route::get('/transactions', [PaymentController::class, 'getTransactionReport']);
    Route::post('/{payment}/force-refund', [PaymentController::class, 'forceRefund']);
    Route::post('/{payment}/mark-as-paid', [PaymentController::class, 'markAsPaid']);
});

// Payment methods and gateways
Route::prefix('api/payment-methods')->group(function () {
    Route::get('/', [PaymentController::class, 'getAvailablePaymentMethods']);
    Route::get('/gateways', [PaymentController::class, 'getPaymentGateways']);
    Route::get('/fees', [PaymentController::class, 'getPaymentFees']);
});
