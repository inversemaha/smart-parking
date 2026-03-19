<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/_boost/browser-logs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'boost.browser-logs',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/sanctum/csrf-cookie' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sanctum.csrf-cookie',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/auth/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.auth.register',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/auth/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.auth.login',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/auth/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.auth.forgot.password',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/auth/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.auth.reset.password',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/auth/verify-email' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.auth.verify.email',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/auth/resend-verification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.auth.resend.verification',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/parking-locations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.parking.locations',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/parking-rates' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.parking.rates',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/gate/entry' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.gate.entry',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/gate/exit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.gate.exit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/gate/scan' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.gate.scan',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/gate/logs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.gate.logs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/user/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.user.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'api.user.profile.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/user/password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.user.password.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/user/avatar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.user.avatar.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/user/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.user.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/user/account' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.user.account.delete',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/vehicles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.vehicles.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'api.vehicles.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/bookings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.bookings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'api.bookings.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/bookings/calculate-cost' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.bookings.calculate.cost',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/bookings/available-slots' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.bookings.slots.available',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/bookings/history' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.bookings.history',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.payments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/payments/webhook/sslcommerz' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.payments.webhook.sslcommerz',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/devices' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.devices.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/devices/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.devices.register',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/analytics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.analytics',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/vehicles/pending' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.vehicles.pending',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/system/health' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.system.health',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/system/logs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.system.logs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/system/cache/clear' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.system.cache.clear',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/system/queue/status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.system.queue.status',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/system/queue/restart' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.system.queue.restart',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/reports/revenue' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.reports.revenue',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/reports/bookings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.reports.bookings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/reports/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.reports.users',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/v1/admin/reports/vehicles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.reports.vehicles',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9c8Q0mhbikzDzdTc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'welcome',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/home' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'home',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/vehicles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vehicles.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'vehicles.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/vehicles/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vehicles.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/bookings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'bookings.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/bookings/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookings.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/bookings/slots/available' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookings.slots.available',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/payments/success' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payments.gateway.success',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/payments/failure' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payments.gateway.failure',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/payments/cancel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payments.gateway.cancel',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'profile.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'profile.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'profile.password.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/profile/avatar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'profile.avatar.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.dashboard.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/permissions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.permissions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.permissions.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/permissions/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.permissions.users',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/roles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.permissions.roles',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.roles.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/users/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.payments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/invoices' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.invoices.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/bookings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bookings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/parking-locations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.parking-locations.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.parking-locations.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/parking-locations/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.parking-locations.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/vehicles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vehicles.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vehicles.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/vehicles/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vehicles.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.profile.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.profile.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'admin.profile.delete-account',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/profile/change-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.profile.change-password',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/profile/logout-all' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.profile.logout-all',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/system/health' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system.health',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/system/logs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system.logs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/system/cache/clear' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system.cache.clear',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.reports.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/reports/revenue' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.reports.revenue',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/reports/bookings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.reports.bookings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/reports/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.reports.users',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Ng8ic8GLlgKH417l',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8G7plWlRjy6CsZb6',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'password.email',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/verify-email' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.notice',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/email/verification-notification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.send',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/confirm-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.confirm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WOnEf9vHpukmq3yL',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/parking/locations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.parking.locations',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/parking/slots/check-availability' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.parking.check.availability',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.register',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.register.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.login.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/verify-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.verify.otp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.verify.otp.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/resend-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.resend.otp',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.password.request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.password.email',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.password.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.profile.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.profile.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.profile.destroy',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/profile/edit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.profile.edit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/profile/password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.profile.password.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/profile/avatar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.profile.avatar.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.profile.avatar.remove',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/vehicles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.vehicles.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.vehicles.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/vehicles/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.vehicles.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/bookings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.bookings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.bookings.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/bookings/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.bookings.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/bookings/api/slots/available' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.bookings.api.slots.available',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/bookings/api/calculate-cost' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.bookings.api.calculate.cost',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/bookings/api/validate-booking' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.bookings.api.validate.booking',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/payments/gateway/success' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.gateway.success',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/payments/gateway/failure' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.gateway.failure',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/payments/gateway/cancel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.gateway.cancel',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/visitor/payments/gateway/webhook' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.gateway.webhook',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/visitor/auth/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.login',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/visitor/auth/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.register',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/visitor/auth/refresh' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.refresh',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/visitor/auth/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/visitor/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.profile.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/visitor/vehicles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.vehicles.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.vehicles.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/visitor/bookings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.bookings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.bookings.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/visitor/payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.payments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/visitor/parking/locations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.parking.locations',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/visitor/parking/check-availability' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.parking.check.availability',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/gate/entry' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'gate.entry',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/gate/exit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'gate.exit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/gate/scan' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'gate.scan',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/gate/logs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'gate.logs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/gates' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.gates.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/gates/entries' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.gates.entries',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/gates/exits' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.gates.exits',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/gate-entries' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.gate-entries.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/gate-exits' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.gate-exits.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/docs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.docs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/a(?|pi/(?|v(?|1/(?|vehicles/([^/]++)(?|(*:47)|/(?|documents(*:67)|verification\\-status(*:94)))|bookings/([^/]++)(?|(*:123)|/(?|extend(*:141)|confirm(*:156)))|payments/(?|booking/([^/]++)(*:194)|([^/]++)(?|(*:213)|/(?|receipt(*:232)|status(*:246))))|devices/(?|([^/]++)(*:276)|all(*:287))|admin/(?|vehicles/([^/]++)/(?|verify(*:332)|reject(*:346)|documents(*:363))|reports/export/([^/]++)(*:395)))|isitor/(?|vehicles/([^/]++)(?|(*:435))|bookings/([^/]++)(?|(*:464))|pa(?|yments/([^/]++)(*:493)|rking/locations/([^/]++)(*:525))))|(.*)(*:540))|dmin/(?|users/([^/]++)(?|/(?|roles(?|(*:586))|edit(*:599)|suspend(*:614)|activate(*:630))|(*:639))|roles/([^/]++)(*:662)|pa(?|yments/([^/]++)(?|(*:693)|/refund(*:708))|rking\\-locations/([^/]++)(?|(*:745)|/edit(*:758)|(*:766)))|invoices/([^/]++)(?|(*:796)|/(?|download(*:816)|mark\\-paid(*:834)))|bookings/([^/]++)(?|(*:864)|/c(?|onfirm(*:883)|ancel(*:896)))|vehicles/(?|([^/]++)(?|(*:929)|/edit(*:942)|(*:950))|pending(*:966)|([^/]++)/(?|verify(*:992)|reject(*:1006)))))|/language/([^/]++)(*:1037)|/dashboard/(?|vehicles/([^/]++)(?|(*:1080)|/(?|edit(*:1097)|documents(*:1115))|(*:1125))|bookings/(?|([^/]++)(?|(*:1158)|/(?|cancel(*:1177)|extend(*:1192)))|calculate\\-cost(*:1218))|payments/(?|booking/([^/]++)(?|(*:1259))|([^/]++)(?|(*:1280)|/receipt(*:1297))))|/reset\\-password/([^/]++)(*:1334)|/v(?|erify\\-email/([^/]++)/([^/]++)(*:1378)|isitor/(?|reset\\-password/([^/]++)(*:1421)|vehicles/([^/]++)(?|(*:1450)|/(?|edit(*:1467)|set\\-default(*:1488)|documents(*:1506))|(*:1516))|bookings/([^/]++)(?|(*:1546)|/(?|cancel(*:1565)|extend(*:1580)|receipt(*:1596)))|payments/(?|booking/([^/]++)(?|(*:1638))|([^/]++)(?|(*:1659)|/(?|invoice(*:1679)|download(*:1696)))|status/([^/]++)(*:1722))))|/parking/locations/([^/]++)(?|(*:1764)|/availability(*:1786))|/storage/(.*)(*:1809))/?$}sDu',
    ),
    3 => 
    array (
      47 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.vehicles.show',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'api.vehicles.update',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'api.vehicles.destroy',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      67 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.vehicles.upload.documents',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      94 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.vehicles.verification.status',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      123 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.bookings.show',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'api.bookings.update',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'api.bookings.cancel',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      141 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.bookings.extend',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      156 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.bookings.confirm',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      194 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.payments.create',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      213 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.payments.show',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      232 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.payments.receipt',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      246 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.payments.status',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      276 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.devices.revoke',
          ),
          1 => 
          array (
            0 => 'device',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      287 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.devices.revoke.all',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      332 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.vehicles.verify',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      346 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.vehicles.reject',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      363 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.vehicles.documents',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      395 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.admin.reports.export',
          ),
          1 => 
          array (
            0 => 'type',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      435 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.vehicles.show',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.vehicles.update',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.vehicles.destroy',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      464 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.bookings.show',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.bookings.update',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.bookings.destroy',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      493 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.payments.show',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      525 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.visitor.api.parking.location.details',
          ),
          1 => 
          array (
            0 => 'location',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      540 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ltYezhn2rCf9lJmR',
          ),
          1 => 
          array (
            0 => 'fallbackPlaceholder',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      586 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.roles.assign',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.roles.remove',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      599 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.edit',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      614 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.suspend',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      630 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.activate',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      639 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.show',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.update',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      662 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.roles.update',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      693 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.payments.show',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      708 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.payments.refund',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      745 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.parking-locations.show',
          ),
          1 => 
          array (
            0 => 'parkingLocation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      758 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.parking-locations.edit',
          ),
          1 => 
          array (
            0 => 'parkingLocation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      766 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.parking-locations.update',
          ),
          1 => 
          array (
            0 => 'parkingLocation',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.parking-locations.destroy',
          ),
          1 => 
          array (
            0 => 'parkingLocation',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      796 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.invoices.show',
          ),
          1 => 
          array (
            0 => 'invoice',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      816 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.invoices.download',
          ),
          1 => 
          array (
            0 => 'invoice',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      834 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.invoices.mark-paid',
          ),
          1 => 
          array (
            0 => 'invoice',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      864 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bookings.show',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      883 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bookings.confirm',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      896 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bookings.cancel',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      929 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vehicles.show',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      942 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vehicles.edit',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      950 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vehicles.update',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vehicles.destroy',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      966 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vehicles.pending',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      992 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vehicles.verify',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1006 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.vehicles.reject',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1037 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'language.switch',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1080 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vehicles.show',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1097 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vehicles.edit',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1115 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vehicles.upload.documents',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1125 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vehicles.update',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'vehicles.destroy',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1158 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookings.show',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1177 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookings.cancel',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1192 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookings.extend',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1218 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'bookings.calculate.cost',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1259 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payments.create',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'payments.store',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1280 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payments.show',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1297 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payments.receipt',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1334 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.reset',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1378 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.verify',
          ),
          1 => 
          array (
            0 => 'id',
            1 => 'hash',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1421 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.password.reset',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1450 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.vehicles.show',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1467 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.vehicles.edit',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1488 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.vehicles.set.default',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1506 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.vehicles.upload.documents',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1516 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.vehicles.update',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.vehicles.destroy',
          ),
          1 => 
          array (
            0 => 'vehicle',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1546 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.bookings.show',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1565 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.bookings.cancel',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1580 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.bookings.extend',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1596 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.bookings.receipt',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1638 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.create',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.store',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1659 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.show',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1679 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.invoice',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1696 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.download',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1722 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.payments.status',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1764 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.parking.location.details',
          ),
          1 => 
          array (
            0 => 'location',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1786 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'visitor.parking.availability',
          ),
          1 => 
          array (
            0 => 'location',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1809 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'boost.browser-logs' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_boost/browser-logs',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:1349:"function (\\Illuminate\\Http\\Request $request) {
            $logs = $request->input(\'logs\', []);

            /** @var Logger $logger */
            $logger = \\Illuminate\\Support\\Facades\\Log::channel(\'browser\');

            /**
             *  @var array{
             *      type: \'error\'|\'warn\'|\'info\'|\'log\'|\'table\'|\'window_error\'|\'uncaught_error\'|\'unhandled_rejection\',
             *      timestamp: string,
             *      data: array,
             *      url:string,
             *      userAgent:string
             *  } $log */
            foreach ($logs as $log) {
                $logger->write(
                    level: match ($log[\'type\']) {
                        \'warn\' => \'warning\',
                        \'log\', \'table\' => \'debug\',
                        \'window_error\', \'uncaught_error\', \'unhandled_rejection\' => \'error\',
                        default => $log[\'type\']
                    },
                    message: self::buildLogMessageFromData($log[\'data\']),
                    context: [
                        \'url\' => $log[\'url\'],
                        \'user_agent\' => $log[\'userAgent\'] ?: null,
                        \'timestamp\' => $log[\'timestamp\'] ?: now()->toIso8601String(),
                    ]
                );
            }

            return response()->json([\'status\' => \'logged\']);
        }";s:5:"scope";s:34:"Laravel\\Boost\\BoostServiceProvider";s:4:"this";N;s:4:"self";s:32:"000000000000071e0000000000000000";}}',
        'as' => 'boost.browser-logs',
        'excluded_middleware' => 
        array (
          0 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'sanctum.csrf-cookie' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sanctum/csrf-cookie',
      'action' => 
      array (
        'uses' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'controller' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'namespace' => NULL,
        'prefix' => 'sanctum',
        'where' => 
        array (
        ),
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'sanctum.csrf-cookie',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.auth.register' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/auth/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@register',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@register',
        'as' => 'api.auth.register',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.auth.login' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/auth/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@login',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@login',
        'as' => 'api.auth.login',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.auth.forgot.password' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/auth/forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@forgotPassword',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@forgotPassword',
        'as' => 'api.auth.forgot.password',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.auth.reset.password' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/auth/reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@resetPassword',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@resetPassword',
        'as' => 'api.auth.reset.password',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.auth.verify.email' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/auth/verify-email',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@verifyEmail',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@verifyEmail',
        'as' => 'api.auth.verify.email',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.auth.resend.verification' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/auth/resend-verification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@resendVerification',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@resendVerification',
        'as' => 'api.auth.resend.verification',
        'namespace' => NULL,
        'prefix' => 'api/v1/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.parking.locations' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/parking-locations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@getAvailableLocations',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@getAvailableLocations',
        'namespace' => NULL,
        'prefix' => 'api/v1',
        'where' => 
        array (
        ),
        'as' => 'api.parking.locations',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.parking.rates' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/parking-rates',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@getParkingRates',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@getParkingRates',
        'namespace' => NULL,
        'prefix' => 'api/v1',
        'where' => 
        array (
        ),
        'as' => 'api.parking.rates',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.gate.entry' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/gate/entry',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@vehicleEntry',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@vehicleEntry',
        'as' => 'api.gate.entry',
        'namespace' => NULL,
        'prefix' => 'api/v1/gate',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.gate.exit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/gate/exit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@vehicleExit',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@vehicleExit',
        'as' => 'api.gate.exit',
        'namespace' => NULL,
        'prefix' => 'api/v1/gate',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.gate.scan' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/gate/scan',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@scanQrCode',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@scanQrCode',
        'as' => 'api.gate.scan',
        'namespace' => NULL,
        'prefix' => 'api/v1/gate',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.gate.logs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/gate/logs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@getGateLogs',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@getGateLogs',
        'as' => 'api.gate.logs',
        'namespace' => NULL,
        'prefix' => 'api/v1/gate',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.user.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/user/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@profile',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@profile',
        'as' => 'api.user.profile',
        'namespace' => NULL,
        'prefix' => 'api/v1/user',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.user.profile.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/user/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@updateProfile',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@updateProfile',
        'as' => 'api.user.profile.update',
        'namespace' => NULL,
        'prefix' => 'api/v1/user',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.user.password.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/user/password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@updatePassword',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@updatePassword',
        'as' => 'api.user.password.update',
        'namespace' => NULL,
        'prefix' => 'api/v1/user',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.user.avatar.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/user/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@updateAvatar',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@updateAvatar',
        'as' => 'api.user.avatar.update',
        'namespace' => NULL,
        'prefix' => 'api/v1/user',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.user.logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/user/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@logout',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@logout',
        'as' => 'api.user.logout',
        'namespace' => NULL,
        'prefix' => 'api/v1/user',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.user.account.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/user/account',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@deleteAccount',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@deleteAccount',
        'as' => 'api.user.account.delete',
        'namespace' => NULL,
        'prefix' => 'api/v1/user',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.vehicles.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@index',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@index',
        'as' => 'api.vehicles.index',
        'namespace' => NULL,
        'prefix' => 'api/v1/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.vehicles.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@store',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@store',
        'as' => 'api.vehicles.store',
        'namespace' => NULL,
        'prefix' => 'api/v1/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.vehicles.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@show',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@show',
        'as' => 'api.vehicles.show',
        'namespace' => NULL,
        'prefix' => 'api/v1/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.vehicles.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@update',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@update',
        'as' => 'api.vehicles.update',
        'namespace' => NULL,
        'prefix' => 'api/v1/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.vehicles.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@destroy',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@destroy',
        'as' => 'api.vehicles.destroy',
        'namespace' => NULL,
        'prefix' => 'api/v1/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.vehicles.upload.documents' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/vehicles/{vehicle}/documents',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@uploadDocuments',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@uploadDocuments',
        'as' => 'api.vehicles.upload.documents',
        'namespace' => NULL,
        'prefix' => 'api/v1/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.vehicles.verification.status' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/vehicles/{vehicle}/verification-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@getVerificationStatus',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\VehicleController@getVerificationStatus',
        'as' => 'api.vehicles.verification.status',
        'namespace' => NULL,
        'prefix' => 'api/v1/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.bookings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@index',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@index',
        'as' => 'api.bookings.index',
        'namespace' => NULL,
        'prefix' => 'api/v1/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.bookings.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@store',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@store',
        'as' => 'api.bookings.store',
        'namespace' => NULL,
        'prefix' => 'api/v1/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.bookings.calculate.cost' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/bookings/calculate-cost',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@calculateCost',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@calculateCost',
        'as' => 'api.bookings.calculate.cost',
        'namespace' => NULL,
        'prefix' => 'api/v1/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.bookings.slots.available' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/bookings/available-slots',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@getAvailableSlots',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@getAvailableSlots',
        'as' => 'api.bookings.slots.available',
        'namespace' => NULL,
        'prefix' => 'api/v1/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.bookings.history' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/bookings/history',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@getBookingHistory',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@getBookingHistory',
        'as' => 'api.bookings.history',
        'namespace' => NULL,
        'prefix' => 'api/v1/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.bookings.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/bookings/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@show',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@show',
        'as' => 'api.bookings.show',
        'namespace' => NULL,
        'prefix' => 'api/v1/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.bookings.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/v1/bookings/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@update',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@update',
        'as' => 'api.bookings.update',
        'namespace' => NULL,
        'prefix' => 'api/v1/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.bookings.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/bookings/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@cancel',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@cancel',
        'as' => 'api.bookings.cancel',
        'namespace' => NULL,
        'prefix' => 'api/v1/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.bookings.extend' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/bookings/{booking}/extend',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@extend',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@extend',
        'as' => 'api.bookings.extend',
        'namespace' => NULL,
        'prefix' => 'api/v1/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.bookings.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/bookings/{booking}/confirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@confirm',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\BookingController@confirm',
        'as' => 'api.bookings.confirm',
        'namespace' => NULL,
        'prefix' => 'api/v1/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.payments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@index',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@index',
        'as' => 'api.payments.index',
        'namespace' => NULL,
        'prefix' => 'api/v1/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.payments.create' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/payments/booking/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@createPayment',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@createPayment',
        'as' => 'api.payments.create',
        'namespace' => NULL,
        'prefix' => 'api/v1/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.payments.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/payments/{payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@show',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@show',
        'as' => 'api.payments.show',
        'namespace' => NULL,
        'prefix' => 'api/v1/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.payments.receipt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/payments/{payment}/receipt',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@receipt',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@receipt',
        'as' => 'api.payments.receipt',
        'namespace' => NULL,
        'prefix' => 'api/v1/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.payments.status' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/payments/{payment}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@getPaymentStatus',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@getPaymentStatus',
        'as' => 'api.payments.status',
        'namespace' => NULL,
        'prefix' => 'api/v1/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.payments.webhook.sslcommerz' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/payments/webhook/sslcommerz',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@handleSSLCommerzWebhook',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\PaymentController@handleSSLCommerzWebhook',
        'as' => 'api.payments.webhook.sslcommerz',
        'namespace' => NULL,
        'prefix' => 'api/v1/payments',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
          0 => 'auth:sanctum',
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.devices.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/devices',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@getDevices',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@getDevices',
        'as' => 'api.devices.index',
        'namespace' => NULL,
        'prefix' => 'api/v1/devices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.devices.register' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/devices/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@registerDevice',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@registerDevice',
        'as' => 'api.devices.register',
        'namespace' => NULL,
        'prefix' => 'api/v1/devices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.devices.revoke' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/devices/{device}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@revokeDevice',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@revokeDevice',
        'as' => 'api.devices.revoke',
        'namespace' => NULL,
        'prefix' => 'api/v1/devices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.devices.revoke.all' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/v1/devices/all',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'api.rate.limit',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@revokeAllDevices',
        'controller' => 'App\\Domains\\User\\Controllers\\Api\\AuthController@revokeAllDevices',
        'as' => 'api.devices.revoke.all',
        'namespace' => NULL,
        'prefix' => 'api/v1/devices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getDashboardStats',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getDashboardStats',
        'as' => 'api.admin.dashboard',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.analytics' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/analytics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getAnalytics',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getAnalytics',
        'as' => 'api.admin.analytics',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.vehicles.pending' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/vehicles/pending',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getPendingVehicles',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getPendingVehicles',
        'as' => 'api.admin.vehicles.pending',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.vehicles.verify' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/admin/vehicles/{vehicle}/verify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@verifyVehicle',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@verifyVehicle',
        'as' => 'api.admin.vehicles.verify',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.vehicles.reject' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/admin/vehicles/{vehicle}/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@rejectVehicle',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@rejectVehicle',
        'as' => 'api.admin.vehicles.reject',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.vehicles.documents' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/vehicles/{vehicle}/documents',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getVehicleDocuments',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getVehicleDocuments',
        'as' => 'api.admin.vehicles.documents',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.system.health' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/system/health',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getSystemHealth',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getSystemHealth',
        'as' => 'api.admin.system.health',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/system',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.system.logs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/system/logs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getSystemLogs',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getSystemLogs',
        'as' => 'api.admin.system.logs',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/system',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.system.cache.clear' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/admin/system/cache/clear',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@clearCache',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@clearCache',
        'as' => 'api.admin.system.cache.clear',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/system',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.system.queue.status' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/system/queue/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getQueueStatus',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getQueueStatus',
        'as' => 'api.admin.system.queue.status',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/system',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.system.queue.restart' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/v1/admin/system/queue/restart',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@restartQueue',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@restartQueue',
        'as' => 'api.admin.system.queue.restart',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/system',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.reports.revenue' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/reports/revenue',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getRevenueReport',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getRevenueReport',
        'as' => 'api.admin.reports.revenue',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.reports.bookings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/reports/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getBookingReport',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getBookingReport',
        'as' => 'api.admin.reports.bookings',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.reports.users' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/reports/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getUserReport',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getUserReport',
        'as' => 'api.admin.reports.users',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.reports.vehicles' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/reports/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getVehicleReport',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@getVehicleReport',
        'as' => 'api.admin.reports.vehicles',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.admin.reports.export' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/v1/admin/reports/export/{type}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'role:admin',
          3 => 'api.rate.limit.admin',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@exportReport',
        'controller' => 'App\\Domains\\Admin\\Controllers\\Api\\DashboardController@exportReport',
        'as' => 'api.admin.reports.export',
        'namespace' => NULL,
        'prefix' => 'api/v1/admin/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ltYezhn2rCf9lJmR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/{fallbackPlaceholder}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:174:"function () {
    return \\response()->json([
        \'success\' => false,
        \'message\' => \'API endpoint not found\',
        \'error\' => \'ENDPOINT_NOT_FOUND\'
    ], 404);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000007570000000000000000";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ltYezhn2rCf9lJmR',
      ),
      'fallback' => true,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'fallbackPlaceholder' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9c8Q0mhbikzDzdTc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:841:"function () {
                    $exception = null;

                    try {
                        \\Illuminate\\Support\\Facades\\Event::dispatch(new \\Illuminate\\Foundation\\Events\\DiagnosingHealth);
                    } catch (\\Throwable $e) {
                        if (app()->hasDebugModeEnabled()) {
                            throw $e;
                        }

                        report($e);

                        $exception = $e->getMessage();
                    }

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'/media/bot/7E246BE4246B9DC1/laragon/www/parking/vendor/laravel/framework/src/Illuminate/Foundation/Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"000000000000075b0000000000000000";}}',
        'as' => 'generated::9c8Q0mhbikzDzdTc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'welcome' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@welcome',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@welcome',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'welcome',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'home' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'home',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@redirectToDashboard',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@redirectToDashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'home',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'language.switch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'language/{locale}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@switchLanguage',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@switchLanguage',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'language.switch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\DashboardController@legacyDashboard',
        'controller' => 'App\\Domains\\User\\Controllers\\DashboardController@legacyDashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\DashboardController@legacyDashboard',
        'controller' => 'App\\Domains\\User\\Controllers\\DashboardController@legacyDashboard',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user.dashboard',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vehicles.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VehicleController@index',
        'controller' => 'App\\Domains\\User\\Controllers\\VehicleController@index',
        'as' => 'vehicles.index',
        'namespace' => NULL,
        'prefix' => '/dashboard/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vehicles.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/vehicles/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VehicleController@create',
        'controller' => 'App\\Domains\\User\\Controllers\\VehicleController@create',
        'as' => 'vehicles.create',
        'namespace' => NULL,
        'prefix' => '/dashboard/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vehicles.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VehicleController@store',
        'controller' => 'App\\Domains\\User\\Controllers\\VehicleController@store',
        'as' => 'vehicles.store',
        'namespace' => NULL,
        'prefix' => '/dashboard/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vehicles.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VehicleController@show',
        'controller' => 'App\\Domains\\User\\Controllers\\VehicleController@show',
        'as' => 'vehicles.show',
        'namespace' => NULL,
        'prefix' => '/dashboard/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vehicles.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/vehicles/{vehicle}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VehicleController@edit',
        'controller' => 'App\\Domains\\User\\Controllers\\VehicleController@edit',
        'as' => 'vehicles.edit',
        'namespace' => NULL,
        'prefix' => '/dashboard/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vehicles.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VehicleController@update',
        'controller' => 'App\\Domains\\User\\Controllers\\VehicleController@update',
        'as' => 'vehicles.update',
        'namespace' => NULL,
        'prefix' => '/dashboard/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vehicles.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'dashboard/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VehicleController@destroy',
        'controller' => 'App\\Domains\\User\\Controllers\\VehicleController@destroy',
        'as' => 'vehicles.destroy',
        'namespace' => NULL,
        'prefix' => '/dashboard/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vehicles.upload.documents' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/vehicles/{vehicle}/documents',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VehicleController@uploadDocuments',
        'controller' => 'App\\Domains\\User\\Controllers\\VehicleController@uploadDocuments',
        'as' => 'vehicles.upload.documents',
        'namespace' => NULL,
        'prefix' => '/dashboard/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\BookingController@index',
        'controller' => 'App\\Domains\\User\\Controllers\\BookingController@index',
        'as' => 'bookings.index',
        'namespace' => NULL,
        'prefix' => '/dashboard/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookings.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/bookings/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\BookingController@create',
        'controller' => 'App\\Domains\\User\\Controllers\\BookingController@create',
        'as' => 'bookings.create',
        'namespace' => NULL,
        'prefix' => '/dashboard/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookings.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\BookingController@store',
        'controller' => 'App\\Domains\\User\\Controllers\\BookingController@store',
        'as' => 'bookings.store',
        'namespace' => NULL,
        'prefix' => '/dashboard/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookings.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/bookings/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\BookingController@show',
        'controller' => 'App\\Domains\\User\\Controllers\\BookingController@show',
        'as' => 'bookings.show',
        'namespace' => NULL,
        'prefix' => '/dashboard/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookings.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/bookings/{booking}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\BookingController@cancel',
        'controller' => 'App\\Domains\\User\\Controllers\\BookingController@cancel',
        'as' => 'bookings.cancel',
        'namespace' => NULL,
        'prefix' => '/dashboard/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookings.extend' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/bookings/{booking}/extend',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\BookingController@extend',
        'controller' => 'App\\Domains\\User\\Controllers\\BookingController@extend',
        'as' => 'bookings.extend',
        'namespace' => NULL,
        'prefix' => '/dashboard/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookings.slots.available' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/bookings/slots/available',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\BookingController@getAvailableSlots',
        'controller' => 'App\\Domains\\User\\Controllers\\BookingController@getAvailableSlots',
        'as' => 'bookings.slots.available',
        'namespace' => NULL,
        'prefix' => '/dashboard/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'bookings.calculate.cost' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/bookings/calculate-cost',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\BookingController@calculateCost',
        'controller' => 'App\\Domains\\User\\Controllers\\BookingController@calculateCost',
        'as' => 'bookings.calculate.cost',
        'namespace' => NULL,
        'prefix' => '/dashboard/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\PaymentController@index',
        'controller' => 'App\\Domains\\User\\Controllers\\PaymentController@index',
        'as' => 'payments.index',
        'namespace' => NULL,
        'prefix' => '/dashboard/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payments.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/payments/booking/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\PaymentController@create',
        'controller' => 'App\\Domains\\User\\Controllers\\PaymentController@create',
        'as' => 'payments.create',
        'namespace' => NULL,
        'prefix' => '/dashboard/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payments.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/payments/booking/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\PaymentController@store',
        'controller' => 'App\\Domains\\User\\Controllers\\PaymentController@store',
        'as' => 'payments.store',
        'namespace' => NULL,
        'prefix' => '/dashboard/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payments.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/payments/{payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\PaymentController@show',
        'controller' => 'App\\Domains\\User\\Controllers\\PaymentController@show',
        'as' => 'payments.show',
        'namespace' => NULL,
        'prefix' => '/dashboard/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payments.receipt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/payments/{payment}/receipt',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\PaymentController@receipt',
        'controller' => 'App\\Domains\\User\\Controllers\\PaymentController@receipt',
        'as' => 'payments.receipt',
        'namespace' => NULL,
        'prefix' => '/dashboard/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payments.gateway.success' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'payments/success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\PaymentController@success',
        'controller' => 'App\\Domains\\User\\Controllers\\PaymentController@success',
        'as' => 'payments.gateway.success',
        'namespace' => NULL,
        'prefix' => '/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payments.gateway.failure' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'payments/failure',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\PaymentController@failure',
        'controller' => 'App\\Domains\\User\\Controllers\\PaymentController@failure',
        'as' => 'payments.gateway.failure',
        'namespace' => NULL,
        'prefix' => '/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payments.gateway.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'payments/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\PaymentController@cancel',
        'controller' => 'App\\Domains\\User\\Controllers\\PaymentController@cancel',
        'as' => 'payments.gateway.cancel',
        'namespace' => NULL,
        'prefix' => '/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profile.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\DashboardController@profile',
        'controller' => 'App\\Domains\\User\\Controllers\\DashboardController@profile',
        'as' => 'profile.index',
        'namespace' => NULL,
        'prefix' => '/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profile.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\DashboardController@updateProfile',
        'controller' => 'App\\Domains\\User\\Controllers\\DashboardController@updateProfile',
        'as' => 'profile.update',
        'namespace' => NULL,
        'prefix' => '/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profile.password.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'profile/password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\DashboardController@updatePassword',
        'controller' => 'App\\Domains\\User\\Controllers\\DashboardController@updatePassword',
        'as' => 'profile.password.update',
        'namespace' => NULL,
        'prefix' => '/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profile.avatar.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'profile/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\DashboardController@updateAvatar',
        'controller' => 'App\\Domains\\User\\Controllers\\DashboardController@updateAvatar',
        'as' => 'profile.avatar.update',
        'namespace' => NULL,
        'prefix' => '/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.dashboard.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:admin.dashboard.view',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@index',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@index',
        'as' => 'admin.dashboard.index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.permissions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_permissions',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\PermissionController@index',
        'controller' => 'App\\Domains\\Admin\\Controllers\\PermissionController@index',
        'as' => 'admin.permissions.index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.permissions.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_permissions',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\PermissionController@createPermission',
        'controller' => 'App\\Domains\\Admin\\Controllers\\PermissionController@createPermission',
        'as' => 'admin.permissions.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.permissions.users' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/permissions/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_permissions',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\PermissionController@users',
        'controller' => 'App\\Domains\\Admin\\Controllers\\PermissionController@users',
        'as' => 'admin.permissions.users',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.roles.assign' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/users/{user}/roles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_permissions',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\PermissionController@assignUserRole',
        'controller' => 'App\\Domains\\Admin\\Controllers\\PermissionController@assignUserRole',
        'as' => 'admin.users.roles.assign',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.roles.remove' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/users/{user}/roles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_permissions',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\PermissionController@removeUserRole',
        'controller' => 'App\\Domains\\Admin\\Controllers\\PermissionController@removeUserRole',
        'as' => 'admin.users.roles.remove',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.permissions.roles' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/roles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_roles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\PermissionController@roles',
        'controller' => 'App\\Domains\\Admin\\Controllers\\PermissionController@roles',
        'as' => 'admin.permissions.roles',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.roles.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/roles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_roles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\PermissionController@createRole',
        'controller' => 'App\\Domains\\Admin\\Controllers\\PermissionController@createRole',
        'as' => 'admin.roles.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.roles.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/roles/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_roles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\PermissionController@updateRole',
        'controller' => 'App\\Domains\\Admin\\Controllers\\PermissionController@updateRole',
        'as' => 'admin.roles.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_users',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@index',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@index',
        'as' => 'admin.users.index',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_users',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@create',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@create',
        'as' => 'admin.users.create',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_users',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@store',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@store',
        'as' => 'admin.users.store',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_users',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@show',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@show',
        'as' => 'admin.users.show',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users/{user}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_users',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@edit',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@edit',
        'as' => 'admin.users.edit',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_users',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@update',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@update',
        'as' => 'admin.users.update',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.suspend' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/users/{user}/suspend',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_users',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@suspend',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@suspend',
        'as' => 'admin.users.suspend',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.activate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/users/{user}/activate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_users',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@activate',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminUserController@activate',
        'as' => 'admin.users.activate',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.payments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminPaymentController@index',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminPaymentController@index',
        'as' => 'admin.payments.index',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.payments.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/payments/{payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminPaymentController@show',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminPaymentController@show',
        'as' => 'admin.payments.show',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.payments.refund' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/payments/{payment}/refund',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminPaymentController@refund',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminPaymentController@refund',
        'as' => 'admin.payments.refund',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.invoices.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/invoices',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_invoices',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminInvoiceController@index',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminInvoiceController@index',
        'as' => 'admin.invoices.index',
        'namespace' => NULL,
        'prefix' => 'admin/invoices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.invoices.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/invoices/{invoice}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_invoices',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminInvoiceController@show',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminInvoiceController@show',
        'as' => 'admin.invoices.show',
        'namespace' => NULL,
        'prefix' => 'admin/invoices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.invoices.download' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/invoices/{invoice}/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_invoices',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminInvoiceController@download',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminInvoiceController@download',
        'as' => 'admin.invoices.download',
        'namespace' => NULL,
        'prefix' => 'admin/invoices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.invoices.mark-paid' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/invoices/{invoice}/mark-paid',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_invoices',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminInvoiceController@markPaid',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminInvoiceController@markPaid',
        'as' => 'admin.invoices.mark-paid',
        'namespace' => NULL,
        'prefix' => 'admin/invoices',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.bookings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_bookings',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminBookingController@index',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminBookingController@index',
        'as' => 'admin.bookings.index',
        'namespace' => NULL,
        'prefix' => 'admin/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.bookings.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/bookings/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_bookings',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminBookingController@show',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminBookingController@show',
        'as' => 'admin.bookings.show',
        'namespace' => NULL,
        'prefix' => 'admin/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.bookings.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/bookings/{booking}/confirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_bookings',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminBookingController@confirm',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminBookingController@confirm',
        'as' => 'admin.bookings.confirm',
        'namespace' => NULL,
        'prefix' => 'admin/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.bookings.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/bookings/{booking}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_bookings',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminBookingController@cancel',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminBookingController@cancel',
        'as' => 'admin.bookings.cancel',
        'namespace' => NULL,
        'prefix' => 'admin/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.parking-locations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/parking-locations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_parking',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@index',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@index',
        'as' => 'admin.parking-locations.index',
        'namespace' => NULL,
        'prefix' => 'admin/parking-locations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.parking-locations.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/parking-locations/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_parking',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@create',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@create',
        'as' => 'admin.parking-locations.create',
        'namespace' => NULL,
        'prefix' => 'admin/parking-locations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.parking-locations.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/parking-locations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_parking',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@store',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@store',
        'as' => 'admin.parking-locations.store',
        'namespace' => NULL,
        'prefix' => 'admin/parking-locations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.parking-locations.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/parking-locations/{parkingLocation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_parking',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@show',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@show',
        'as' => 'admin.parking-locations.show',
        'namespace' => NULL,
        'prefix' => 'admin/parking-locations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.parking-locations.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/parking-locations/{parkingLocation}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_parking',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@edit',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@edit',
        'as' => 'admin.parking-locations.edit',
        'namespace' => NULL,
        'prefix' => 'admin/parking-locations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.parking-locations.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/parking-locations/{parkingLocation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_parking',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@update',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@update',
        'as' => 'admin.parking-locations.update',
        'namespace' => NULL,
        'prefix' => 'admin/parking-locations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.parking-locations.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/parking-locations/{parkingLocation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_parking',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@destroy',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminParkingLocationController@destroy',
        'as' => 'admin.parking-locations.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/parking-locations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vehicles.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_vehicles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@index',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@index',
        'as' => 'admin.vehicles.index',
        'namespace' => NULL,
        'prefix' => 'admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vehicles.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/vehicles/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_vehicles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@create',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@create',
        'as' => 'admin.vehicles.create',
        'namespace' => NULL,
        'prefix' => 'admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vehicles.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_vehicles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@store',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@store',
        'as' => 'admin.vehicles.store',
        'namespace' => NULL,
        'prefix' => 'admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vehicles.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_vehicles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@show',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@show',
        'as' => 'admin.vehicles.show',
        'namespace' => NULL,
        'prefix' => 'admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vehicles.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/vehicles/{vehicle}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_vehicles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@edit',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@edit',
        'as' => 'admin.vehicles.edit',
        'namespace' => NULL,
        'prefix' => 'admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vehicles.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_vehicles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@update',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@update',
        'as' => 'admin.vehicles.update',
        'namespace' => NULL,
        'prefix' => 'admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vehicles.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_vehicles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@destroy',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminVehicleController@destroy',
        'as' => 'admin.vehicles.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vehicles.pending' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/vehicles/pending',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_vehicles',
          4 => 'permission:verify_vehicles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@pendingVehicles',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@pendingVehicles',
        'as' => 'admin.vehicles.pending',
        'namespace' => NULL,
        'prefix' => 'admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vehicles.verify' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/vehicles/{vehicle}/verify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_vehicles',
          4 => 'permission:verify_vehicles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@verifyVehicle',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@verifyVehicle',
        'as' => 'admin.vehicles.verify',
        'namespace' => NULL,
        'prefix' => 'admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.vehicles.reject' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/vehicles/{vehicle}/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_vehicles',
          4 => 'permission:verify_vehicles',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@rejectVehicle',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@rejectVehicle',
        'as' => 'admin.vehicles.reject',
        'namespace' => NULL,
        'prefix' => 'admin/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.profile.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\ProfileController@index',
        'controller' => 'App\\Domains\\Admin\\Controllers\\ProfileController@index',
        'as' => 'admin.profile.index',
        'namespace' => NULL,
        'prefix' => 'admin/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.profile.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\ProfileController@update',
        'controller' => 'App\\Domains\\Admin\\Controllers\\ProfileController@update',
        'as' => 'admin.profile.update',
        'namespace' => NULL,
        'prefix' => 'admin/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.profile.change-password' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/profile/change-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\ProfileController@changePassword',
        'controller' => 'App\\Domains\\Admin\\Controllers\\ProfileController@changePassword',
        'as' => 'admin.profile.change-password',
        'namespace' => NULL,
        'prefix' => 'admin/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.profile.logout-all' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/profile/logout-all',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\ProfileController@logoutAll',
        'controller' => 'App\\Domains\\Admin\\Controllers\\ProfileController@logoutAll',
        'as' => 'admin.profile.logout-all',
        'namespace' => NULL,
        'prefix' => 'admin/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.profile.delete-account' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\ProfileController@deleteAccount',
        'controller' => 'App\\Domains\\Admin\\Controllers\\ProfileController@deleteAccount',
        'as' => 'admin.profile.delete-account',
        'namespace' => NULL,
        'prefix' => 'admin/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\SystemSettingsController@index',
        'controller' => 'App\\Domains\\Admin\\Controllers\\SystemSettingsController@index',
        'as' => 'admin.settings.index',
        'namespace' => NULL,
        'prefix' => 'admin/settings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\SystemSettingsController@update',
        'controller' => 'App\\Domains\\Admin\\Controllers\\SystemSettingsController@update',
        'as' => 'admin.settings.update',
        'namespace' => NULL,
        'prefix' => 'admin/settings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system.health' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system/health',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:view_logs',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@systemHealth',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@systemHealth',
        'as' => 'admin.system.health',
        'namespace' => NULL,
        'prefix' => 'admin/system',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system.logs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system/logs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:view_logs',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@systemLogs',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@systemLogs',
        'as' => 'admin.system.logs',
        'namespace' => NULL,
        'prefix' => 'admin/system',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system.cache.clear' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/system/cache/clear',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:view_logs',
          4 => 'permission:manage_settings',
        ),
        'uses' => '\\App\\Domains\\Admin\\Controllers\\AdminDashboardController@clearCache',
        'controller' => '\\App\\Domains\\Admin\\Controllers\\AdminDashboardController@clearCache',
        'as' => 'admin.system.cache.clear',
        'namespace' => NULL,
        'prefix' => 'admin/system',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.reports.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:view_reports',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@reports',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@reports',
        'as' => 'admin.reports.index',
        'namespace' => NULL,
        'prefix' => 'admin/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.reports.revenue' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/reports/revenue',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:view_reports',
          4 => 'permission:view_financial_reports',
        ),
        'uses' => '\\App\\Domains\\Admin\\Controllers\\AdminDashboardController@revenueReport',
        'controller' => '\\App\\Domains\\Admin\\Controllers\\AdminDashboardController@revenueReport',
        'as' => 'admin.reports.revenue',
        'namespace' => NULL,
        'prefix' => 'admin/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.reports.bookings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/reports/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:view_reports',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@bookingReport',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@bookingReport',
        'as' => 'admin.reports.bookings',
        'namespace' => NULL,
        'prefix' => 'admin/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.reports.users' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/reports/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'set.language',
          3 => 'permission:view_reports',
        ),
        'uses' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@userReport',
        'controller' => 'App\\Domains\\Admin\\Controllers\\AdminDashboardController@userReport',
        'as' => 'admin.reports.users',
        'namespace' => NULL,
        'prefix' => 'admin/reports',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisteredUserController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisteredUserController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Ng8ic8GLlgKH417l' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisteredUserController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisteredUserController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::Ng8ic8GLlgKH417l',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8G7plWlRjy6CsZb6' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::8G7plWlRjy6CsZb6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.request',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.email' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.email',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.reset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'reset-password/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.reset',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.notice' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'verify-email',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\EmailVerificationPromptController@__invoke',
        'controller' => 'App\\Http\\Controllers\\Auth\\EmailVerificationPromptController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.notice',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.verify' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'verify-email/{id}/{hash}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'signed',
          3 => 'throttle:6,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\VerifyEmailController@__invoke',
        'controller' => 'App\\Http\\Controllers\\Auth\\VerifyEmailController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.verify',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'email/verification-notification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'throttle:6,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\EmailVerificationNotificationController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\EmailVerificationNotificationController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.send',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'confirm-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@show',
        'controller' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.confirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WOnEf9vHpukmq3yL' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'confirm-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::WOnEf9vHpukmq3yL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\PasswordController@update',
        'controller' => 'App\\Http\\Controllers\\Auth\\PasswordController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@destroy',
        'controller' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.parking.locations' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'parking/locations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@locations',
        'controller' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@locations',
        'as' => 'visitor.parking.locations',
        'namespace' => NULL,
        'prefix' => '/parking',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.parking.location.details' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'parking/locations/{location}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@locationDetails',
        'controller' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@locationDetails',
        'as' => 'visitor.parking.location.details',
        'namespace' => NULL,
        'prefix' => '/parking',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.parking.availability' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'parking/locations/{location}/availability',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@availability',
        'controller' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@availability',
        'as' => 'visitor.parking.availability',
        'namespace' => NULL,
        'prefix' => '/parking',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.parking.check.availability' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'parking/slots/check-availability',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@checkAvailability',
        'controller' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@checkAvailability',
        'as' => 'visitor.parking.check.availability',
        'namespace' => NULL,
        'prefix' => '/parking',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.register' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@showRegister',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@showRegister',
        'as' => 'visitor.register',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.register.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@register',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@register',
        'as' => 'visitor.register.store',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@showLogin',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@showLogin',
        'as' => 'visitor.login',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.login.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@login',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@login',
        'as' => 'visitor.login.store',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.verify.otp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/verify-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@showOtpVerification',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@showOtpVerification',
        'as' => 'visitor.verify.otp',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.verify.otp.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/verify-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@verifyOtp',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@verifyOtp',
        'as' => 'visitor.verify.otp.store',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.resend.otp' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/resend-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@resendOtp',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@resendOtp',
        'as' => 'visitor.resend.otp',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.password.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@showForgotPassword',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@showForgotPassword',
        'as' => 'visitor.password.request',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.password.email' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@sendPasswordReset',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@sendPasswordReset',
        'as' => 'visitor.password.email',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.password.reset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/reset-password/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@showResetPassword',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@showResetPassword',
        'as' => 'visitor.password.reset',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.password.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
          2 => 'throttle:auth',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@resetPassword',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@resetPassword',
        'as' => 'visitor.password.store',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@dashboard',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@dashboard',
        'as' => 'visitor.dashboard',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@logout',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@logout',
        'as' => 'visitor.logout',
        'namespace' => NULL,
        'prefix' => '/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.profile.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@index',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@index',
        'as' => 'visitor.profile.index',
        'namespace' => NULL,
        'prefix' => 'visitor/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.profile.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/profile/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@edit',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@edit',
        'as' => 'visitor.profile.edit',
        'namespace' => NULL,
        'prefix' => 'visitor/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.profile.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'visitor/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@update',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@update',
        'as' => 'visitor.profile.update',
        'namespace' => NULL,
        'prefix' => 'visitor/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.profile.password.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'visitor/profile/password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@updatePassword',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@updatePassword',
        'as' => 'visitor.profile.password.update',
        'namespace' => NULL,
        'prefix' => 'visitor/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.profile.avatar.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/profile/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@updateAvatar',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@updateAvatar',
        'as' => 'visitor.profile.avatar.update',
        'namespace' => NULL,
        'prefix' => 'visitor/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.profile.avatar.remove' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'visitor/profile/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@removeAvatar',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@removeAvatar',
        'as' => 'visitor.profile.avatar.remove',
        'namespace' => NULL,
        'prefix' => 'visitor/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.profile.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'visitor/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@destroy',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@destroy',
        'as' => 'visitor.profile.destroy',
        'namespace' => NULL,
        'prefix' => 'visitor/profile',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.vehicles.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@index',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@index',
        'as' => 'visitor.vehicles.index',
        'namespace' => NULL,
        'prefix' => 'visitor/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.vehicles.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/vehicles/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@create',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@create',
        'as' => 'visitor.vehicles.create',
        'namespace' => NULL,
        'prefix' => 'visitor/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.vehicles.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@store',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@store',
        'as' => 'visitor.vehicles.store',
        'namespace' => NULL,
        'prefix' => 'visitor/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.vehicles.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@show',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@show',
        'as' => 'visitor.vehicles.show',
        'namespace' => NULL,
        'prefix' => 'visitor/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.vehicles.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/vehicles/{vehicle}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@edit',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@edit',
        'as' => 'visitor.vehicles.edit',
        'namespace' => NULL,
        'prefix' => 'visitor/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.vehicles.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'visitor/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@update',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@update',
        'as' => 'visitor.vehicles.update',
        'namespace' => NULL,
        'prefix' => 'visitor/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.vehicles.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'visitor/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@destroy',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@destroy',
        'as' => 'visitor.vehicles.destroy',
        'namespace' => NULL,
        'prefix' => 'visitor/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.vehicles.set.default' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/vehicles/{vehicle}/set-default',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@setDefault',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@setDefault',
        'as' => 'visitor.vehicles.set.default',
        'namespace' => NULL,
        'prefix' => 'visitor/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.vehicles.upload.documents' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/vehicles/{vehicle}/documents',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@uploadDocuments',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@uploadDocuments',
        'as' => 'visitor.vehicles.upload.documents',
        'namespace' => NULL,
        'prefix' => 'visitor/vehicles',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.bookings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@index',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@index',
        'as' => 'visitor.bookings.index',
        'namespace' => NULL,
        'prefix' => 'visitor/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.bookings.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/bookings/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@create',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@create',
        'as' => 'visitor.bookings.create',
        'namespace' => NULL,
        'prefix' => 'visitor/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.bookings.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@store',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@store',
        'as' => 'visitor.bookings.store',
        'namespace' => NULL,
        'prefix' => 'visitor/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.bookings.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/bookings/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@show',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@show',
        'as' => 'visitor.bookings.show',
        'namespace' => NULL,
        'prefix' => 'visitor/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.bookings.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'visitor/bookings/{booking}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@cancel',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@cancel',
        'as' => 'visitor.bookings.cancel',
        'namespace' => NULL,
        'prefix' => 'visitor/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.bookings.extend' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'visitor/bookings/{booking}/extend',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@extend',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@extend',
        'as' => 'visitor.bookings.extend',
        'namespace' => NULL,
        'prefix' => 'visitor/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.bookings.receipt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/bookings/{booking}/receipt',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@receipt',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@receipt',
        'as' => 'visitor.bookings.receipt',
        'namespace' => NULL,
        'prefix' => 'visitor/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.bookings.api.slots.available' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/bookings/api/slots/available',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@getAvailableSlots',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@getAvailableSlots',
        'as' => 'visitor.bookings.api.slots.available',
        'namespace' => NULL,
        'prefix' => 'visitor/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.bookings.api.calculate.cost' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/bookings/api/calculate-cost',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@calculateCost',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@calculateCost',
        'as' => 'visitor.bookings.api.calculate.cost',
        'namespace' => NULL,
        'prefix' => 'visitor/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.bookings.api.validate.booking' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/bookings/api/validate-booking',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@validateBooking',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@validateBooking',
        'as' => 'visitor.bookings.api.validate.booking',
        'namespace' => NULL,
        'prefix' => 'visitor/bookings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@index',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@index',
        'as' => 'visitor.payments.index',
        'namespace' => NULL,
        'prefix' => 'visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/payments/booking/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@create',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@create',
        'as' => 'visitor.payments.create',
        'namespace' => NULL,
        'prefix' => 'visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/payments/booking/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@store',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@store',
        'as' => 'visitor.payments.store',
        'namespace' => NULL,
        'prefix' => 'visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/payments/{payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@show',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@show',
        'as' => 'visitor.payments.show',
        'namespace' => NULL,
        'prefix' => 'visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.invoice' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/payments/{payment}/invoice',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@invoice',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@invoice',
        'as' => 'visitor.payments.invoice',
        'namespace' => NULL,
        'prefix' => 'visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.download' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/payments/{payment}/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
          3 => 'set.language',
          4 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@download',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@download',
        'as' => 'visitor.payments.download',
        'namespace' => NULL,
        'prefix' => 'visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.gateway.success' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/payments/gateway/success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@gatewaySuccess',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@gatewaySuccess',
        'as' => 'visitor.payments.gateway.success',
        'namespace' => NULL,
        'prefix' => '/visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.gateway.failure' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/payments/gateway/failure',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@gatewayFailure',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@gatewayFailure',
        'as' => 'visitor.payments.gateway.failure',
        'namespace' => NULL,
        'prefix' => '/visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.gateway.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/payments/gateway/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@gatewayCancel',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@gatewayCancel',
        'as' => 'visitor.payments.gateway.cancel',
        'namespace' => NULL,
        'prefix' => '/visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.gateway.webhook' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'visitor/payments/gateway/webhook',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@gatewayWebhook',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@gatewayWebhook',
        'as' => 'visitor.payments.gateway.webhook',
        'namespace' => NULL,
        'prefix' => '/visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'visitor.payments.status' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'visitor/payments/status/{payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@checkStatus',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@checkStatus',
        'as' => 'visitor.payments.status',
        'namespace' => NULL,
        'prefix' => '/visitor/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.login' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/visitor/auth/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@apiLogin',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@apiLogin',
        'as' => 'api.visitor.api.login',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.register' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/visitor/auth/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@apiRegister',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@apiRegister',
        'as' => 'api.visitor.api.register',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.refresh' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/visitor/auth/refresh',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@apiRefresh',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@apiRefresh',
        'as' => 'api.visitor.api.refresh',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/visitor/auth/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorController@apiLogout',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorController@apiLogout',
        'as' => 'api.visitor.api.logout',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/visitor/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@apiProfile',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@apiProfile',
        'as' => 'api.visitor.api.profile',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.profile.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/visitor/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'uses' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@apiUpdateProfile',
        'controller' => 'App\\Domains\\User\\Controllers\\VisitorProfileController@apiUpdateProfile',
        'as' => 'api.visitor.api.profile.update',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.vehicles.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/visitor/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'as' => 'api.visitor.api.vehicles.index',
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@index',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@index',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.vehicles.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/visitor/vehicles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'as' => 'api.visitor.api.vehicles.store',
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@store',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@store',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.vehicles.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/visitor/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'as' => 'api.visitor.api.vehicles.show',
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@show',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@show',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.vehicles.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'api/visitor/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'as' => 'api.visitor.api.vehicles.update',
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@update',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@update',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.vehicles.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/visitor/vehicles/{vehicle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'as' => 'api.visitor.api.vehicles.destroy',
        'uses' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@destroy',
        'controller' => 'App\\Domains\\Vehicle\\Controllers\\VisitorVehicleController@destroy',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.bookings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/visitor/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'as' => 'api.visitor.api.bookings.index',
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@index',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@index',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.bookings.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/visitor/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'as' => 'api.visitor.api.bookings.store',
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@store',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@store',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.bookings.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/visitor/bookings/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'as' => 'api.visitor.api.bookings.show',
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@show',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@show',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.bookings.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'api/visitor/bookings/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'as' => 'api.visitor.api.bookings.update',
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@update',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@update',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.bookings.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/visitor/bookings/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'as' => 'api.visitor.api.bookings.destroy',
        'uses' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@destroy',
        'controller' => 'App\\Domains\\Booking\\Controllers\\VisitorBookingController@destroy',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.payments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/visitor/payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@apiIndex',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@apiIndex',
        'as' => 'api.visitor.api.payments.index',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.payments.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/visitor/payments/{payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
          2 => 'auth:sanctum',
        ),
        'uses' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@apiShow',
        'controller' => 'App\\Domains\\Payment\\Controllers\\VisitorPaymentController@apiShow',
        'as' => 'api.visitor.api.payments.show',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.parking.locations' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/visitor/parking/locations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@apiLocations',
        'controller' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@apiLocations',
        'as' => 'api.visitor.api.parking.locations',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.parking.location.details' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/visitor/parking/locations/{location}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@apiLocationDetails',
        'controller' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@apiLocationDetails',
        'as' => 'api.visitor.api.parking.location.details',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.visitor.api.parking.check.availability' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/visitor/parking/check-availability',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:api',
        ),
        'uses' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@apiCheckAvailability',
        'controller' => 'App\\Domains\\Parking\\Controllers\\VisitorParkingController@apiCheckAvailability',
        'as' => 'api.visitor.api.parking.check.availability',
        'namespace' => NULL,
        'prefix' => '/api/visitor',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'gate.entry' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'gate/entry',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@vehicleEntry',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@vehicleEntry',
        'as' => 'gate.entry',
        'namespace' => NULL,
        'prefix' => '/gate',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'gate.exit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'gate/exit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@vehicleExit',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@vehicleExit',
        'as' => 'gate.exit',
        'namespace' => NULL,
        'prefix' => '/gate',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'gate.scan' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'gate/scan',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@scanQrCode',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@scanQrCode',
        'as' => 'gate.scan',
        'namespace' => NULL,
        'prefix' => '/gate',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'gate.logs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'gate/logs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@getGateLogs',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@getGateLogs',
        'as' => 'gate.logs',
        'namespace' => NULL,
        'prefix' => '/gate',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.gates.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/gates',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:sanctum',
          2 => 'auth',
          3 => 'can:operate.gates',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@index',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@index',
        'as' => 'admin.gates.index',
        'namespace' => NULL,
        'prefix' => '/admin/gates',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.gates.entries' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/gates/entries',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:sanctum',
          2 => 'auth',
          3 => 'can:operate.gates',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@entries',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@entries',
        'as' => 'admin.gates.entries',
        'namespace' => NULL,
        'prefix' => '/admin/gates',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.gates.exits' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/gates/exits',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:sanctum',
          2 => 'auth',
          3 => 'can:operate.gates',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@exits',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@exits',
        'as' => 'admin.gates.exits',
        'namespace' => NULL,
        'prefix' => '/admin/gates',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.gate-entries.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/gate-entries',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:sanctum',
          2 => 'auth',
          3 => 'can:view.gate.logs',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@entryLogs',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@entryLogs',
        'as' => 'admin.gate-entries.index',
        'namespace' => NULL,
        'prefix' => '/admin/gate-entries',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.gate-exits.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/gate-exits',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:sanctum',
          2 => 'auth',
          3 => 'can:view.gate.logs',
        ),
        'uses' => 'App\\Domains\\Gate\\Controllers\\GateController@exitLogs',
        'controller' => 'App\\Domains\\Gate\\Controllers\\GateController@exitLogs',
        'as' => 'admin.gate-exits.index',
        'namespace' => NULL,
        'prefix' => '/admin/gate-exits',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.docs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/docs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:54:"function () {
    return \\view(\'api.documentation\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000079a0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'api.docs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:5:{s:6:"driver";s:5:"local";s:4:"root";s:67:"/media/bot/7E246BE4246B9DC1/laragon/www/parking/storage/app/private";s:5:"serve";b:1;s:5:"throw";b:0;s:6:"report";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:323:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ServeFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"000000000000084f0000000000000000";}}',
        'as' => 'storage.local',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
