<?php

namespace App\Domains\User\Services;

use App\Domains\User\Models\User;
use App\Domains\User\Models\Role;
use App\Domains\User\Repositories\UserRepository;
use App\Shared\Services\FileUploadService;
use App\Shared\Services\AuditLogService;
use App\Shared\Services\CacheService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected FileUploadService $fileUploadService,
        protected AuditLogService $auditLogService,
        protected CacheService $cacheService
    ) {}

    /**
     * Get all users with filtering and pagination
     */
    public function getAllUsers(array $filters = []): Collection
    {
        return $this->userRepository->getAllUsers($filters);
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }

    /**
     * Create a new user
     */
    public function createUser(array $userData): User
    {
        $userData['password'] = Hash::make($userData['password']);
        $user = $this->userRepository->create($userData);

        // Assign default role
        $defaultRole = Role::where('name', 'user')->first();
        if ($defaultRole) {
            $user->roles()->attach($defaultRole->id, [
                'assigned_at' => now(),
                'assigned_by' => auth()->id()
            ]);
        }

        // Log user creation
        $this->auditLogService->log(
            'user_created',
            'User created',
            null,
            $user->id,
            ['email' => $user->email]
        );

        return $user->fresh(['roles']);
    }

    /**
     * Update user information
     */
    public function updateUser(User $user, array $data): User
    {
        // Track changes for audit
        $originalData = $user->only(['name', 'email', 'phone', 'locale', 'is_active']);

        $user = $this->userRepository->update($user, $data);

        // Log changes
        $changes = array_diff_assoc($data, $originalData);
        if (!empty($changes)) {
            $this->auditLogService->log(
                'user_updated',
                'User information updated',
                null,
                $user->id,
                ['changes' => $changes]
            );
        }

        return $user->fresh(['roles']);
    }

    /**
     * Delete user (soft delete)
     */
    public function deleteUser(User $user): bool
    {
        // Log deletion
        $this->auditLogService->log(
            'user_deleted',
            'User deleted',
            null,
            $user->id,
            ['email' => $user->email]
        );

        return $this->userRepository->delete($user);
    }

    /**
     * Update user avatar
     */
    public function updateAvatar(User $user, UploadedFile $avatar): string
    {
        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Upload new avatar
        $avatarPath = $this->fileUploadService->uploadFile(
            $avatar,
            'avatars',
            'public'
        );

        // Update user record
        $user->update(['avatar' => $avatarPath]);

        // Log avatar update
        $this->auditLogService->log(
            'avatar_updated',
            'User avatar updated',
            null,
            $user->id,
            ['avatar_path' => $avatarPath]
        );

        return Storage::disk('public')->url($avatarPath);
    }

    /**
     * Change user password
     */
    public function changePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // Revoke all existing tokens for security
        $user->tokens()->delete();

        // Log password change
        $this->auditLogService->log(
            'password_changed',
            'User password changed',
            null,
            $user->id,
            []
        );
    }

    /**
     * Upload user documents
     */
    public function uploadDocuments(User $user, array $documents): array
    {
        $uploadedDocuments = [];

        foreach ($documents as $document) {
            $documentPath = $this->fileUploadService->uploadFile(
                $document,
                'documents/users/' . $user->id,
                'private'
            );

            $uploadedDocuments[] = [
                'name' => $document->getClientOriginalName(),
                'path' => $documentPath,
                'size' => $document->getSize(),
                'mime_type' => $document->getMimeType()
            ];
        }

        // Store document information in user's meta data
        $existingDocuments = $user->documents ?? [];
        $allDocuments = array_merge($existingDocuments, $uploadedDocuments);

        $user->update(['documents' => $allDocuments]);

        // Log document upload
        $this->auditLogService->log(
            'documents_uploaded',
            'User documents uploaded',
            null,
            $user->id,
            ['document_count' => count($uploadedDocuments)]
        );

        return $uploadedDocuments;
    }

    /**
     * Change user language preference
     */
    public function changeLanguage(User $user, string $locale): void
    {
        $oldLocale = $user->locale;

        $user->update(['locale' => $locale]);

        // Clear user cache to force locale refresh
        $this->cacheService->forget("user:{$user->id}:preferences");

        // Log language change
        $this->auditLogService->log(
            'language_changed',
            'User language preference changed',
            null,
            $user->id,
            ['from' => $oldLocale, 'to' => $locale]
        );
    }

    /**
     * Activate user account
     */
    public function activateUser(User $user): void
    {
        $user->update(['is_active' => true]);

        $this->auditLogService->log(
            'user_activated',
            'User account activated',
            auth()->id(),
            $user->id,
            []
        );
    }

    /**
     * Deactivate user account
     */
    public function deactivateUser(User $user): void
    {
        $user->update(['is_active' => false]);

        // Revoke all tokens
        $user->tokens()->delete();

        $this->auditLogService->log(
            'user_deactivated',
            'User account deactivated',
            auth()->id(),
            $user->id,
            []
        );
    }

    /**
     * Assign role to user
     */
    public function assignRole(User $user, string $roleName): void
    {
        $role = Role::where('name', $roleName)->firstOrFail();

        if (!$user->hasRole($roleName)) {
            $user->roles()->attach($role->id, [
                'assigned_at' => now(),
                'assigned_by' => auth()->id()
            ]);

            $this->auditLogService->log(
                'role_assigned',
                "Role '{$roleName}' assigned to user",
                auth()->id(),
                $user->id,
                ['role' => $roleName]
            );
        }
    }

    /**
     * Remove role from user
     */
    public function removeRole(User $user, string $roleName): void
    {
        $role = Role::where('name', $roleName)->firstOrFail();

        if ($user->hasRole($roleName)) {
            $user->roles()->detach($role->id);

            $this->auditLogService->log(
                'role_removed',
                "Role '{$roleName}' removed from user",
                auth()->id(),
                $user->id,
                ['role' => $roleName]
            );
        }
    }

    /**
     * Get user dashboard statistics
     */
    public function getUserDashboardStats(User $user): array
    {
        $cacheKey = "user:{$user->id}:dashboard_stats";

        return $this->cacheService->remember($cacheKey, 300, function () use ($user) {
            return [
                'total_vehicles' => $user->vehicles()->count(),
                'active_bookings' => $user->bookings()->where('status', 'active')->count(),
                'total_bookings' => $user->bookings()->count(),
                'total_spent' => $user->payments()->where('status', 'completed')->sum('amount'),
                'pending_payments' => $user->payments()->where('status', 'pending')->count(),
            ];
        });
    }

    /**
     * Search users
     */
    public function searchUsers(string $query): Collection
    {
        return $this->userRepository->search($query);
    }
}
