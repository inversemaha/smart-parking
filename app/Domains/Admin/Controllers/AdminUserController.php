<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Models\User;
use App\Domains\Auth\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage_users|admin');
    }

    /**
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'suspended') {
                $query->whereNotNull('suspended_at');
            } elseif ($request->status === 'deactivated') {
                $query->whereNotNull('deactivated_at');
            } elseif ($request->status === 'active') {
                $query->whereNull('suspended_at')->whereNull('deactivated_at');
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'mobile' => 'nullable|string|max:20',
            'user_type' => 'required|in:visitor,admin,operator',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'user_type' => $validated['user_type'],
                'password' => Hash::make($validated['password']),
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return redirect()
                ->route('admin.users.show', $user->id)
                ->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to create user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('roles', 'bookings.parkingLocation');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
        ]);

        try {
            $user->update([
                'name' => $validated['name'],
                'mobile' => $validated['mobile'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Update roles if provided
            if (isset($validated['roles'])) {
                $user->roles()->sync($validated['roles']);
            }

            return redirect()
                ->route('admin.users.show', $user->id)
                ->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to update user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Suspend a user.
     */
    public function suspend(Request $request, User $user)
    {
        try {
            $user->update([
                'suspended_at' => now(),
                'suspension_reason' => $request->input('reason', 'Suspended by admin'),
            ]);

            return redirect()
                ->route('admin.users.show', $user->id)
                ->with('success', 'User suspended successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to suspend user: ' . $e->getMessage());
        }
    }

    /**
     * Activate a suspended user.
     */
    public function activate(User $user)
    {
        try {
            $user->update([
                'suspended_at' => null,
                'suspension_reason' => null,
            ]);

            return redirect()
                ->route('admin.users.show', $user->id)
                ->with('success', 'User activated successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to activate user: ' . $e->getMessage());
        }
    }
}
