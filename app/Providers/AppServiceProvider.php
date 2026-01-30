<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Policies\AdminPolicy;
use App\Domains\Admin\Models\AuditLog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Admin services
        $this->app->singleton(\App\Domains\Admin\Services\DashboardService::class);
        $this->app->singleton(\App\Domains\Admin\Services\ReportService::class);
        $this->app->singleton(\App\Domains\Admin\Services\AdminCacheService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for MySQL
        Schema::defaultStringLength(191);

        // Register Admin policies
        Gate::policy(\App\Domains\User\Models\User::class, AdminPolicy::class);

        // Define additional gates for admin functionality
        Gate::define('admin.access', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('admin.dashboard.view', function ($user) {
            return $user->hasRole('admin') || $user->hasPermission('admin.dashboard.view');
        });

        Gate::define('admin.emergency.access', function ($user) {
            return $user->hasRole('admin') || $user->hasPermission('admin.emergency.manage');
        });

        // Define audit logging observer if needed
        if (class_exists(AuditLog::class)) {
            // AuditLog::observe(\App\Observers\AuditLogObserver::class);
        }
    }
}
