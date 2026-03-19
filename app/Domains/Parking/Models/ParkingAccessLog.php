<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingAccessLog extends Model
{
    protected $table = 'parking_access_logs';

    public $timestamps = true;

    protected $fillable = [
        'vehicle_id',
        'gate_id',
        'zone_id',
        'license_plate',
        'access_type',
        'access_status',
        'access_method',
        'denial_reason',
        'notes',
        'staff_member',
        'vehicle_type',
        'sensor_data',
        'accessed_at',
    ];

    protected $casts = [
        'sensor_data' => 'json',
        'accessed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the vehicle that accessed the gate
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Vehicle\Models\Vehicle::class, 'vehicle_id');
    }

    /**
     * Get the gate that was accessed
     */
    public function gate(): BelongsTo
    {
        return $this->belongsTo(ParkingGate::class, 'gate_id');
    }

    /**
     * Get the parking zone
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    /**
     * Check if access was granted
     */
    public function isAccessGranted(): bool
    {
        return $this->access_status === 'allowed';
    }

    /**
     * Check if access was denied
     */
    public function isAccessDenied(): bool
    {
        return $this->access_status === 'denied';
    }

    /**
     * Check if access is pending approval
     */
    public function isPending(): bool
    {
        return $this->access_status === 'pending';
    }

    /**
     * Check if access triggered an alert
     */
    public function isAlert(): bool
    {
        return $this->access_status === 'alert';
    }

    /**
     * Approve this access (admin action)
     */
    public function approve(string $staffMember, ?string $notes = null): void
    {
        $this->update([
            'access_status' => 'allowed',
            'staff_member' => $staffMember,
            'notes' => $notes ?? $this->notes,
        ]);
    }

    /**
     * Deny this access (admin action)
     */
    public function deny(string $reason, string $staffMember, ?string $notes = null): void
    {
        $this->update([
            'access_status' => 'denied',
            'denial_reason' => $reason,
            'staff_member' => $staffMember,
            'notes' => $notes ?? $this->notes,
        ]);
    }

    /**
     * Scope: Get entries only
     */
    public function scopeEntries($query)
    {
        return $query->where('access_type', 'entry');
    }

    /**
     * Scope: Get exits only
     */
    public function scopeExits($query)
    {
        return $query->where('access_type', 'exit');
    }

    /**
     * Scope: Get allowed accesses
     */
    public function scopeAllowed($query)
    {
        return $query->where('access_status', 'allowed');
    }

    /**
     * Scope: Get denied accesses
     */
    public function scopeDenied($query)
    {
        return $query->where('access_status', 'denied');
    }

    /**
     * Scope: Get pending approvals
     */
    public function scopePending($query)
    {
        return $query->where('access_status', 'pending');
    }

    /**
     * Scope: Get alerts
     */
    public function scopeAlerts($query)
    {
        return $query->where('access_status', 'alert');
    }

    /**
     * Scope: Get recent logs (last 24 hours)
     */
    public function scopeRecent($query)
    {
        return $query->where('accessed_at', '>=', now()->subDay())
            ->orderBy('accessed_at', 'desc');
    }

    /**
     * Scope: Get logs for a specific gate
     */
    public function scopeForGate($query, $gateId)
    {
        return $query->where('gate_id', $gateId);
    }

    /**
     * Scope: Get logs for a specific vehicle
     */
    public function scopeForVehicle($query, $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }
}
