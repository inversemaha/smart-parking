<?php

namespace App\Shared\DTOs;

use Carbon\Carbon;

class BookingDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $userId,
        public readonly int $vehicleId,
        public readonly int $parkingLocationId,
        public readonly ?int $parkingSlotId,
        public readonly Carbon $startTime,
        public readonly Carbon $endTime,
        public readonly float $durationHours,
        public readonly float $hourlyRate,
        public readonly float $totalAmount,
        public readonly string $status = 'pending',
        public readonly string $paymentStatus = 'pending',
        public readonly ?string $notes = null
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: $data['user_id'],
            vehicleId: $data['vehicle_id'],
            parkingLocationId: $data['parking_location_id'],
            parkingSlotId: $data['parking_slot_id'] ?? null,
            startTime: Carbon::parse($data['start_time']),
            endTime: Carbon::parse($data['end_time']),
            durationHours: $data['duration_hours'] ?? 0,
            hourlyRate: $data['hourly_rate'] ?? 0,
            totalAmount: $data['total_amount'] ?? 0,
            status: $data['status'] ?? 'pending',
            paymentStatus: $data['payment_status'] ?? 'pending',
            notes: $data['notes'] ?? null
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'vehicle_id' => $this->vehicleId,
            'parking_location_id' => $this->parkingLocationId,
            'parking_slot_id' => $this->parkingSlotId,
            'start_time' => $this->startTime->toDateTimeString(),
            'end_time' => $this->endTime->toDateTimeString(),
            'duration_hours' => $this->durationHours,
            'hourly_rate' => $this->hourlyRate,
            'total_amount' => $this->totalAmount,
            'status' => $this->status,
            'payment_status' => $this->paymentStatus,
            'notes' => $this->notes,
        ];
    }
}
