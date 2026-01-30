<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Domains\User\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Domains\Vehicle\Models\Vehicle::class => \App\Policies\VehiclePolicy::class,
        \App\Domains\Booking\Models\Booking::class => \App\Policies\BookingPolicy::class,
        \App\Domains\Payment\Models\Payment::class => \App\Policies\PaymentPolicy::class,
        \App\Domains\Parking\Models\ParkingLocation::class => \App\Policies\ParkingLocationPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        $this->registerPolicies();

        // Define gates for admin functions
        Gate::define('admin.dashboard.view', function ($user) {
            return $user->hasAnyRole(['admin', 'super-admin']);
        });

        Gate::define('manage_permissions', function ($user) {
            return $user->hasAnyRole(['admin', 'super-admin']);
        });

        Gate::define('manage_roles', function ($user) {
            return $user->hasAnyRole(['admin', 'super-admin']);
        });

        Gate::define('manage_system', function ($user) {
            return $user->hasAnyRole(['admin', 'super-admin']);
        });

        Gate::define('view_reports', function ($user) {
            return $user->hasAnyRole(['admin', 'super-admin', 'accountant']);
        });

        Gate::define('manage_vehicles', function ($user) {
            return $user->hasAnyRole(['admin', 'super-admin', 'operator']);
        });

        Gate::define('gate_operations', function ($user) {
            return $user->hasAnyRole(['admin', 'super-admin', 'gate-operator']);
        });
    }
}
