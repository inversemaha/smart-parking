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
            <a href="<?php echo e(route('admin.vehicles.pending')); ?>" class="side-menu <?php echo e(request()->routeIs('admin.vehicles*') ? 'side-menu--active' : ''); ?>">
                <div class="side-menu__icon">
                    <i data-lucide="car"></i>
                </div>
                <div class="side-menu__title">Vehicles</div>
            </a>
        </li>

        <!-- Parking Locations -->
        <li>
            <a href="javascript:;" class="side-menu">
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
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">All Locations</div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">Add New</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Bookings -->
        <li>
            <a href="javascript:;" class="side-menu">
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
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">All Bookings</div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">Pending</div>
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

        <!-- Users -->
        <li>
            <a href="javascript:;" class="side-menu">
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
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">All Users</div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">Roles & Permissions</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Payments -->
        <li>
            <a href="javascript:;" class="side-menu">
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
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">All Payments</div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">Invoices</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="side-nav__devider my-6"></li>

        <!-- System Settings -->
        <li>
            <a href="javascript:;" class="side-menu">
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
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">System Settings</div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="activity"></i>
                        </div>
                        <div class="side-menu__title">Audit Logs</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- END: Side Menu -->
<?php /**PATH /media/bot/7E246BE4246B9DC1/laragon/www/parking/resources/views/partials/admin/sidebar.blade.php ENDPATH**/ ?>