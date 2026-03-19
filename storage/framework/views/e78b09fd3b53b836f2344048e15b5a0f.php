<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    <a href="<?php echo e(route('admin.dashboard.index')); ?>" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Logo" class="w-6" src="<?php echo e(asset('backend/assets/images/logo.svg')); ?>">
        <span class="hidden xl:block text-white text-lg ml-3">Parking</span>
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>
        <!-- Dashboard -->
        <li>
            <a href="<?php echo e(route('admin.dashboard.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.dashboard*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="home"></i>
                </div>
                <div class="side-menu__title">Dashboard</div>
            </a>
        </li>

        <!-- Vehicles -->
        <li>
            <a href="<?php echo e(route('admin.vehicles.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.vehicles*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="car"></i>
                </div>
                <div class="side-menu__title">
                    Vehicles
                    <div class="side-menu__sub-icon">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="<?php echo e(route('admin.vehicles.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.vehicles.index') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="list"></i>
                        </div>
                        <div class="side-menu__title">All Vehicles</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.vehicles.pending')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.vehicles.pending') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="clock"></i>
                        </div>
                        <div class="side-menu__title">Pending Verification</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.vehicles.create')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.vehicles.create') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="plus"></i>
                        </div>
                        <div class="side-menu__title">Add Vehicle</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Parking Locations -->
        <li>
            <a href="<?php echo e(route('admin.parking-locations.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.parking-locations*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="map-pin"></i>
                </div>
                <div class="side-menu__title">
                    Parking Lots
                    <div class="side-menu__sub-icon">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="<?php echo e(route('admin.parking-locations.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.parking-locations.index') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="list"></i>
                        </div>
                        <div class="side-menu__title">All Locations</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.parking-locations.create')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.parking-locations.create') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="plus"></i>
                        </div>
                        <div class="side-menu__title">Add Location</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Bookings -->
        <li>
            <a href="<?php echo e(route('admin.bookings.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.bookings*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="calendar"></i>
                </div>
                <div class="side-menu__title">
                    Bookings
                    <div class="side-menu__sub-icon">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="<?php echo e(route('admin.bookings.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.bookings.index') && !request()->has('status') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="list"></i>
                        </div>
                        <div class="side-menu__title">All Bookings</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.bookings.index', ['status' => 'pending'])); ?>" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="clock"></i>
                        </div>
                        <div class="side-menu__title">Pending</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.bookings.index', ['status' => 'active'])); ?>" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="zap"></i>
                        </div>
                        <div class="side-menu__title">Active</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Users -->
        <li>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.users*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="users"></i>
                </div>
                <div class="side-menu__title">
                    Users
                    <div class="side-menu__sub-icon">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.users.index') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="list"></i>
                        </div>
                        <div class="side-menu__title">All Users</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.users.create')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.users.create') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="plus"></i>
                        </div>
                        <div class="side-menu__title">Add User</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Payments -->
        <li>
            <a href="<?php echo e(route('admin.payments.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.payments*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="credit-card"></i>
                </div>
                <div class="side-menu__title">
                    Payments
                    <div class="side-menu__sub-icon">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="<?php echo e(route('admin.payments.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.payments.index') && !request()->has('status') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="list"></i>
                        </div>
                        <div class="side-menu__title">All Payments</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.payments.index', ['status' => 'paid'])); ?>" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="check-circle"></i>
                        </div>
                        <div class="side-menu__title">Completed</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.payments.index', ['status' => 'pending'])); ?>" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="clock"></i>
                        </div>
                        <div class="side-menu__title">Pending</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Invoices -->
        <li>
            <a href="<?php echo e(route('admin.invoices.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.invoices*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="file-text"></i>
                </div>
                <div class="side-menu__title">
                    Invoices
                    <div class="side-menu__sub-icon">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="<?php echo e(route('admin.invoices.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.invoices.index') && !request()->has('status') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="list"></i>
                        </div>
                        <div class="side-menu__title">All Invoices</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.invoices.index', ['status' => 'unpaid'])); ?>" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="alert-circle"></i>
                        </div>
                        <div class="side-menu__title">Unpaid</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.invoices.index', ['status' => 'paid'])); ?>" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="check-circle"></i>
                        </div>
                        <div class="side-menu__title">Paid</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Reports -->
        <li>
            <a href="<?php echo e(route('admin.reports.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.reports*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="chart-column"></i>
                </div>
                <div class="side-menu__title">Reports</div>
            </a>
        </li>

        <!-- Permissions & Roles -->
        <li>
            <a href="<?php echo e(route('admin.permissions.roles')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.permissions*') || request()->routeIs('admin.roles*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="lock"></i>
                </div>
                <div class="side-menu__title">
                    Permissions
                    <div class="side-menu__sub-icon">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="<?php echo e(route('admin.permissions.roles')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.permissions.roles') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="shield"></i>
                        </div>
                        <div class="side-menu__title">Roles</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.permissions.users')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.permissions.users') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="user-check"></i>
                        </div>
                        <div class="side-menu__title">User Permissions</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.permissions.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.permissions.index') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="key"></i>
                        </div>
                        <div class="side-menu__title">Manage Permissions</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="side-nav__devider my-6"></li>

        <!-- System Settings -->
        <li>
            <a href="<?php echo e(route('admin.settings.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.settings*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="settings"></i>
                </div>
                <div class="side-menu__title">
                    Settings
                    <div class="side-menu__sub-icon">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </a>
            <ul class="">
                <li>
                    <a href="<?php echo e(route('admin.settings.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.settings*') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="settings"></i>
                        </div>
                        <div class="side-menu__title">System Settings</div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.system.logs')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.system.logs') ? 'side-menu--active' : ''); ?>">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">Audit Logs</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- User Profile -->
        <li>
            <a href="<?php echo e(route('admin.profile.index')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.profile*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="user"></i>
                </div>
                <div class="side-menu__title">My Profile</div>
            </a>
        </li>
    </ul>
</nav>
<!-- END: Side Menu -->
<?php /**PATH /media/bot/7E246BE4246B9DC1/laragon/www/parking/resources/views/partials/admin/sidebar.blade.php ENDPATH**/ ?>