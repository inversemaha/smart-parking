<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Booking\Models\Booking;
use App\Domains\Booking\Models\BookingSlotHistory;
use App\Domains\Booking\Models\VehicleEntry;

class ParkingSlot extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parking_area_id',
        'slot_number',
        'floor',
        'section',
        'slot_type',
        'vehicle_types',
        'status',
        'length_meters',
        'width_meters',
        'notes',
        'is_active',
        'last_occupied_at',
    ];

    protected function casts(): array
    {
        return [
            'vehicle_types' => 'array',
            'length_meters' => 'decimal:2',
            'width_meters' => 'decimal:2',
            'is_active' => 'boolean',
            'last_occupied_at' => 'datetime',
        ];
    }

    /**
     * Parking area relationship.
     */
    public function parkingArea()
    {
        return $this->belongsTo(ParkingArea::class);
    }

    /**
     * Bookings relationship.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Current booking relationship.
     */
    public function currentBooking()
    {
        return $this->hasOne(Booking::class)
                    ->whereIn('status', ['confirmed', 'active'])
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now());
    }

    /**
     * Booking slot histories relationship.
     */
    public function histories()
    {
        return $this->hasMany(BookingSlotHistory::class);
    }

    /**
     * Vehicle entries relationship.
     */
    public function vehicleEntries()
    {
        return $this->hasMany(VehicleEntry::class);
    }

    /**
     * Scope active slots.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope available slots.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope by vehicle type.
     */
    public function scopeForVehicleType($query, $vehicleType)
    {
        return $query->whereJsonContains('vehicle_types', $vehicleType);
    }

    /**
     * Check if slot is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->is_active;
    }

    /**
     * Check if slot can accommodate vehicle type.
     */
    public function canAccommodate($vehicleType): bool
    {
        return in_array($vehicleType, $this->vehicle_types);
    }

    /**
     * Occupy the slot.
     */
    public function occupy()
    {
        $this->update([
            'status' => 'occupied',
            'last_occupied_at' => now(),
        ]);
    }

    /**
     * Make the slot available.
     */
    public function makeAvailable()
    {
        $this->update(['status' => 'available']);
    }

    /**
     * Reserve the slot.
     */
    public function reserve()
    {
        $this->update(['status' => 'reserved']);
    }
}
