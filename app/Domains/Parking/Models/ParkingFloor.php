<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingFloor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zone_id',
        'floor_number',
        'floor_name',
        'description',
        'total_capacity',
        'current_occupancy',
        'hourly_rate',
        'daily_rate',
        'is_active',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the zone this floor belongs to
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    /**
     * Get parking slots on this floor (from parking_slots table)
     */
    public function slots(): HasMany
    {
        return $this->hasMany(ParkingSlot::class, 'floor_id');
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

    /**
     * Scope: Get only active floors
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get floors by zone
     */
    public function scopeByZone($query, $zoneId)
    {
        return $query->where('zone_id', $zoneId);
    }
}
