<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Repository Contracts
use App\Shared\Contracts\RepositoryInterface;
use App\Shared\Contracts\ServiceInterface;
use App\Shared\Contracts\VehicleServiceInterface;
use App\Shared\Contracts\BookingServiceInterface;
use App\Shared\Contracts\PaymentServiceInterface;
use App\Shared\Contracts\UserServiceInterface;
use App\Shared\Contracts\ParkingServiceInterface;

// Repository Implementations
use App\Domains\User\Repositories\UserRepository;
use App\Domains\Vehicle\Repositories\VehicleRepository;
use App\Domains\Parking\Repositories\ParkingAreaRepository;
use App\Domains\Parking\Repositories\ParkingSlotRepository;
use App\Domains\Booking\Repositories\BookingRepository;
use App\Domains\Payment\Repositories\PaymentRepository;

// Service Implementations
use App\Domains\User\Services\UserService;
use App\Domains\Vehicle\Services\VehicleService;
use App\Domains\Parking\Services\ParkingService;
use App\Domains\Booking\Services\BookingService;
use App\Domains\Payment\Services\PaymentService;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register service contracts
        $this->registerServices();

        // Register repository contracts
        $this->registerRepositories();

        // Register shared utilities
        $this->registerSharedServices();

        // Register model aliases for backward compatibility
        $this->registerModelAliases();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register domain routes
        $this->loadDomainRoutes();

        // Register domain views
        $this->loadDomainViews();

        // Register middleware
        $this->registerMiddleware();
    }

    /**
     * Register service contracts.
     */
    protected function registerServices(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(VehicleServiceInterface::class, VehicleService::class);
        $this->app->bind(ParkingServiceInterface::class, ParkingService::class);
        $this->app->bind(BookingServiceInterface::class, BookingService::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
    }

    /**
     * Register repository contracts.
     */
    protected function registerRepositories(): void
    {
        $this->app->bind('App\Shared\Contracts\UserRepositoryInterface', UserRepository::class);
        $this->app->bind('App\Shared\Contracts\VehicleRepositoryInterface', VehicleRepository::class);
        $this->app->bind('App\Shared\Contracts\ParkingAreaRepositoryInterface', ParkingAreaRepository::class);
        $this->app->bind('App\Shared\Contracts\ParkingSlotRepositoryInterface', ParkingSlotRepository::class);
        $this->app->bind('App\Shared\Contracts\BookingRepositoryInterface', BookingRepository::class);
        $this->app->bind('App\Shared\Contracts\PaymentRepositoryInterface', PaymentRepository::class);
    }

    /**
     * Register shared utilities as singletons.
     */
    protected function registerSharedServices(): void
    {
        $this->app->singleton('response.helper', function () {
            return new \App\Shared\Utils\ResponseHelper();
        });

        $this->app->singleton('validation.helper', function () {
            return new \App\Shared\Utils\ValidationHelper();
        });

        $this->app->singleton('datetime.helper', function () {
            return new \App\Shared\Utils\DateTimeHelper();
        });
    }

    /**
     * Register model aliases for backward compatibility.
     */
    protected function registerModelAliases(): void
    {
        $models = [
            'App\Models\User' => 'App\Domains\User\Models\User',
            'App\Models\Vehicle' => 'App\Domains\Vehicle\Models\Vehicle',
            'App\Models\Booking' => 'App\Domains\Booking\Models\Booking',
            'App\Models\Payment' => 'App\Domains\Payment\Models\Payment',
            'App\Models\ParkingArea' => 'App\Domains\Parking\Models\ParkingArea',
            'App\Models\ParkingSlot' => 'App\Domains\Parking\Models\ParkingSlot',
            'App\Models\VehicleEntry' => 'App\Domains\Booking\Models\VehicleEntry',
            'App\Models\VehicleExit' => 'App\Domains\Booking\Models\VehicleExit',
            'App\Models\AuditLog' => 'App\Domains\Admin\Models\AuditLog',
            'App\Models\Role' => 'App\Domains\User\Models\Role',
            'App\Models\Permission' => 'App\Domains\User\Models\Permission',
        ];

        foreach ($models as $alias => $implementation) {
            if (!class_exists($alias) && class_exists($implementation)) {
                class_alias($implementation, $alias);
            }
        }
    }

    /**
     * Register middleware.
     */
    protected function registerMiddleware(): void
    {
        $router = $this->app['router'];

        // Register shared middleware
        $router->aliasMiddleware('audit.log', \App\Shared\Middleware\AuditLogMiddleware::class);
        $router->aliasMiddleware('set.language', \App\Shared\Middleware\SetLanguage::class);
    }

    /**
     * Load domain-specific routes.
     */
    protected function loadDomainRoutes(): void
    {
        $domains = ['User', 'Vehicle', 'Parking', 'Booking', 'Payment', 'Admin'];

        foreach ($domains as $domain) {
            $routePath = app_path("Domains/{$domain}/routes.php");
            if (file_exists($routePath)) {
                $this->loadRoutesFrom($routePath);
            }
        }
    }

    /**
     * Load domain-specific views.
     */
    protected function loadDomainViews(): void
    {
        $domains = ['User', 'Vehicle', 'Parking', 'Booking', 'Payment', 'Admin'];
        }
    }
}
