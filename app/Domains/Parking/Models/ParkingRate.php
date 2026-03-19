<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zone_id',
        'vehicle_type_id',
        'hourly_rate',
        'daily_rate',
        'peak_hour_rate',
        'off_peak_rate',
        'peak_hours_start',
        'peak_hours_end',
        'is_active',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'peak_hour_rate' => 'decimal:2',
        'off_peak_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parking zone this rate belongs to
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    /**
     * Get the vehicle type this rate applies to
     */
    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    /**
     * Calculate parking charge based on duration (hours) and whether it's peak hours
     *
     * @param float $duration Duration in hours
     * @param bool $isPeakHour Whether the parking is during peak hours
     * @return array Array containing ['base_rate' => float, 'applied_rate' => float, 'total_charge' => float]
     */
    public function calculateCharge(float $duration, bool $isPeakHour = false): array
    {
        $baseRate = (float) $this->hourly_rate;
        $appliedRate = $isPeakHour 
            ? (float) $this->peak_hour_rate ?? $baseRate 
            : (float) $this->off_peak_rate ?? $baseRate;

        $totalCharge = $appliedRate * $duration;

        return [
            'base_rate' => $baseRate,
            'applied_rate' => $appliedRate,
            'total_charge' => round($totalCharge, 2),
        ];
    }

    /**
     * Apply vehicle type rate multiplier to a base price
     *
     * @param float $basePrice Base price before multiplier
     * @return float Adjusted price after applying vehicle type multiplier
     */
    public function applyVehicleMultiplier(float $basePrice): float
    {
        if ($this->vehicleType) {
            return round($basePrice * (float) $this->vehicleType->rate_multiplier, 2);
        }
        return $basePrice;
    }

    /**
     * Get daily rate with fallback to hourly rate × 24
     */
    public function getDailyRateAttribute($value): float
    {
        if ($value) {
            return (float) $value;
        }
        // Fallback: 24 × hourly rate
        return round(((float) $this->hourly_rate) * 24, 2);
    }

    /**
     * Check if given time is within peak hours
     *
     * @param string $time Time in HH:MM format
     * @return bool True if time is within peak hours
     */
    public function isWithinPeakHours(string $time): bool
    {
        if (!$this->peak_hours_start || !$this->peak_hours_end) {
            return false;
        }

        $currentTime = strtotime($time);
        $peakStart = strtotime($this->peak_hours_start);
        $peakEnd = strtotime($this->peak_hours_end);

        if ($peakStart > $peakEnd) {
            // Peak hours span midnight (e.g., 22:00 to 06:00)
            return $currentTime >= $peakStart || $currentTime < $peakEnd;
        }

        return $currentTime >= $peakStart && $currentTime < $peakEnd;
    }

    /**
     * Scope: Get only active rates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get rates for a specific zone
     */
    public function scopeForZone($query, $zoneId)
    {
        return $query->where('zone_id', $zoneId);
    }

    /**
     * Scope: Get rates for a specific vehicle type
     */
    public function scopeForVehicleType($query, $vehicleTypeId)
    {
        return $query->where('vehicle_type_id', $vehicleTypeId);
    }
}
