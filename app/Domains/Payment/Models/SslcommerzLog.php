<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SslcommerzLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'transaction_id',
        'action',
        'status',
        'request_data',
        'response_data',
        'response_code',
        'error_message',
        'response_time_ms',
        'ip_address',
        'user_agent',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'request_data' => 'array',
            'response_data' => 'array',
            'response_time_ms' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    /**
     * Payment relationship.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Log SSLCommerz transaction.
     */
    public static function logTransaction($paymentId, $transactionId, $action, $requestData, $responseData = null, $status = 'pending')
    {
        return self::create([
            'payment_id' => $paymentId,
            'transaction_id' => $transactionId,
            'action' => $action,
            'status' => $status,
            'request_data' => $requestData,
            'response_data' => $responseData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'processed_at' => now(),
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
        ]);
    }

    /**
     * Scope by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope successful logs.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }
}
