<?php

namespace App\Domains\Parking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Vehicle;
use App\Models\Payment;
use Carbon\Carbon;

class ParkingSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parking_sessions';

    protected $fillable = [
        'vehicle_id',
        'zone_id',
        'floor_id',
        'entry_gate_id',
        'exit_gate_id',
        'qr_code_id',
        'parking_rate_id',
        'license_plate',
        'session_status',
        'vehicle_category',
        'entry_time',
        'exit_time',
        'duration_minutes',
        'extension_count',
        'base_rate_per_hour',
        'total_hours',
        'peak_hours',
        'vehicle_multiplier',
        'base_charge',
        'peak_charge',
        'discount_amount',
        'total_charge',
        'charging_status',
        'payment_id',
        'payment_notes',
        'entry_metadata',
        'exit_metadata',
        'notes',
        'cancellation_reason',
        'cancellation_by',
        'is_extended',
        'is_grace_period_applied',
        'is_overstayed',
        'is_billing_alert_sent',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
        'entry_metadata' => 'json',
        'exit_metadata' => 'json',
        'base_rate_per_hour' => 'decimal:2',
        'total_hours' => 'decimal:2',
        'peak_hours' => 'decimal:2',
        'vehicle_multiplier' => 'decimal:2',
        'base_charge' => 'decimal:2',
        'peak_charge' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_charge' => 'decimal:2',
        'is_extended' => 'boolean',
        'is_grace_period_applied' => 'boolean',
        'is_overstayed' => 'boolean',
        'is_billing_alert_sent' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class);
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(ParkingFloor::class);
    }

    public function entryGate(): BelongsTo
    {
        return $this->belongsTo(ParkingGate::class, 'entry_gate_id');
    }

    public function exitGate(): BelongsTo
    {
        return $this->belongsTo(ParkingGate::class, 'exit_gate_id');
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(ParkingQrCode::class);
    }

    public function rate(): BelongsTo
    {
        return $this->belongsTo(ParkingRate::class, 'parking_rate_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Query Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('session_status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('session_status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('session_status', 'cancelled');
    }

    public function scopeExtended($query)
    {
        return $query->where('session_status', 'extended');
    }

    public function scopeByZone($query, $zoneId)
    {
        return $query->where('zone_id', $zoneId);
    }

    public function scopeByVehicle($query, $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    public function scopeByGate($query, $gateId)
    {
        return $query->where('entry_gate_id', $gateId)->orWhere('exit_gate_id', $gateId);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('charging_status', '!=', 'collected');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('entry_time', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('entry_time', now()->month)
            ->whereYear('entry_time', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('entry_time', now()->year);
    }

    public function scopeOverstayed($query)
    {
        return $query->where('is_overstayed', true);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('entry_time', '>=', now()->subDays($days));
    }

    /**
     * Health Check: Mark session as exited
     */
    public function markExited($exitTime = null, $exitGateId = null, $metadata = null)
    {
        $this->exit_time = $exitTime ?? now();
        $this->exit_gate_id = $exitGateId;
        $this->exit_metadata = $metadata;
        
        // Calculate duration
        $this->calculateDuration();
        
        // Calculate charges
        $this->calculateCharges();
        
        // Update session status
        $this->session_status = 'completed';
        $this->charging_status = 'calculated';
        
        $this->save();
        
        return $this;
    }

    /**
     * Calculate duration in minutes and decimal hours
     */
    public function calculateDuration()
    {
        if (!$this->exit_time) {
            return;
        }

        $entryTime = Carbon::parse($this->entry_time);
        $exitTime = Carbon::parse($this->exit_time);
        
        $diffMinutes = $entryTime->diffInMinutes($exitTime);
        $diffHours = $entryTime->diffInHours($exitTime, absolute: false);
        $decimalHours = $entryTime->diffInMinutes($exitTime) / 60;

        $this->duration_minutes = $diffMinutes;
        $this->total_hours = round($decimalHours, 2);
        
        // Mark as overstayed if > 24 hours
        $this->is_overstayed = $diffHours > 24;
    }

    /**
     * Calculate all charges
     */
    public function calculateCharges()
    {
        if (!$this->rate || !$this->total_hours) {
            return;
        }

        $baseRate = $this->base_rate_per_hour ?? $this->rate->hourly_rate;
        $hours = $this->total_hours;
        
        // Apply vehicle multiplier
        $multiplier = $this->vehicle_multiplier ?? 1.0;
        $adjustedRate = $baseRate * $multiplier;
        
        // Calculate peak hour charges (assuming peak is 12:00-19:00)
        $peakHours = $this->calculatePeakHours();
        $regularHours = $hours - $peakHours;
        
        $this->peak_hours = round($peakHours, 2);
        $this->base_charge = round($regularHours * $adjustedRate, 2);
        $this->peak_charge = round($peakHours * $adjustedRate * 1.25, 2); // 25% peak surcharge
        
        // Total before discount
        $subtotal = $this->base_charge + $this->peak_charge;
        
        // Apply graceful charging (minimum 30 mins)
        if ($this->duration_minutes < 30) {
            $this->is_grace_period_applied = true;
            $this->total_charge = 0;
        } else {
            // Round up in 15-minute increments
            $roundedHours = ceil($this->total_hours * 4) / 4;
            $this->total_charge = round(($roundedHours * $adjustedRate) + ($peakHours * 0.25 * $adjustedRate), 2);
        }
    }

    /**
     * Calculate peak hours from entry to exit
     */
    private function calculatePeakHours()
    {
        if (!$this->entry_time || !$this->exit_time) {
            return 0;
        }

        $entry = Carbon::parse($this->entry_time);
        $exit = Carbon::parse($this->exit_time);
        
        $peakStart = 12; // 12:00 (noon)
        $peakEnd = 19;   // 19:00 (7 PM)
        
        $peakHours = 0;

        // If entire session is within peak hours
        if ($entry->hour >= $peakStart && $exit->hour < $peakEnd) {
            return $this->total_hours;
        }

        // If session spans peak hours
        $current = $entry->copy();
        while ($current < $exit) {
            if ($current->hour >= $peakStart && $current->hour < $peakEnd) {
                $nextHour = $current->copy()->addHour();
                $endTime = $nextHour < $exit ? $nextHour : $exit;
                $peakHours += $current->diffInMinutes($endTime) / 60;
            }
            $current->addHour();
        }

        return $peakHours;
    }

    /**
     * Mark session as cancelled
     */
    public function cancel($reason = null, $cancelledBy = 'system')
    {
        $this->session_status = 'cancelled';
        $this->cancellation_reason = $reason;
        $this->cancellation_by = $cancelledBy;
        $this->save();

        return $this;
    }

    /**
     * Extend session by x hours
     */
    public function extend($hours = 1, $notes = null)
    {
        $this->extension_count += 1;
        $this->is_extended = true;
        $this->session_status = 'extended';
        
        if ($notes) {
            $this->notes = ($this->notes ?? '') . "\nExtended by {$hours} hour(s): {$notes}";
        }

        $this->save();

        return $this;
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->session_status) {
            'active' => '<span class="badge badge-warning">Active</span>',
            'completed' => '<span class="badge badge-success">Completed</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            'extended' => '<span class="badge badge-info">Extended</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return '-';
        }

        $hours = intval($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        return "{$hours}h {$minutes}m";
    }

    /**
     * Get current occupancy time
     */
    public function getCurrentOccupancyAttribute()
    {
        if ($this->exit_time) {
            return $this->getFormattedDurationAttribute();
        }

        return Carbon::parse($this->entry_time)->diffForHumans(absolute: true);
    }
}
