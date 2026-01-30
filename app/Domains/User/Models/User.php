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
        'mobile',
        'phone',
        'password',
        'locale',
        'preferred_language',
        'user_type',
        'status',
        'avatar_path',
        'date_of_birth',
        'gender',
        'emergency_contact_name',
        'emergency_contact_phone',
        'email_verified_at',
        'mobile_verified_at',
        'is_active',
        'deactivated_at',
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
            'mobile_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'deactivated_at' => 'datetime',
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

    // Visitor-Specific Methods

    /**
     * Check if user is a visitor
     */
    public function isVisitor(): bool
    {
        return $this->user_type === 'visitor' || $this->hasRole('visitor');
    }

    /**
     * Get user's default vehicle
     */
    public function defaultVehicle()
    {
        return $this->vehicles()->where('is_default', true)->first()
               ?? $this->vehicles()->oldest()->first();
    }

    /**
     * Get user's active bookings
     */
    public function activeBookings()
    {
        return $this->bookings()->whereIn('status', ['confirmed', 'active']);
    }

    /**
     * Get user's upcoming bookings
     */
    public function upcomingBookings()
    {
        return $this->bookings()
                   ->where('status', 'confirmed')
                   ->where('start_datetime', '>', now())
                   ->orderBy('start_datetime');
    }

    /**
     * Get user's recent bookings
     */
    public function recentBookings($limit = 5)
    {
        return $this->bookings()
                   ->whereNotIn('status', ['pending'])
                   ->orderBy('created_at', 'desc')
                   ->limit($limit);
    }

    /**
     * Get total amount spent by user
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->payments()
                   ->where('status', 'paid')
                   ->sum('amount');
    }

    /**
     * Check if user has mobile verified
     */
    public function hasMobileVerified(): bool
    {
        return !is_null($this->mobile_verified_at);
    }

    /**
     * Check if user account is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && is_null($this->deactivated_at);
    }

    /**
     * Get user's full name with fallback
     */
    public function getFullNameAttribute(): string
    {
        return $this->name ?: 'Unknown User';
    }

    /**
     * Get user avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }

        // Generate avatar based on initials
        $initials = collect(explode(' ', $this->name))->map(function ($word) {
            return strtoupper(substr($word, 0, 1));
        })->take(2)->implode('');

        return "https://ui-avatars.com/api/?name={$initials}&size=128&background=6366f1&color=fff";
    }

    /**
     * Get user's display name for different locales
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get user's stats for dashboard
     */
    public function getVisitorStats(): array
    {
        return [
            'total_vehicles' => $this->vehicles()->count(),
            'active_bookings' => $this->activeBookings()->count(),
            'total_bookings' => $this->bookings()->count(),
            'total_spent' => $this->getTotalSpentAttribute(),
            'member_since' => $this->created_at->format('M Y'),
            'last_booking' => $this->bookings()->latest()->first()?->created_at,
        ];
    }

    /**
     * Check if user can create more vehicles
     */
    public function canCreateVehicles(): bool
    {
        $maxVehicles = config('parking.max_vehicles_per_user', 5);
        return $this->vehicles()->count() < $maxVehicles;
    }

    /**
     * Check if user can create more bookings
     */
    public function canCreateBookings(): bool
    {
        $maxActiveBookings = config('parking.max_active_bookings_per_user', 3);
        return $this->activeBookings()->count() < $maxActiveBookings;
    }

    /**
     * Get user notification preferences
     */
    public function notificationPreferences(): array
    {
        $settings = $this->settings ?? [];

        return $settings['notifications'] ?? [
            'email_booking_confirmation' => true,
            'email_payment_confirmation' => true,
            'sms_booking_reminder' => true,
            'sms_payment_reminder' => true,
            'push_notifications' => true,
        ];
    }

    /**
     * User settings relationship
     */
    public function settings()
    {
        return $this->hasOne(\App\Models\UserSetting::class);
    }

    /**
     * User activity logs relationship
     */
    public function activityLogs()
    {
        return $this->hasMany(\App\Models\UserActivityLog::class);
    }
}
