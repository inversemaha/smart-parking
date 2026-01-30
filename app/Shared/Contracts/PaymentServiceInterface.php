<?php

namespace App\Shared\Contracts;

use App\Models\Payment;
use App\Models\Booking;

interface PaymentServiceInterface extends ServiceInterface
{
    /**
     * Initiate payment.
     */
    public function initiatePayment(Booking $booking, array $data): ?Payment;

    /**
     * Process payment success.
     */
    public function processPaymentSuccess(array $gatewayData): bool;

    /**
     * Process payment failure.
     */
    public function processPaymentFailure(array $gatewayData): bool;

    /**
     * Process refund.
     */
    public function processRefund(Booking $booking): bool;
}
