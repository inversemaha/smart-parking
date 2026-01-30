<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Parking\Models\ParkingSlot;

class ParkingArea extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'address',
        'latitude',
        'longitude',
        'total_capacity',
        'hourly_rate',
        'daily_rate',
        'monthly_rate',
        'operating_hours',
        'vehicle_types',
        'amenities',
        'is_active',
        'opened_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'hourly_rate' => 'decimal:2',
            'daily_rate' => 'decimal:2',
            'monthly_rate' => 'decimal:2',
            'operating_hours' => 'array',
            'vehicle_types' => 'array',
            'amenities' => 'array',
            'is_active' => 'boolean',
            'opened_at' => 'datetime',
        ];
    }

    /**
     * Get the parking slots for this area.
     */
    public function slots()
    {
        return $this->hasMany(ParkingSlot::class);
    }

    /**
     * Get available slots.
     */
    public function availableSlots()
    {
        return $this->slots()->where('is_active', true)->where('status', 'available');
    }

    /**
     * Get occupied slots.
     */
    public function occupiedSlots()
    {
        return $this->slots()->where('status', 'occupied');
    }

    /**
     * Get maintenance slots.
     */
    public function maintenanceSlots()
    {
        return $this->slots()->where('status', 'maintenance');
    }

    /**
     * Get total available capacity.
     */
    public function getAvailableCapacityAttribute(): int
    {
        return $this->availableSlots()->count();
    }

    /**
     * Get occupied capacity.
     */
    public function getOccupiedCapacityAttribute(): int
    {
        return $this->occupiedSlots()->count();
    }

    /**
     * Get occupancy rate as percentage.
     */
    public function getOccupancyRateAttribute(): float
    {
        if ($this->total_capacity <= 0) {
            return 0;
        }

        return round(($this->occupied_capacity / $this->total_capacity) * 100, 2);
    }

    /**
     * Check if area is currently open.
     */
    public function isOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $operatingHours = $this->operating_hours;
        if (!$operatingHours) {
            return true; // 24/7 if no operating hours set
        }

        $now = now();
        $currentDay = strtolower($now->format('l'));
        $currentTime = $now->format('H:i');

        if (!isset($operatingHours[$currentDay])) {
            return false; // Closed today
        }

        $todayHours = $operatingHours[$currentDay];
        if ($todayHours === 'closed') {
            return false;
        }

        if ($todayHours === '24/7') {
            return true;
        }

        // Check if current time is within operating hours
        if (isset($todayHours['open']) && isset($todayHours['close'])) {
            return $currentTime >= $todayHours['open'] && $currentTime <= $todayHours['close'];
        }

        return false;
    }

    /**
     * Scope for active parking areas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for parking areas with available slots.
     */
    public function scopeWithAvailableSlots($query)
    {
        return $query->whereHas('slots', function ($q) {
            $q->where('is_active', true)->where('status', 'available');
        });
    }

    /**
     * Scope for searching by location.
     */
    public function scopeNearby($query, float $latitude, float $longitude, int $radiusKm = 10)
    {
        return $query->selectRaw("
            *, ( 6371 * acos( cos( radians(?) ) *
            cos( radians( latitude ) )
            * cos( radians( longitude ) - radians(?)
            ) + sin( radians(?) ) *
            sin( radians( latitude ) ) )
            ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having("distance", "<", $radiusKm)
        ->orderBy("distance");
    }

    /**
     * Scope for filtering by vehicle type.
     */
    public function scopeForVehicleType($query, string $vehicleType)
    {
        return $query->whereJsonContains('vehicle_types', $vehicleType);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    /**
     * Get formatted address.
     */
    public function getFormattedAddressAttribute(): string
    {
        return $this->address ?: $this->name;
    }

    /**
     * Get rate for specific duration type.
     */
    public function getRateForDuration(string $durationType): float
    {
        return match ($durationType) {
            'hourly' => $this->hourly_rate,
            'daily' => $this->daily_rate,
            'monthly' => $this->monthly_rate,
            default => $this->hourly_rate,
        };
    }
}
