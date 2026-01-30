<?php

namespace App\Domains\Vehicle\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrtaVerificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'brta_config_id',
        'registration_number',
        'status',
        'request_data',
        'response_data',
        'response_code',
        'error_message',
        'attempt_number',
        'response_time_ms',
        'requested_at',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'request_data' => 'array',
            'response_data' => 'array',
            'response_time_ms' => 'decimal:2',
            'requested_at' => 'datetime',
            'responded_at' => 'datetime',
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
     * BRTA config relationship.
     */
    public function brtaConfig()
    {
        return $this->belongsTo(BrtaConfig::class);
    }

    /**
     * Scope successful verifications.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope failed verifications.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope by registration number.
     */
    public function scopeByRegistration($query, $registrationNumber)
    {
        return $query->where('registration_number', $registrationNumber);
    }

    /**
     * Log BRTA verification attempt.
     */
    public static function logVerification($vehicleId, $configId, $registrationNumber, $requestData, $attemptNumber = 1)
    {
        return self::create([
            'vehicle_id' => $vehicleId,
            'brta_config_id' => $configId,
            'registration_number' => $registrationNumber,
            'status' => 'pending',
            'request_data' => $requestData,
            'attempt_number' => $attemptNumber,
            'requested_at' => now(),
        ]);
    }

    /**
     * Update with response.
     */
    public function updateWithResponse($responseData, $responseCode, $responseTimeMs, $status = 'success', $errorMessage = null)
    {
        $this->update([
            'status' => $status,
            'response_data' => $responseData,
            'response_code' => $responseCode,
            'response_time_ms' => $responseTimeMs,
            'error_message' => $errorMessage,
            'responded_at' => now(),
        ]);
    }
}
