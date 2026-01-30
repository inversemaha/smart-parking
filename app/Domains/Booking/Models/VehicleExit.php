<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleExit extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_entry_id',
        'vehicle_id',
        'booking_id',
        'parking_location_id',
        'gate_number',
        'exit_time',
        'recorded_by',
        'exit_method',
        'duration_minutes',
        'calculated_fee',
        'paid_amount',
        'payment_status',
        'exit_data',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'exit_time' => 'datetime',
            'calculated_fee' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'exit_data' => 'array',
        ];
    }

    /**
     * Vehicle entry relationship.
     */
    public function vehicleEntry()
    {
        return $this->belongsTo(VehicleEntry::class);
    }

    /**
     * Vehicle relationship.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Booking relationship.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Parking location relationship.
     */
    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class);
    }

    /**
     * Recorded by user relationship.
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Payments relationship.
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * Scope by payment status.
     */
    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope unpaid exits.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Calculate parking fee based on duration and hourly rate.
     */
    public function calculateFee($hourlyRate = null)
    {
        if (!$this->vehicleEntry) {
            return 0;
        }

        $hours = ceil($this->duration_minutes / 60);
        $rate = $hourlyRate ?: ($this->booking ? $this->booking->hourly_rate : $this->parkingLocation->hourly_rate);

        return $hours * $rate;
    }

    /**
     * Mark as paid.
     */
    public function markAsPaid($amount = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'paid_amount' => $amount ?: $this->calculated_fee,
        ]);
    }

    /**
     * Get remaining balance.
     */
    public function getRemainingBalanceAttribute()
    {
        return max(0, $this->calculated_fee - $this->paid_amount);
    }
}
