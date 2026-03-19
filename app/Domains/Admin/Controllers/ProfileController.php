<?php

namespace App\Domains\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show user profile.
     */
    public function index()
    {
        $user = auth()->user();
        $user->load('roles', 'roles.permissions');

        return view('admin.profile.index', compact('user'));
    }

    /**
     * Update user profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                \Storage::delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('admin.profile.index')
                       ->with('success', 'Profile updated successfully!');
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile.index')
                       ->with('success', 'Password changed successfully!');
    }

    /**
     * Logout all sessions.
     */
    public function logoutAll(Request $request)
    {
        // Delete all tokens/sessions for the user
        auth()->user()->sessions()->delete();

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                       ->with('success', 'All sessions have been logged out!');
    }

    /**
     * Delete account.
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|password',
        ]);

        $user = auth()->user();
        
        // Delete user data (email encrypted for GDPR)
        $user->update([
            'email' => 'deleted_' . $user->id . '@deleted.local',
            'is_active' => false,
        ]);

        // Soft delete user
        $user->delete();

        auth()->logout();

        return redirect('/')
                   ->with('success', 'Your account has been deleted.');
    }
}
