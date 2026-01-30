<?php

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Booking\Models\Booking;
use App\Domains\Payment\Models\Payment;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'locale',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * User roles relationship.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withPivot('assigned_at', 'assigned_by')
                    ->withTimestamps();
    }

    /**
     * User sessions relationship.
     */
    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * User devices relationship.
     */
    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    /**
     * User access logs relationship.
     */
    public function accessLogs()
    {
        return $this->hasMany(UserAccessLog::class);
    }

    /**
     * User vehicles relationship.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * User bookings relationship.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * User payments relationship.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles()->where('name', $role)->exists();
        }

        return $this->roles()->where('id', $role)->exists();
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole($roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permission): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    /**
     * Check if user has permission to access something (alias for hasPermission).
     */
    public function hasPermissionTo($permission): bool
    {
        return $this->hasPermission($permission);
    }

    /**
     * Check if user can perform an action (Laravel Gate style).
     */
    public function can($abilities, $arguments = []): bool
    {
        // For single permission strings (our custom logic)
        if (is_string($abilities)) {
            return $this->hasPermission($abilities);
        }

        // For arrays or other Laravel Gate logic, delegate to parent
        return parent::can($abilities, $arguments);
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissions) {
            $query->whereIn('name', $permissions);
        })->exists();
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get all permissions for the user through their roles.
     */
    public function getAllPermissions()
    {
        return Permission::whereIn('id',
            $this->roles()->with('permissions')->get()
                ->pluck('permissions.*.id')->flatten()->unique()
        )->get();
    }

    /**
     * Check if user is super admin (has all permissions).
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Assign role to user.
     */
    public function assignRole($roleIdOrName, $assignedBy = null)
    {
        // If it's a string, find the role by name
        if (is_string($roleIdOrName)) {
            $role = \App\Domains\User\Models\Role::where('name', $roleIdOrName)->first();
            if (!$role) {
                throw new \InvalidArgumentException("Role '{$roleIdOrName}' not found");
            }
            $roleId = $role->id;
        } else {
            $roleId = $roleIdOrName;
        }

        $this->roles()->attach($roleId, [
            'assigned_at' => now(),
            'assigned_by' => $assignedBy ?? auth()->id(),
        ]);
    }

    /**
     * Remove role from user.
     */
    public function removeRole($roleId)
    {
        $this->roles()->detach($roleId);
    }

    /**
     * Get user's active session.
     */
    public function activeSession()
    {
        return $this->sessions()->where('is_active', true)->latest('last_activity_at');
    }

    /**
     * Get user's current locale with fallback.
     */
    public function getLocaleAttribute($value)
    {
        return $value ?: config('app.locale', 'en');
    }
}
