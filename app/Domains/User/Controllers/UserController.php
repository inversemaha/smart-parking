<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Models\User;
use App\Domains\User\Services\UserService;
use App\Domains\Vehicle\Services\VehicleService;
use App\Domains\Booking\Services\BookingService;
use App\Shared\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected VehicleService $vehicleService,
        protected BookingService $bookingService,
        protected NotificationService $notificationService
    ) {
        $this->middleware(['auth:sanctum', 'role.check']);
    }

    /**
     * Get all users (Admin/Manager only)
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $users = $this->userService->getAllUsers($request->all());

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Get single user details
     */
    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return response()->json([
            'success' => true,
            'data' => $user->load(['roles', 'vehicles', 'bookings'])
        ]);
    }

    /**
     * Update user details
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,'.$user->id,
            'phone' => 'string|unique:users,phone,'.$user->id,
            'locale' => 'in:en,bn'
        ]);

        $updatedUser = $this->userService->updateUser($user, $request->all());

        return response()->json([
            'success' => true,
            'message' => __('user.updated_successfully'),
            'data' => $updatedUser
        ]);
    }

    /**
     * Delete user
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $this->userService->deleteUser($user);

        return response()->json([
            'success' => true,
            'message' => __('user.deleted_successfully')
        ]);
    }

    /**
     * Upload user avatar
     */
    public function uploadAvatar(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $avatarPath = $this->userService->updateAvatar($user, $request->file('avatar'));

        return response()->json([
            'success' => true,
            'message' => __('user.avatar_updated'),
            'avatar_url' => $avatarPath
        ]);
    }

    /**
     * Get user vehicles
     */
    public function getUserVehicles(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        $vehicles = $this->vehicleService->getUserVehicles($user->id);

        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
    }

    /**
     * Get user bookings
     */
    public function getUserBookings(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        $bookings = $this->bookingService->getUserBookings($user->id);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * Get user profile
     */
    public function profile(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $user->load(['roles', 'vehicles', 'bookings'])
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,'.$user->id,
            'phone' => 'string|unique:users,phone,'.$user->id,
            'locale' => 'in:en,bn'
        ]);

        $updatedUser = $this->userService->updateUser($user, $request->all());

        return response()->json([
            'success' => true,
            'message' => __('user.profile_updated'),
            'data' => $updatedUser
        ]);
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => __('user.current_password_incorrect')
            ], 422);
        }

        $this->userService->changePassword($user, $request->password);

        return response()->json([
            'success' => true,
            'message' => __('user.password_changed')
        ]);
    }

    /**
     * Upload user documents
     */
    public function uploadDocuments(Request $request): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'documents' => 'required|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $documents = $this->userService->uploadDocuments($user, $request->file('documents'));

        return response()->json([
            'success' => true,
            'message' => __('user.documents_uploaded'),
            'data' => $documents
        ]);
    }

    /**
     * Get user notifications
     */
    public function getNotifications(): JsonResponse
    {
        $user = Auth::user();
        $notifications = $this->notificationService->getUserNotifications($user->id);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead(Request $request, $notificationId): JsonResponse
    {
        $user = Auth::user();
        $this->notificationService->markAsRead($user->id, $notificationId);

        return response()->json([
            'success' => true,
            'message' => __('notification.marked_as_read')
        ]);
    }

    /**
     * Change user language
     */
    public function changeLanguage(Request $request): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'locale' => 'required|in:en,bn'
        ]);

        $this->userService->changeLanguage($user, $request->locale);

        return response()->json([
            'success' => true,
            'message' => __('user.language_changed'),
            'locale' => $request->locale
        ]);
    }
}
