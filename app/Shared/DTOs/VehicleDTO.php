<?php

namespace App\Shared\DTOs;

class VehicleDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $userId,
        public readonly string $registrationNumber,
        public readonly string $vehicleType,
        public readonly string $brand,
        public readonly string $model,
        public readonly int $year,
        public readonly string $color,
        public readonly ?string $chassisNumber = null,
        public readonly ?string $engineNumber = null,
        public readonly string $status = 'active',
        public readonly string $verificationStatus = 'pending',
        public readonly ?string $verificationNotes = null
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: $data['user_id'],
            registrationNumber: $data['registration_number'],
            vehicleType: $data['vehicle_type'],
            brand: $data['brand'],
            model: $data['model'],
            year: $data['year'],
            color: $data['color'],
            chassisNumber: $data['chassis_number'] ?? null,
            engineNumber: $data['engine_number'] ?? null,
            status: $data['status'] ?? 'active',
            verificationStatus: $data['verification_status'] ?? 'pending',
            verificationNotes: $data['verification_notes'] ?? null
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
            'registration_number' => $this->registrationNumber,
            'vehicle_type' => $this->vehicleType,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'color' => $this->color,
            'chassis_number' => $this->chassisNumber,
            'engine_number' => $this->engineNumber,
            'status' => $this->status,
            'verification_status' => $this->verificationStatus,
            'verification_notes' => $this->verificationNotes,
        ];
    }
}
