<?php

namespace App\Domains\Vehicle\Models;

use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'registration_number',
        'vehicle_type',
        'brand',
        'model',
        'color',
        'manufacture_year',
        'verification_status',
        'verified_at',
        'verified_by',
        'brta_data',
        'verification_notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'brta_data' => 'array',
            'is_active' => 'boolean',
            'manufacture_year' => 'integer',
        ];
    }

    /**
     * User relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Unknown User',
            'email' => 'unknown@example.com'
        ]);
    }

    /**
     * Verified by user relationship.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by')->withDefault([
            'name' => 'System',
            'email' => 'system@example.com'
        ]);
    }

    /**
     * BRTA verification logs relationship.
     */
    public function brtaVerificationLogs()
    {
        return $this->hasMany(BrtaVerificationLog::class);
    }

    /**
     * Manual verifications relationship.
     */
    public function manualVerifications()
    {
        return $this->hasMany(VehicleManualVerification::class);
    }

    /**
     * Vehicle bookings relationship.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Vehicle entries relationship.
     */
    public function entries()
    {
        return $this->hasMany(VehicleEntry::class);
    }

    /**
     * Vehicle exits relationship.
     */
    public function exits()
    {
        return $this->hasMany(VehicleExit::class);
    }

    /**
     * Scope verified vehicles.
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope active vehicles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if vehicle is verified.
     */
    public function isVerified(): bool
    {
        return in_array($this->verification_status, ['verified', 'manual_verified']);
    }

    /**
     * Get latest BRTA verification log.
     */
    public function latestBrtaLog()
    {
        return $this->brtaVerificationLogs()->latest();
    }
}
