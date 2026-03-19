<?php

namespace App\Domains\Payment\Models;

use App\Domains\Booking\Models\Booking;
use App\Domains\Parking\Models\ParkingSession;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'parking_session_id',
        'booking_id',
        'payment_id',
        'amount',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'payment_status',
        'issued_at',
        'due_date',
        'paid_at',
        'payment_method',
        'reference_number',
        'description',
        'notes',
        'currency',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'issued_at' => 'datetime',
            'due_date' => 'datetime',
            'paid_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    // Status constants
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ISSUED = 'issued';
    public const STATUS_PARTIALLY_PAID = 'partially_paid';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_STATUS_UNPAID = 'unpaid';
    public const PAYMENT_STATUS_PARTIAL = 'partial';
    public const PAYMENT_STATUS_PAID = 'paid';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = self::generateInvoiceNumber();
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
     * Parking session relationship.
     */
    public function parkingSession()
    {
        return $this->belongsTo(ParkingSession::class);
    }

    /**
     * Booking relationship.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Payment relationship.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Generate unique invoice number.
     */
    public static function generateInvoiceNumber()
    {
        do {
            $number = 'INV' . date('Ymd') . strtoupper(Str::random(6));
        } while (self::where('invoice_number', $number)->exists());

        return $number;
    }

    /**
     * Get total amount including tax and discount.
     */
    public function getTotalAttribute()
    {
        return ($this->amount + ($this->tax_amount ?? 0)) - ($this->discount_amount ?? 0);
    }

    /**
     * Check if invoice is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_ISSUED && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Get remaining amount due
     */
    public function getRemainingAmount()
    {
        if ($this->status === self::STATUS_PAID) {
            return 0;
        }

        return $this->total_amount - ($this->getPaidAmount() ?? 0);
    }

    /**
     * Get amount paid so far (if there's a payment)
     */
    public function getPaidAmount()
    {
        if ($this->payment && $this->payment->status === 'paid') {
            return $this->payment->amount;
        }

        return 0;
    }

    /**
     * Issue the invoice (draft to issued)
     */
    public function issue()
    {
        $this->update([
            'status' => self::STATUS_ISSUED,
            'issued_at' => now(),
            'due_date' => now()->addDays(7), // 7-day payment terms
        ]);

        return $this;
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid($paymentDate = null)
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'payment_status' => self::PAYMENT_STATUS_PAID,
            'paid_at' => $paymentDate ?? now(),
        ]);

        return $this;
    }

    /**
     * Record a partial payment
     */
    public function recordPartialPayment($amount)
    {
        $paid = $this->getPaidAmount() + $amount;
        $remaining = $this->total_amount - $paid;

        if ($remaining <= 0) {
            return $this->markAsPaid();
        }

        $this->update([
            'status' => self::STATUS_PARTIALLY_PAID,
            'payment_status' => self::PAYMENT_STATUS_PARTIAL,
        ]);

        return $this;
    }

    /**
     * Check and mark as overdue if applicable
     */
    public function checkAndMarkOverdue()
    {
        if ($this->due_date && $this->due_date->isPast() && in_array($this->payment_status, [self::PAYMENT_STATUS_UNPAID, self::PAYMENT_STATUS_PARTIAL])) {
            $this->update(['status' => self::STATUS_OVERDUE]);
        }

        return $this;
    }

    /**
     * Cancel the invoice
     */
    public function cancel($reason = null)
    {
        if ($this->isPaid()) {
            return false; // Cannot cancel paid invoices
        }

        $metadata = $this->metadata ?? [];
        $metadata['cancelled_at'] = now();
        $metadata['cancellation_reason'] = $reason;

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'metadata' => $metadata,
        ]);

        return $this;
    }

    // ===== SCOPES =====

    /**
     * Scope by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope unpaid invoices.
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', [self::STATUS_ISSUED, self::STATUS_PARTIALLY_PAID, self::STATUS_OVERDUE]);
    }

    /**
     * Scope paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope overdue invoices.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
            ->orWhere(function ($q) {
                $q->where('due_date', '<', now())
                    ->whereIn('payment_status', [self::PAYMENT_STATUS_UNPAID, self::PAYMENT_STATUS_PARTIAL]);
            });
    }

    /**
     * Scope draft invoices
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope issued invoices
     */
    public function scopeIssued($query)
    {
        return $query->where('status', self::STATUS_ISSUED);
    }

    /**
     * Scope by parking session
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('parking_session_id', $sessionId);
    }

    /**
     * Scope by booking
     */
    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    /**
     * Scope this month
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('issued_at', now()->month)
            ->whereYear('issued_at', now()->year);
    }

    /**
     * Scope this year
     */
    public function scopeThisYear($query)
    {
        return $query->whereYear('issued_at', now()->year);
    }

    /**
     * Scope recent invoices
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('issued_at', 'desc');
    }
}
