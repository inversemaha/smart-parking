<?php

namespace App\Domains\Booking\Models;

use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'user_id',
        'vehicle_id',
        'parking_location_id',
        'parking_slot_id',
        'start_time',
        'end_time',
        'entry_time',
        'exit_time',
        'duration_hours',
        'parking_duration_minutes',
        'hourly_rate',
        'total_amount',
        'status',
        'payment_status',
        'notes',
        'confirmed_at',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'entry_time' => 'datetime',
            'exit_time' => 'datetime',
            'parking_duration_minutes' => 'integer',
            'hourly_rate' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = self::generateBookingNumber();
            }
        });
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
     * Vehicle relationship.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class)->withDefault([
            'license_plate' => 'Unknown',
            'model' => 'Unknown Vehicle'
        ]);
    }

    /**
     * Parking location relationship.
     */
    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class);
    }

    /**
     * Parking slot relationship.
     */
    public function parkingSlot()
    {
        return $this->belongsTo(ParkingSlot::class);
    }

    /**
     * Cancelled by user relationship.
     */
    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by')->withDefault([
            'name' => 'System',
            'email' => 'system@example.com'
        ]);
    }

    /**
     * Booking slot histories relationship.
     */
    public function slotHistories()
    {
        return $this->hasMany(BookingSlotHistory::class);
    }

    /**
     * Vehicle entries relationship.
     */
    public function vehicleEntries()
    {
        return $this->hasMany(\App\Models\VehicleEntry::class);
    }

    /**
     * Get the latest vehicle entry for this booking.
     */
    public function latestEntry()
    {
        return $this->vehicleEntries()->latest('entry_time');
    }

    /**
     * Vehicle exits relationship.
     */
    public function vehicleExits()
    {
        return $this->hasMany(VehicleExit::class);
    }

    /**
     * Payments relationship.
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * Generate unique booking number.
     */
    public static function generateBookingNumber()
    {
        do {
            $number = 'BK' . date('Ymd') . strtoupper(Str::random(6));
        } while (self::where('booking_number', $number)->exists());

        return $number;
    }

    /**
     * Scope active bookings.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'active']);
    }

    /**
     * Scope by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if booking is active.
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['confirmed', 'active']) &&
               $this->start_time <= now() &&
               $this->end_time >= now();
    }

    /**
     * Check if booking can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']) &&
               $this->start_time > now();
    }

    /**
     * Cancel booking.
     */
    public function cancel($reason = null, $cancelledBy = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => $cancelledBy,
            'cancellation_reason' => $reason,
        ]);

        // Free up the slot
        if ($this->parkingSlot) {
            $this->parkingSlot->makeAvailable();
        }
    }

    /**
     * Confirm booking.
     */
    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        // Reserve the slot
        if ($this->parkingSlot) {
            $this->parkingSlot->reserve();
        }
    }
}
