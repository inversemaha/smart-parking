<?php

namespace App\Shared\Services;

use App\Domains\Admin\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log an action for audit trail
     */
    public function log(
        string $action,
        string $description,
        ?int $performedBy = null,
        ?int $targetUserId = null,
        array $metadata = []
    ): void {
        try {
            AuditLog::create([
                'action' => $action,
                'description' => $description,
                'performed_by' => $performedBy ?? Auth::id(),
                'target_user_id' => $targetUserId,
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
                'metadata' => $metadata,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log to system log if audit log fails
            \Log::error('Failed to create audit log: ' . $e->getMessage(), [
                'action' => $action,
                'description' => $description,
                'metadata' => $metadata
            ]);
        }
    }

    /**
     * Log user authentication events
     */
    public function logAuth(string $action, ?int $userId = null, array $metadata = []): void
    {
        $this->log(
            $action,
            "Authentication event: {$action}",
            null,
            $userId,
            array_merge([
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
                'timestamp' => now()->toISOString()
            ], $metadata)
        );
    }

    /**
     * Log security events
     */
    public function logSecurity(string $action, string $description, array $metadata = []): void
    {
        $this->log(
            "security.{$action}",
            $description,
            Auth::id(),
            null,
            array_merge([
                'security_event' => true,
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
                'timestamp' => now()->toISOString()
            ], $metadata)
        );
    }

    /**
     * Log data access events
     */
    public function logDataAccess(string $model, string $action, int $recordId, array $metadata = []): void
    {
        $this->log(
            "data.{$action}",
            "Accessed {$model} record ID: {$recordId}",
            Auth::id(),
            null,
            array_merge([
                'model' => $model,
                'record_id' => $recordId,
                'action' => $action
            ], $metadata)
        );
    }

    /**
     * Log admin actions
     */
    public function logAdmin(string $action, string $description, array $metadata = []): void
    {
        $this->log(
            "admin.{$action}",
            $description,
            Auth::id(),
            null,
            array_merge([
                'admin_action' => true,
                'timestamp' => now()->toISOString()
            ], $metadata)
        );
    }

    /**
     * Log API requests
     */
    public function logApi(string $endpoint, string $method, int $statusCode, array $metadata = []): void
    {
        $this->log(
            'api.request',
            "API request: {$method} {$endpoint}",
            Auth::id(),
            null,
            array_merge([
                'endpoint' => $endpoint,
                'method' => $method,
                'status_code' => $statusCode,
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent')
            ], $metadata)
        );
    }

    /**
     * Get audit logs for a specific user
     */
    public function getUserLogs(int $userId, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return AuditLog::where('target_user_id', $userId)
            ->orWhere('performed_by', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent security events
     */
    public function getSecurityEvents(int $days = 7, int $limit = 100): \Illuminate\Database\Eloquent\Collection
    {
        return AuditLog::where('action', 'like', 'security.%')
            ->where('created_at', '>=', now()->subDays($days))
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get failed login attempts
     */
    public function getFailedLogins(int $hours = 24): \Illuminate\Database\Eloquent\Collection
    {
        return AuditLog::where('action', 'login_failed')
            ->where('created_at', '>=', now()->subHours($hours))
            ->latest()
            ->get();
    }

    /**
     * Clean up old logs (for scheduled tasks)
     */
    public function cleanup(int $days = 90): int
    {
        return AuditLog::where('created_at', '<', now()->subDays($days))->delete();
    }
}
