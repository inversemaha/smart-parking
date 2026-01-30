<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSlotHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'parking_slot_id',
        'action',
        'previous_slot_id',
        'action_at',
        'action_by',
        'reason',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'action_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /**
     * Booking relationship.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Parking slot relationship.
     */
    public function parkingSlot()
    {
        return $this->belongsTo(ParkingSlot::class);
    }

    /**
     * Previous slot relationship.
     */
    public function previousSlot()
    {
        return $this->belongsTo(ParkingSlot::class, 'previous_slot_id');
    }

    /**
     * Action by user relationship.
     */
    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    /**
     * Log slot action.
     */
    public static function logAction($bookingId, $slotId, $action, $previousSlotId = null, $actionBy = null, $reason = null, $metadata = null)
    {
        return self::create([
            'booking_id' => $bookingId,
            'parking_slot_id' => $slotId,
            'action' => $action,
            'previous_slot_id' => $previousSlotId,
            'action_at' => now(),
            'action_by' => $actionBy,
            'reason' => $reason,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Scope by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }
}
