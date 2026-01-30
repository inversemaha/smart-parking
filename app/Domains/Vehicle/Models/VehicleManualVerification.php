<?php

namespace App\Domains\Vehicle\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleManualVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'verified_by',
        'status',
        'reason',
        'documents',
        'admin_notes',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'documents' => 'array',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Vehicle relationship.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Verified by user relationship.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope approved verifications.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope rejected verifications.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Create manual verification.
     */
    public static function createVerification($vehicleId, $verifiedBy, $status, $reason, $documents = null, $adminNotes = null)
    {
        $verification = self::create([
            'vehicle_id' => $vehicleId,
            'verified_by' => $verifiedBy,
            'status' => $status,
            'reason' => $reason,
            'documents' => $documents,
            'admin_notes' => $adminNotes,
            'verified_at' => now(),
        ]);

        // Update vehicle verification status
        $vehicle = Vehicle::find($vehicleId);
        if ($vehicle) {
            $vehicle->update([
                'verification_status' => $status === 'approved' ? 'manual_verified' : 'failed',
                'verified_at' => now(),
                'verified_by' => $verifiedBy,
                'verification_notes' => $reason,
            ]);
        }

        return $verification;
    }
}
