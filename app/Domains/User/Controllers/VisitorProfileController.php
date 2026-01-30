<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Services\VisitorService;
use App\Shared\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class VisitorProfileController extends Controller
{
    public function __construct(
        protected VisitorService $visitorService,
        protected FileUploadService $fileUploadService
    ) {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show visitor profile
     */
    public function index(): View
    {
        $user = auth()->user();
        $stats = $this->visitorService->getVisitorStats($user->id);

        return view('visitor.profile.index', compact('user', 'stats'));
    }

    /**
     * Show profile edit form
     */
    public function edit(): View
    {
        $user = auth()->user();

        return view('visitor.profile.edit', compact('user'));
    }

    /**
     * Update visitor profile
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'mobile' => ['required', 'string', 'regex:/^01[3-9]\d{8}$/', 'unique:users,mobile,' . $user->id],
            'date_of_birth' => ['nullable', 'date', 'before:18 years ago'],
            'gender' => ['nullable', 'in:male,female,other'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'regex:/^01[3-9]\d{8}$/'],
            'preferred_language' => ['required', 'in:en,bn'],
        ]);

        $updatedUser = $this->visitorService->updateProfile($user->id, $request->only([
            'name',
            'email',
            'mobile',
            'date_of_birth',
            'gender',
            'emergency_contact_name',
            'emergency_contact_phone',
            'preferred_language'
        ]));

        return redirect()->route('visitor.profile.index')
                        ->with('success', __('user.profile_updated'));
    }

    /**
     * Update visitor password
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        $success = $this->visitorService->updatePassword(
            auth()->id(),
            $request->current_password,
            $request->password
        );

        if (!$success) {
            return back()->withErrors(['current_password' => __('auth.password_incorrect')]);
        }

        return redirect()->route('visitor.profile.index')
                        ->with('success', __('auth.password_updated'));
    }

    /**
     * Update visitor avatar
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = auth()->user();

        // Delete old avatar if exists
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // Upload new avatar
        $avatarPath = $this->fileUploadService->uploadFile(
            $request->file('avatar'),
            'avatars',
            'visitor_' . $user->id
        );

        // Update user avatar path
        $this->visitorService->updateProfile($user->id, [
            'avatar_path' => $avatarPath
        ]);

        return redirect()->route('visitor.profile.index')
                        ->with('success', __('user.avatar_updated'));
    }

    /**
     * Remove visitor avatar
     */
    public function removeAvatar(): RedirectResponse
    {
        $user = auth()->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);

            $this->visitorService->updateProfile($user->id, [
                'avatar_path' => null
            ]);
        }

        return redirect()->route('visitor.profile.index')
                        ->with('success', __('user.avatar_removed'));
    }

    /**
     * Deactivate visitor account
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
            'confirm_deactivation' => ['required', 'accepted'],
        ]);

        $user = auth()->user();

        // Verify password
        if (!password_verify($request->password, $user->password)) {
            return back()->withErrors(['password' => __('auth.password_incorrect')]);
        }

        // Deactivate account
        $this->visitorService->deactivateAccount($user->id);

        // Logout user
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')
                        ->with('success', __('user.account_deactivated'));
    }

    // API Methods

    /**
     * Get visitor profile via API
     */
    public function apiProfile(): JsonResponse
    {
        $user = auth()->user();
        $stats = $this->visitorService->getVisitorStats($user->id);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'stats' => $stats
            ]
        ]);
    }

    /**
     * Update visitor profile via API
     */
    public function apiUpdateProfile(Request $request): JsonResponse
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $user->id],
            'mobile' => ['sometimes', 'string', 'regex:/^01[3-9]\d{8}$/', 'unique:users,mobile,' . $user->id],
            'date_of_birth' => ['nullable', 'date', 'before:18 years ago'],
            'gender' => ['nullable', 'in:male,female,other'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'regex:/^01[3-9]\d{8}$/'],
            'preferred_language' => ['sometimes', 'in:en,bn'],
        ]);

        $updatedUser = $this->visitorService->updateProfile(
            $user->id,
            $request->only([
                'name',
                'email',
                'mobile',
                'date_of_birth',
                'gender',
                'emergency_contact_name',
                'emergency_contact_phone',
                'preferred_language'
            ])
        );

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $updatedUser,
                'message' => __('user.profile_updated')
            ]
        ]);
    }
}
