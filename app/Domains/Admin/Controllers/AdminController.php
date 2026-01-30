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
            $query = User::with(['roles', 'userSessions' => function ($q) {
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
}
