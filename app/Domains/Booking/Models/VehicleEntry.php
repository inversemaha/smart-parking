<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'booking_id',
        'parking_location_id',
        'parking_slot_id',
        'gate_number',
        'entry_time',
        'recorded_by',
        'entry_method',
        'entry_data',
        'ticket_number',
        'is_valid_entry',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'entry_time' => 'datetime',
            'entry_data' => 'array',
            'is_valid_entry' => 'boolean',
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
     * Parking slot relationship.
     */
    public function parkingSlot()
    {
        return $this->belongsTo(ParkingSlot::class);
    }

    /**
     * Recorded by user relationship.
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Vehicle exit relationship.
     */
    public function vehicleExit()
    {
        return $this->hasOne(VehicleExit::class);
    }

    /**
     * Scope valid entries.
     */
    public function scopeValid($query)
    {
        return $query->where('is_valid_entry', true);
    }

    /**
     * Scope by entry method.
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('entry_method', $method);
    }

    /**
     * Check if vehicle has exited.
     */
    public function hasExited(): bool
    {
        return $this->vehicleExit !== null;
    }

    /**
     * Get parking duration in minutes.
     */
    public function getParkingDurationAttribute()
    {
        $endTime = $this->vehicleExit ? $this->vehicleExit->exit_time : now();
        return $this->entry_time->diffInMinutes($endTime);
    }

    /**
     * Generate ticket number.
     */
    public static function generateTicketNumber()
    {
        return 'TK' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
}
