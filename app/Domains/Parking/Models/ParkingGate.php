<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingGate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zone_id',
        'floor_id',
        'name',
        'description',
        'gate_type',
        'gate_status',
        'location',
        'contact_person',
        'contact_phone',
        'camera_url',
        'device_settings',
        'is_active',
    ];

    protected $casts = [
        'device_settings' => 'json',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parking zone this gate belongs to
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    /**
     * Get the parking floor this gate serves (if floor-specific)
     */
    public function floor(): BelongsTo
    {
        return $this->belongsTo(ParkingFloor::class, 'floor_id');
    }

    /**
     * Get all access logs for this gate
     */
    public function accessLogs(): HasMany
    {
        return $this->hasMany(ParkingAccessLog::class, 'gate_id');
    }

    /**
     * Check if gate is currently operational
     */
    public function isOperational(): bool
    {
        return $this->gate_status === 'operational' && $this->is_active;
    }

    /**
     * Check if gate handles entries
     */
    public function handlesEntry(): bool
    {
        return in_array($this->gate_type, ['entry', 'bidirectional']);
    }

    /**
     * Check if gate handles exits
     */
    public function handlesExit(): bool
    {
        return in_array($this->gate_type, ['exit', 'bidirectional']);
    }

    /**
     * Get recent access attempts (last 24 hours)
     */
    public function recentAccessAttempts()
    {
        return $this->accessLogs()
            ->where('accessed_at', '>=', now()->subDay())
            ->orderBy('accessed_at', 'desc');
    }

    /**
     * Scope: Get only active gates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('gate_status', 'operational');
    }

    /**
     * Scope: Get gates by zone
     */
    public function scopeByZone($query, $zoneId)
    {
        return $query->where('zone_id', $zoneId);
    }

    /**
     * Scope: Get gates by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('gate_type', $type);
    }
}
