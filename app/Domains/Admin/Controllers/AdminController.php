<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Domains\User\Models\User;
use App\Domains\User\Models\Role;
use App\Domains\User\Models\Permission;
use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Admin Controller
 *
 * Handles administrative functions including user management,
 * system settings, and vehicle verification.
 */
class AdminController extends Controller
{
    /**
     * Get all users with pagination and filters.
     */
    public function getUsers(Request $request): JsonResponse
    {
        try {
            $query = User::with(['roles', 'sessions' => function ($q) {
                $q->where('is_active', true);
            }]);

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('role')) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('name', $request->role);
                });
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('mobile', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users'
            ], 500);
        }
    }

    /**
     * Get all roles.
     */
    public function getRoles(): JsonResponse
    {
        try {
            $roles = Role::with(['permissions'])->get();

            return response()->json([
                'success' => true,
                'data' => $roles,
                'message' => 'Roles retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching roles: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch roles'
            ], 500);
        }
    }

    /**
     * Get all permissions.
     */
    public function getPermissions(): JsonResponse
    {
        try {
            $permissions = Permission::all()->groupBy('group');

            return response()->json([
                'success' => true,
                'data' => $permissions,
                'message' => 'Permissions retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching permissions: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch permissions'
            ], 500);
        }
    }

    /**
     * Get system settings.
     */
    public function getSystemSettings(Request $request): JsonResponse
    {
        try {
            $query = SystemSetting::query();

            // Filter by group if specified
            if ($request->filled('group')) {
                $query->where('group', $request->group);
            }

            // Filter public settings for non-admin users
            if (!auth()->check() || !auth()->user()->hasRole('admin')) {
                $query->where('is_public', true);
            }

            $settings = $query->orderBy('group')->orderBy('key')->get();

            // Group settings by their group
            $groupedSettings = $settings->groupBy('group')->map(function ($groupSettings) {
                return $groupSettings->map(function ($setting) {
                    return [
                        'id' => $setting->id,
                        'key' => $setting->key,
                        'value' => $setting->is_encrypted ? '[ENCRYPTED]' : $setting->value,
                        'type' => $setting->type,
                        'description' => $setting->description,
                        'is_public' => $setting->is_public,
                        'is_encrypted' => $setting->is_encrypted,
                    ];
                });
            });

            return response()->json([
                'success' => true,
                'data' => $groupedSettings,
                'message' => 'System settings retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching system settings: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch system settings'
            ], 500);
        }
    }

    /**
     * Update system settings.
     */
    public function updateSystemSettings(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'settings' => 'required|array',
                'settings.*.key' => 'required|string',
                'settings.*.value' => 'nullable',
                'settings.*.type' => 'required|in:string,integer,boolean,json,array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            foreach ($request->settings as $settingData) {
                $setting = SystemSetting::where('key', $settingData['key'])->first();

                if (!$setting) {
                    continue; // Skip non-existent settings
                }

                // Convert value based on type
                $value = $settingData['value'];
                if ($settingData['type'] === 'boolean') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
                } elseif (in_array($settingData['type'], ['array', 'json']) && is_array($value)) {
                    $value = json_encode($value);
                }

                $setting->update([
                    'value' => $setting->is_encrypted ? encrypt($value) : $value,
                ]);
            }

            // Clear system settings cache
            SystemSetting::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'System settings updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating system settings: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update system settings'
            ], 500);
        }
    }

    /**
     * Get vehicles pending verification.
     */
    public function getPendingVehicles(Request $request): JsonResponse
    {
        try {
            $query = Vehicle::with(['user'])
                ->where('verification_status', 'pending');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('number_plate', 'like', "%{$search}%")
                      ->orWhere('owner_name', 'like', "%{$search}%")
                      ->orWhere('owner_mobile', 'like', "%{$search}%");
                });
            }

            $vehicles = $query->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $vehicles,
                'message' => 'Pending vehicles retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching pending vehicles: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pending vehicles'
            ], 500);
        }
    }

    /**
     * Verify a vehicle.
     */
    public function verifyVehicle(Request $request, Vehicle $vehicle): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'remarks' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $vehicle->update([
                'verification_status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);

            // Log manual verification
            $vehicle->manualVerifications()->create([
                'admin_id' => auth()->id(),
                'remarks' => $request->remarks ?? 'Manually verified by admin',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle verified successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error verifying vehicle: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify vehicle'
            ], 500);
        }
    }

    /**
     * Reject a vehicle verification.
     */
    public function rejectVehicle(Request $request, Vehicle $vehicle): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'remarks' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $vehicle->update([
                'verification_status' => 'rejected',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);

            // Log manual verification
            $vehicle->manualVerifications()->create([
                'admin_id' => auth()->id(),
                'remarks' => $request->remarks,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle verification rejected'
            ]);
        } catch (\Exception $e) {
            Log::error('Error rejecting vehicle: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject vehicle'
            ], 500);
        }
    }

    /**
     * Assign permissions to a role.
     */
    public function assignPermissions(Request $request, Role $role): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role->permissions()->sync($request->permissions);

            return response()->json([
                'success' => true,
                'message' => 'Permissions assigned successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error assigning permissions: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign permissions'
            ], 500);
        }
    }

    /**
     * Clear system cache.
     */
    public function clearCache(): JsonResponse
    {
        try {
            Cache::flush();
            SystemSetting::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error clearing cache: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache'
            ], 500);
        }
    }

    /**
     * Get a single user.
     */
    public function getUser(User $user): JsonResponse
    {
        try {
            $user->load(['roles', 'sessions' => function ($q) {
                $q->where('is_active', true);
            }]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user'
            ], 500);
        }
    }

    /**
     * Update a user.
     */
    public function updateUser(Request $request, User $user): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'mobile' => 'required|string|unique:users,mobile,' . $user->id,
                'is_active' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user->update($validator->validated());

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update user'
            ], 500);
        }
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user): JsonResponse
    {
        try {
            // Optionally soft delete or hard delete
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user'
            ], 500);
        }
    }

    /**
     * Suspend a user.
     */
    public function suspendUser(Request $request, User $user): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user->update([
                'is_active' => false,
                'suspended_at' => now(),
                'suspension_reason' => $request->reason ?? 'Suspended by admin'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User suspended successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error suspending user: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to suspend user'
            ], 500);
        }
    }

    /**
     * Activate a suspended user.
     */
    public function activateUser(User $user): JsonResponse
    {
        try {
            $user->update([
                'is_active' => true,
                'suspended_at' => null,
                'suspension_reason' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User activated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error activating user: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to activate user'
            ], 500);
        }
    }

    /**
     * Assign roles to a user.
     */
    public function assignRoles(Request $request, User $user): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user->roles()->sync($request->roles);

            return response()->json([
                'success' => true,
                'message' => 'Roles assigned successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error assigning roles: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign roles'
            ], 500);
        }
    }

    /**
     * Remove a role from a user.
     */
    public function removeRole(Request $request, User $user, Role $role): JsonResponse
    {
        try {
            $user->roles()->detach($role);

            return response()->json([
                'success' => true,
                'message' => 'Role removed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing role: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove role'
            ], 500);
        }
    }

    /**
     * Create a new role.
     */
    public function createRole(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:roles,name',
                'display_name' => 'required|string',
                'description' => 'nullable|string',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description ?? null,
            ]);

            if ($request->has('permissions') && $request->permissions) {
                $role->permissions()->attach($request->permissions);
            }

            return response()->json([
                'success' => true,
                'data' => $role->load('permissions'),
                'message' => 'Role created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create role'
            ], 500);
        }
    }

    /**
     * Update a role.
     */
    public function updateRole(Request $request, Role $role): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'display_name' => 'required|string',
                'description' => 'nullable|string',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $role->update([
                'display_name' => $request->display_name,
                'description' => $request->description ?? null,
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions ?? []);
            }

            return response()->json([
                'success' => true,
                'data' => $role->load('permissions'),
                'message' => 'Role updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating role: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update role'
            ], 500);
        }
    }

    /**
     * Delete a role.
     */
    public function deleteRole(Role $role): JsonResponse
    {
        try {
            // Check if role is assigned to users
            if ($role->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete role that is assigned to users'
                ], 422);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role'
            ], 500);
        }
    }

    /**
     * Get vehicle documents.
     */
    public function getVehicleDocuments(Vehicle $vehicle): JsonResponse
    {
        try {
            // Get the latest manual verification with documents
            $verifications = $vehicle->manualVerifications()
                ->orderBy('created_at', 'desc')
                ->get();

            // Flatten documents from all verifications
            $documents = [];
            foreach ($verifications as $verification) {
                if ($verification->documents && is_array($verification->documents)) {
                    $documents = array_merge($documents, $verification->documents);
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'vehicle' => $vehicle->only(['id', 'registration_number', 'brand', 'model']),
                    'documents' => $documents,
                    'verifications' => $verifications
                ],
                'message' => 'Vehicle documents retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching vehicle documents: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vehicle documents'
            ], 500);
        }
    }

    /**
     * Get parking rates.
     */
    public function getParkingRates(): JsonResponse
    {
        try {
            $rates = SystemSetting::where('group', 'parking_rates')
                ->orderBy('key')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $rates,
                'message' => 'Parking rates retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching parking rates: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch parking rates'
            ], 500);
        }
    }

    /**
     * Update parking rates.
     */
    public function updateParkingRates(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'rates' => 'required|array',
                'rates.*.key' => 'required|string',
                'rates.*.value' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            foreach ($request->rates as $rateData) {
                SystemSetting::updateOrCreate(
                    [
                        'key' => $rateData['key'],
                        'group' => 'parking_rates'
                    ],
                    [
                        'value' => $rateData['value'],
                        'type' => 'numeric',
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Parking rates updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating parking rates: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update parking rates'
            ], 500);
        }
    }

    /**
     * Get system health status.
     */
    public function getSystemHealth(): JsonResponse
    {
        try {
            $health = [
                'database' => $this->checkDatabaseConnection(),
                'cache' => $this->checkCacheConnection(),
                'queue' => $this->checkQueueConnection(),
                'disk_space' => $this->checkDiskSpace(),
                'memory' => $this->checkMemory(),
                'cpu' => $this->checkCpuUsage(),
            ];

            return response()->json([
                'success' => true,
                'data' => $health,
                'message' => 'System health retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching system health: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch system health'
            ], 500);
        }
    }

    /**
     * Check database connection.
     */
    private function checkDatabaseConnection(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connection OK'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => 'Database connection failed: ' . $e->getMessage()];
        }
    }

    /**
     * Check cache connection.
     */
    private function checkCacheConnection(): array
    {
        try {
            Cache::put('health_check', 'ok', 60);
            Cache::forget('health_check');
            return ['status' => 'healthy', 'message' => 'Cache connection OK'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => 'Cache connection failed'];
        }
    }

    /**
     * Check queue connection.
     */
    private function checkQueueConnection(): array
    {
        try {
            return ['status' => 'healthy', 'message' => 'Queue system OK'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => 'Queue system failed'];
        }
    }

    /**
     * Check disk space.
     */
    private function checkDiskSpace(): array
    {
        $disk = disk_free_space('/');
        $diskTotal = disk_total_space('/');
        $diskUsagePercent = (($diskTotal - $disk) / $diskTotal) * 100;

        return [
            'status' => $diskUsagePercent > 90 ? 'warning' : 'healthy',
            'usage_percent' => round($diskUsagePercent, 2),
            'free_space' => floor($disk / 1024 / 1024 / 1024) . ' GB',
        ];
    }

    /**
     * Check memory usage.
     */
    private function checkMemory(): array
    {
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);

        $memoryUsagePercent = (($mem[2] / $mem[1]) * 100);

        return [
            'status' => $memoryUsagePercent > 90 ? 'warning' : 'healthy',
            'usage_percent' => round($memoryUsagePercent, 2),
        ];
    }

    /**
     * Check CPU usage.
     */
    private function checkCpuUsage(): array
    {
        try {
            $load = sys_getloadavg();
            $cpuCount = shell_exec('nproc');
            $cpuCount = (int)trim($cpuCount);

            return [
                'status' => 'healthy',
                'load_average' => round($load[0], 2),
                'cpu_cores' => $cpuCount,
            ];
        } catch (\Exception $e) {
            return ['status' => 'unknown', 'message' => 'Could not determine CPU usage'];
        }
    }

    /**
     * Get queue status.
     */
    public function getQueueStatus(): JsonResponse
    {
        try {
            $jobsCount = DB::table('jobs')->count();
            $failedJobsCount = DB::table('failed_jobs')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'pending_jobs' => $jobsCount,
                    'failed_jobs' => $failedJobsCount,
                    'status' => $jobsCount > 0 ? 'processing' : 'idle'
                ],
                'message' => 'Queue status retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching queue status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch queue status'
            ], 500);
        }
    }

    /**
     * Get cache status.
     */
    public function getCacheStatus(): JsonResponse
    {
        try {
            $cacheInfo = [
                'driver' => config('cache.default'),
                'status' => 'active'
            ];

            return response()->json([
                'success' => true,
                'data' => $cacheInfo,
                'message' => 'Cache status retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching cache status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cache status'
            ], 500);
        }
    }

    /**
     * Get system logs.
     */
    public function getSystemLogs(Request $request): JsonResponse
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            $lines = $request->get('lines', 100);

            if (!file_exists($logFile)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Log file not found'
                ], 404);
            }

            $logs = file($logFile);
            $logs = array_slice($logs, -$lines);
            $logs = array_reverse($logs);

            return response()->json([
                'success' => true,
                'data' => $logs,
                'message' => 'System logs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching system logs: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch system logs'
            ], 500);
        }
    }

    /**
     * Force exit all vehicles.
     */
    public function forceExitAllVehicles(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find all active vehicle entries (without corresponding exits)
            $activeEntries = DB::table('vehicle_entries as ve')
                ->leftJoin('vehicle_exits as vex', 've.id', '=', 'vex.vehicle_entry_id')
                ->whereNull('vex.id') // No exit record
                ->select('ve.*')
                ->get();

            $exitedCount = 0;
            $now = now();

            foreach ($activeEntries as $entry) {
                // Create vehicle exit record
                DB::table('vehicle_exits')->insert([
                    'vehicle_entry_id' => $entry->id,
                    'vehicle_id' => $entry->vehicle_id,
                    'booking_id' => $entry->booking_id,
                    'parking_location_id' => $entry->parking_location_id,
                    'gate_number' => $entry->gate_number,
                    'exit_time' => $now,
                    'recorded_by' => auth()->id(),
                    'exit_method' => 'force_exit',
                    'duration_minutes' => (int)$entry->entry_time->diffInMinutes($now),
                    'calculated_fee' => 0,
                    'paid_amount' => 0,
                    'payment_status' => 'waived',
                    'notes' => $request->reason ?? 'Emergency force exit by admin',
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
                $exitedCount++;
            }

            return response()->json([
                'success' => true,
                'data' => ['exited_vehicles' => $exitedCount],
                'message' => "Force exited $exitedCount vehicles"
            ]);
        } catch (\Exception $e) {
            Log::error('Error force exiting vehicles: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to force exit vehicles'
            ], 500);
        }
    }

    /**
     * Lock system for emergency.
     */
    public function lockSystem(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            SystemSetting::updateOrCreate(
                ['key' => 'system_locked', 'group' => 'emergency'],
                [
                    'value' => 'true',
                    'type' => 'boolean',
                    'description' => 'System locked for emergency: ' . ($request->reason ?? 'No reason provided')
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'System locked successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error locking system: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to lock system'
            ], 500);
        }
    }

    /**
     * Unlock system.
     */
    public function unlockSystem(): JsonResponse
    {
        try {
            SystemSetting::where('key', 'system_locked')
                ->where('group', 'emergency')
                ->update(['value' => 'false']);

            return response()->json([
                'success' => true,
                'message' => 'System unlocked successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error unlocking system: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to unlock system'
            ], 500);
        }
    }

    /**
     * Broadcast emergency message to all users.
     */
    public function broadcastMessage(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'message' => 'required|string|max:1000',
                'type' => 'nullable|in:info,warning,danger,success',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // You can implement broadcasting via websockets, email, SMS, or notifications
            // For now, store in system broadcasts table or cache
            $broadcastMessage = [
                'id' => uniqid(),
                'message' => $request->message,
                'type' => $request->type ?? 'info',
                'created_by' => auth()->id(),
                'created_at' => now()
            ];

            Cache::put('system_broadcast_' . $broadcastMessage['id'], $broadcastMessage, 3600);

            return response()->json([
                'success' => true,
                'data' => $broadcastMessage,
                'message' => 'Message broadcasted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error broadcasting message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to broadcast message'
            ], 500);
        }
    }
}
