<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Admin\Models\AuditLog;
use App\Domains\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

/**
 * AuditLogController
 *
 * Manages audit log viewing and export functionality for administrators.
 */
class AuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
        $this->middleware('permission:admin.audit.view')->only(['index', 'show', 'getUserLogs']);
        $this->middleware('permission:admin.audit.export')->only(['export']);
    }

    /**
     * Display a listing of audit logs with filtering.
     */
    public function index(Request $request): JsonResponse|View
    {
        $query = AuditLog::with('user:id,name,mobile')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('resource_type')) {
            $query->where('resource_type', $request->resource_type);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('resource_type', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('mobile', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = $request->get('per_page', 15);
        $logs = $query->paginate($perPage);

        // Add additional computed data
        $logs->getCollection()->transform(function ($log) {
            return [
                'id' => $log->id,
                'user' => $log->user ? [
                    'id' => $log->user->id,
                    'name' => $log->user->name,
                    'mobile' => $log->user->mobile
                ] : null,
                'action' => $log->action,
                'action_description' => $log->action_description,
                'resource_type' => $log->resource_type,
                'resource_id' => $log->resource_id,
                'old_values' => $log->old_values,
                'new_values' => $log->new_values,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'additional_data' => $log->additional_data,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                'created_at_human' => $log->created_at->diffForHumans()
            ];
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $logs->items(),
                'meta' => [
                    'current_page' => $logs->currentPage(),
                    'from' => $logs->firstItem(),
                    'last_page' => $logs->lastPage(),
                    'per_page' => $logs->perPage(),
                    'to' => $logs->lastItem(),
                    'total' => $logs->total(),
                ]
            ]);
        }

        return view('admin.audit.index', compact('logs'));
    }

    /**
     * Display the specified audit log.
     */
    public function show(AuditLog $auditLog): JsonResponse
    {
        $auditLog->load('user:id,name,mobile');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $auditLog->id,
                'user' => $auditLog->user ? [
                    'id' => $auditLog->user->id,
                    'name' => $auditLog->user->name,
                    'mobile' => $auditLog->user->mobile
                ] : null,
                'action' => $auditLog->action,
                'action_description' => $auditLog->action_description,
                'resource_type' => $auditLog->resource_type,
                'resource_id' => $auditLog->resource_id,
                'old_values' => $auditLog->old_values,
                'new_values' => $auditLog->new_values,
                'ip_address' => $auditLog->ip_address,
                'user_agent' => $auditLog->user_agent,
                'additional_data' => $auditLog->additional_data,
                'created_at' => $auditLog->created_at->format('Y-m-d H:i:s'),
                'created_at_human' => $auditLog->created_at->diffForHumans()
            ]
        ]);
    }

    /**
     * Get audit logs for a specific user.
     */
    public function getUserLogs(User $user, Request $request): JsonResponse
    {
        $query = AuditLog::forUser($user->id)
            ->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        $perPage = $request->get('per_page', 15);
        $logs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $logs->items(),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'from' => $logs->firstItem(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'to' => $logs->lastItem(),
                'total' => $logs->total(),
            ],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile
            ]
        ]);
    }

    /**
     * Export audit logs to CSV.
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $query = AuditLog::with('user:id,name,mobile')
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('resource_type')) {
            $query->where('resource_type', $request->resource_type);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        $fileName = 'audit_logs_' . Carbon::now()->format('Y_m_d_H_i_s') . '.csv';

        return Response::streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($handle, [
                'ID',
                'User Name',
                'User Mobile',
                'Action',
                'Resource Type',
                'Resource ID',
                'IP Address',
                'Created At',
                'Old Values',
                'New Values'
            ]);

            // Stream data in chunks
            $query->chunk(1000, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->user?->name ?? 'N/A',
                        $log->user?->mobile ?? 'N/A',
                        $log->action,
                        $log->resource_type,
                        $log->resource_id,
                        $log->ip_address,
                        $log->created_at->format('Y-m-d H:i:s'),
                        json_encode($log->old_values),
                        json_encode($log->new_values)
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }
}
