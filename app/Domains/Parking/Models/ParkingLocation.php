<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingLocation extends Model
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
            'operating_hours' => 'array',
            'vehicle_types' => 'array',
            'amenities' => 'array',
            'is_active' => 'boolean',
            'opened_at' => 'datetime',
        ];
    }

    /**
     * Parking slots relationship.
     */
    public function parkingSlots()
    {
        return $this->hasMany(ParkingSlot::class);
    }

    /**
     * Active parking slots relationship.
     */
    public function activeSlots()
    {
        return $this->hasMany(ParkingSlot::class)->active();
    }

    /**
     * Available parking slots relationship.
     */
    public function availableSlots()
    {
        return $this->hasMany(ParkingSlot::class)->where('status', 'available');
    }

    /**
     * Bookings relationship.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Vehicle entries relationship.
     */
    public function vehicleEntries()
    {
        return $this->hasMany(VehicleEntry::class);
    }

    /**
     * Vehicle exits relationship.
     */
    public function vehicleExits()
    {
        return $this->hasMany(VehicleExit::class);
    }

    /**
     * Scope active locations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get available slot count.
     */
    public function getAvailableSlotsCountAttribute()
    {
        return $this->parkingSlots()->where('status', 'available')->count();
    }

    /**
     * Get occupied slot count.
     */
    public function getOccupiedSlotsCountAttribute()
    {
        return $this->parkingSlots()->where('status', 'occupied')->count();
    }

    /**
     * Check if location is open at specific time.
     */
    public function isOpenAt($dateTime = null)
    {
        $dateTime = $dateTime ?: now();
        $dayOfWeek = strtolower($dateTime->format('l'));

        if (!isset($this->operating_hours[$dayOfWeek])) {
            return false;
        }

        $hours = $this->operating_hours[$dayOfWeek];
        if (!isset($hours['open']) || !isset($hours['close'])) {
            return false;
        }

        $currentTime = $dateTime->format('H:i');
        return $currentTime >= $hours['open'] && $currentTime <= $hours['close'];
    }
}
