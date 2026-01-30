<?php

namespace App\Shared\DTOs;

class PaymentDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $bookingId,
        public readonly int $userId,
        public readonly float $amount,
        public readonly string $currency = 'BDT',
        public readonly string $gateway = 'sslcommerz',
        public readonly string $transactionId,
        public readonly ?string $gatewayTransactionId = null,
        public readonly string $status = 'pending',
        public readonly ?array $gatewayResponse = null,
        public readonly ?string $paymentUrl = null
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            bookingId: $data['booking_id'],
            userId: $data['user_id'],
            amount: $data['amount'],
            currency: $data['currency'] ?? 'BDT',
            gateway: $data['gateway'] ?? 'sslcommerz',
            transactionId: $data['transaction_id'],
            gatewayTransactionId: $data['gateway_transaction_id'] ?? null,
            status: $data['status'] ?? 'pending',
            gatewayResponse: $data['gateway_response'] ?? null,
            paymentUrl: $data['payment_url'] ?? null
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->bookingId,
            'user_id' => $this->userId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'gateway' => $this->gateway,
            'transaction_id' => $this->transactionId,
            'gateway_transaction_id' => $this->gatewayTransactionId,
            'status' => $this->status,
            'gateway_response' => $this->gatewayResponse,
            'payment_url' => $this->paymentUrl,
        ];
    }
}
