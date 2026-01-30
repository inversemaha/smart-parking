<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Models\User;
use App\Domains\User\Models\Role;
use App\Domains\User\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:manage_permissions']);
    }

    /**
     * Display permissions management.
     */
    public function index(): View
    {
        $permissions = Permission::with('roles')->orderBy('module')->orderBy('name')->get();
        $roles = Role::with('permissions')->get();

        return view('admin.permissions.index', compact('permissions', 'roles'));
    }

    /**
     * Display role management.
     */
    public function roles(): View
    {
        $roles = Role::with(['permissions', 'users'])->get();
        $permissions = Permission::orderBy('module')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Display user role assignments.
     */
    public function users(): View
    {
        $users = User::with('roles')->orderBy('name')->paginate(20);
        $roles = Role::all();

        return view('admin.users.roles', compact('users', 'roles'));
    }

    /**
     * Create new role.
     */
    public function createRole(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'description' => $request->description,
            'is_active' => true
        ]);

        if ($request->permissions) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('admin.permissions.roles')
            ->with('success', 'Role created successfully!');
    }

    /**
     * Update role permissions.
     */
    public function updateRole(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.permissions.roles')
            ->with('success', 'Role updated successfully!');
    }

    /**
     * Assign role to user.
     */
    public function assignUserRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        if (!$user->hasRole($request->role_id)) {
            $user->assignRole($request->role_id, auth()->id());
        }

        return redirect()->route('admin.permissions.users')
            ->with('success', 'Role assigned successfully!');
    }

    /**
     * Remove role from user.
     */
    public function removeUserRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->removeRole($request->role_id);

        return redirect()->route('admin.permissions.users')
            ->with('success', 'Role removed successfully!');
    }

    /**
     * Create new permission.
     */
    public function createPermission(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'module' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'module' => $request->module,
            'description' => $request->description,
            'is_active' => true
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully!');
    }
}
