<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingQrCode extends Model
{
    use SoftDeletes;

    protected $table = 'parking_qr_codes';

    protected $fillable = [
        'zone_id',
        'floor_id',
        'code',
        'qr_data',
        'type',
        'redirect_url',
        'metadata',
        'scan_count',
        'first_scanned_at',
        'last_scanned_at',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'json',
        'is_active' => 'boolean',
        'first_scanned_at' => 'datetime',
        'last_scanned_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parking zone this QR code belongs to
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    /**
     * Get the parking floor this QR code belongs to (optional)
     */
    public function floor(): BelongsTo
    {
        return $this->belongsTo(ParkingFloor::class, 'floor_id');
    }

    /**
     * Increment scan count and update last_scanned_at
     */
    public function recordScan(): void
    {
        $this->increment('scan_count');
        
        if ($this->first_scanned_at === null) {
            $this->first_scanned_at = now();
        }
        
        $this->last_scanned_at = now();
        $this->save();
    }

    /**
     * Generate QR code data for display
     * Returns base64 encoded image or URL
     */
    public function getQrImageAttribute(): string
    {
        return $this->qr_data;
    }

    /**
     * Get scan statistics
     */
    public function getScanStats(): array
    {
        return [
            'total_scans' => $this->scan_count,
            'first_scan' => $this->first_scanned_at?->format('Y-m-d H:i:s'),
            'last_scan' => $this->last_scanned_at?->format('Y-m-d H:i:s'),
            'days_active' => $this->first_scanned_at ? now()->diffInDays($this->first_scanned_at) : 0,
        ];
    }

    /**
     * Check if QR code is active and scannable
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Scope: Get only active QR codes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get QR codes by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Get QR codes by zone
     */
    public function scopeForZone($query, $zoneId)
    {
        return $query->where('zone_id', $zoneId);
    }

    /**
     * Scope: Get most recently scanned codes
     */
    public function scopeMostScanned($query, $limit = 10)
    {
        return $query->orderBy('scan_count', 'desc')->limit($limit);
    }
}
