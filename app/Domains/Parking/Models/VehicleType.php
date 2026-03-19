<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'width',
        'height',
        'length',
        'rate_multiplier',
        'icon_url',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'length' => 'decimal:2',
        'rate_multiplier' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get parking rates for this vehicle type
     */
    public function rates(): HasMany
    {
        return $this->hasMany(ParkingRate::class, 'vehicle_type_id');
    }

    /**
     * Calculate volume (width × height × length)
     */
    public function getVolumeAttribute(): float
    {
        return (float) ($this->width * $this->height * $this->length);
    }

    /**
     * Get rate multiplier as percentage
     */
    public function getRateMultiplierPercentageAttribute(): float
    {
        return ((float) $this->rate_multiplier - 1.0) * 100;
    }

    /**
     * Scope: Get only active vehicle types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }
}
