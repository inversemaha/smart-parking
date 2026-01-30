<?php

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'device_name',
        'device_type',
        'browser',
        'platform',
        'ip_address',
        'first_login_at',
        'last_login_at',
        'is_trusted',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'first_login_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_trusted' => 'boolean',
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
     * Scope active devices.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope trusted devices.
     */
    public function scopeTrusted($query)
    {
        return $query->where('is_trusted', true);
    }

    /**
     * Mark device as trusted.
     */
    public function markAsTrusted()
    {
        $this->update(['is_trusted' => true]);
    }

    /**
     * Update last login time.
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }
}
