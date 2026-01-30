<?php

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'login_at',
        'last_activity_at',
        'logout_at',
        'is_active',
        'device_type',
        'browser',
        'platform',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'login_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'logout_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * User relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active sessions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for recent sessions.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('last_activity_at', '>=', now()->subDays($days));
    }

    /**
     * Get session duration in minutes.
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->logout_at) {
            return null;
        }

        return $this->login_at->diffInMinutes($this->logout_at);
    }

    /**
     * Check if session is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->is_active) {
            return true;
        }

        // Consider session expired if last activity was more than 2 hours ago
        return $this->last_activity_at->lt(now()->subHours(2));
    }
}
