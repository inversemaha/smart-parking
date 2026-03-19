<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingZone extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'building_id',
        'description',
        'location',
        'latitude',
        'longitude',
        'total_capacity',
        'current_occupancy',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all floors in this zone
     */
    public function floors(): HasMany
    {
        return $this->hasMany(ParkingFloor::class, 'zone_id');
    }

    /**
     * Get all parking rates for this zone
     */
    public function rates(): HasMany
    {
        return $this->hasMany(ParkingRate::class, 'zone_id');
    }

    /**
     * Get all parking gates in this zone
     */
    public function gates(): HasMany
    {
        return $this->hasMany(ParkingGate::class, 'zone_id');
    }

    /**
     * Get all QR codes for this zone
     */
    public function qrCodes(): HasMany
    {
        return $this->hasMany(ParkingQrCode::class, 'zone_id');
    }

    /**
     * Get all parking sessions in this zone
     */
    public function parkingSessions(): HasMany
    {
        return $this->hasMany(ParkingSession::class, 'zone_id');
    }

    /**
     * Calculate occupancy percentage
     */
    public function occupancyPercentage(): float
    {
        if ($this->total_capacity === 0) {
            return 0;
        }
        return round(($this->current_occupancy / $this->total_capacity) * 100, 2);
    }

    /**
     * Get available slots count
     */
    public function getAvailableSlotsAttribute(): int
    {
        return $this->total_capacity - $this->current_occupancy;
    }
}
