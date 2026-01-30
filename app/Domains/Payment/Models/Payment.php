<?php

namespace App\Domains\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_id',
        'user_id',
        'payable_type',
        'payable_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'gateway',
        'gateway_transaction_id',
        'gateway_response',
        'initiated_at',
        'paid_at',
        'failed_at',
        'failure_reason',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'gateway_response' => 'array',
            'initiated_at' => 'datetime',
            'paid_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_id)) {
                $payment->payment_id = self::generatePaymentId();
            }
        });
    }

    /**
     * User relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Payable relationship (polymorphic).
     */
    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * SSLCommerz logs relationship.
     */
    public function sslcommerzLogs()
    {
        return $this->hasMany(SslcommerzLog::class);
    }

    /**
     * Generate unique payment ID.
     */
    public static function generatePaymentId()
    {
        do {
            $id = 'PAY' . date('Ymd') . strtoupper(Str::random(8));
        } while (self::where('payment_id', $id)->exists());

        return $id;
    }

    /**
     * Scope by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope successful payments.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope pending payments.
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['initiated', 'pending', 'processing']);
    }

    /**
     * Scope failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Mark payment as paid.
     */
    public function markAsPaid($transactionId = null, $gatewayResponse = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'gateway_transaction_id' => $transactionId,
            'gateway_response' => $gatewayResponse,
        ]);
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed($reason = null, $gatewayResponse = null)
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'failure_reason' => $reason,
            'gateway_response' => $gatewayResponse,
        ]);
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return in_array($this->status, ['initiated', 'pending', 'processing']);
    }
}
