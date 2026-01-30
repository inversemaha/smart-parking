<?php

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccessLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'method',
        'url',
        'request_data',
        'session_id',
        'device_id',
        'status',
        'message',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'request_data' => 'array',
            'created_at' => 'datetime',
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
     * Log user action.
     */
    public static function logAction($userId, $action, $data = [])
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'request_data' => $data,
            'session_id' => session()->getId(),
            'status' => 'success',
            'created_at' => now(),
        ]);
    }

    /**
     * Scope by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope successful actions.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope failed actions.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
