<?php

namespace App\Domains\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\User\Models\User;
use Carbon\Carbon;

/**
 * AuditLog Model
 *
 * Tracks all administrative actions and security events
 * for audit trail and compliance purposes.
 */
class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'resource_type',
        'resource_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'additional_data',
        'created_at'
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'additional_data' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }

    /**
     * Get the user who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create an audit log entry.
     */
    public static function createLog(string $action, string $resourceType, $resourceId = null, array $oldValues = [], array $newValues = [], array $additionalData = []): void
    {
        $user = auth()->user();
        $request = request();

        static::create([
            'user_id' => $user?->id,
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'additional_data' => $additionalData
        ]);
    }

    /**
     * Get logs for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get logs for a specific resource.
     */
    public function scopeForResource($query, string $resourceType, $resourceId = null)
    {
        $query = $query->where('resource_type', $resourceType);

        if ($resourceId !== null) {
            $query->where('resource_id', $resourceId);
        }

        return $query;
    }

    /**
     * Get logs from a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    /**
     * Get the human-readable action description.
     */
    public function getActionDescriptionAttribute(): string
    {
        $descriptions = [
            'create' => __('admin.audit.actions.create'),
            'update' => __('admin.audit.actions.update'),
            'delete' => __('admin.audit.actions.delete'),
            'login' => __('admin.audit.actions.login'),
            'logout' => __('admin.audit.actions.logout'),
            'access' => __('admin.audit.actions.access'),
            'verify' => __('admin.audit.actions.verify'),
            'reject' => __('admin.audit.actions.reject'),
            'suspend' => __('admin.audit.actions.suspend'),
            'activate' => __('admin.audit.actions.activate'),
        ];

        return $descriptions[$this->action] ?? $this->action;
    }
}
