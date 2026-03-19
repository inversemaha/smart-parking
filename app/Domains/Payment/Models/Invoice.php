<?php

namespace App\Domains\Payment\Models;

use App\Domains\Booking\Models\Booking;
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
        'payment_id',
        'booking_id',
        'user_id',
        'amount',
        'tax_amount',
        'discount_amount',
        'status',
        'issued_at',
        'due_date',
        'paid_at',
        'notes',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'issued_at' => 'datetime',
            'due_date' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

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
     * Payment relationship.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Booking relationship.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
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
        return $this->status === 'paid';
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'unpaid' && $this->due_date && $this->due_date->isPast();
    }

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
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope overdue invoices.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'unpaid')
                    ->where('due_date', '<', now());
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid($paymentDate = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => $paymentDate ?? now(),
        ]);
    }
}
