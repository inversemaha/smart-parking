<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
            Route::middleware('web')
                ->group(base_path('routes/gate.php'));
            Route::middleware('web')
                ->group(base_path('routes/visitor.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocaleMiddleware::class,
        ]);

        $middleware->api(append: [
            \App\Http\Middleware\SetLocaleMiddleware::class,
        ]);

        $middleware->alias([
            'locale' => \App\Http\Middleware\SetLocaleMiddleware::class,
            'set.language' => \App\Http\Middleware\SetLocaleMiddleware::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'role.check' => \App\Http\Middleware\RoleCheckMiddleware::class,
            'audit' => \App\Http\Middleware\AuditLogMiddleware::class,
            'audit.log' => \App\Http\Middleware\AuditLogMiddleware::class,
            'rate_limit' => \App\Http\Middleware\RateLimitMiddleware::class,
            'api.rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
            'admin.security' => \App\Http\Middleware\AdminApiSecurityMiddleware::class,
            'visitor.access' => \App\Http\Middleware\VisitorAccessMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
