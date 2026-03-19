<!-- BEGIN: Top Bar -->
<div class="top-bar">
    <!-- BEGIN: Breadcrumb -->
    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('admin.dashboard.index')); ?>">Dashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo $__env->yieldContent('page-title', 'Page'); ?>
            </li>
        </ol>
    </nav>
    <!-- END: Breadcrumb -->

    <!-- BEGIN: Search -->
    <div class="intro-x relative mr-3 sm:mr-6">
        <div class="search hidden sm:block">
            <input type="text" class="search__input form-control border-transparent" placeholder="Search...">
            <i data-lucide="search" class="search__icon dark:text-slate-500"></i>
        </div>
        <a class="notification sm:hidden" href="javascript:;">
            <i data-lucide="search" class="notification__icon dark:text-slate-500"></i>
        </a>
        <div class="search-result">
            <div class="search-result__content">
                <div class="search-result__content__title">Admin Pages</div>
                <div class="mb-5">
                    <a href="<?php echo e(route('admin.dashboard.index')); ?>" class="flex items-center">
                        <div class="w-8 h-8 bg-primary/20 text-primary flex items-center justify-center rounded-full">
                            <i class="w-4 h-4" data-lucide="layout-dashboard"></i>
                        </div>
                        <div class="ml-3">Dashboard</div>
                    </a>
                    <a href="<?php echo e(route('admin.vehicles.pending')); ?>" class="flex items-center mt-2">
                        <div class="w-8 h-8 bg-success/20 text-success flex items-center justify-center rounded-full">
                            <i class="w-4 h-4" data-lucide="car"></i>
                        </div>
                        <div class="ml-3">Vehicles</div>
                    </a>
                    <a href="<?php echo e(route('admin.reports.index')); ?>" class="flex items-center mt-2">
                        <div class="w-8 h-8 bg-warning/20 text-warning flex items-center justify-center rounded-full">
                            <i class="w-4 h-4" data-lucide="chart-column"></i>
                        </div>
                        <div class="ml-3">Reports</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Search -->

    <!-- BEGIN: Notifications -->
    <div class="intro-x dropdown mr-auto sm:mr-6">
        <div class="dropdown-toggle notification notification--bullet cursor-pointer" role="button" aria-expanded="false" data-tw-toggle="dropdown">
            <i data-lucide="bell" class="notification__icon dark:text-slate-500"></i>
        </div>
        <div class="notification-content pt-2 dropdown-menu">
            <div class="notification-content__box dropdown-content">
                <div class="notification-content__title">Notifications</div>

                <!-- Sample Notification -->
                <div class="cursor-pointer relative flex items-center">
                    <div class="w-12 h-12 flex-none image-fit mr-1">
                        <img alt="Avatar" class="rounded-full" src="<?php echo e(asset('backend/ui/images/fakers/profile-4.jpg')); ?>">
                        <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                    </div>
                    <div class="ml-2 overflow-hidden">
                        <div class="flex items-center">
                            <a href="javascript:;" class="font-medium truncate mr-5">System</a>
                            <div class="text-xs text-slate-400 ml-auto whitespace-nowrap">Just Now</div>
                        </div>
                        <div class="w-full truncate text-slate-500 mt-0.5">New booking request pending approval</div>
                    </div>
                </div>

                <div class="cursor-pointer relative flex items-center mt-5">
                    <div class="w-12 h-12 flex-none image-fit mr-1">
                        <img alt="Avatar" class="rounded-full" src="<?php echo e(asset('backend/ui/images/fakers/profile-2.jpg')); ?>">
                        <div class="w-3 h-3 bg-success absolute right-0 bottom-0 rounded-full border-2 border-white dark:border-darkmode-600"></div>
                    </div>
                    <div class="ml-2 overflow-hidden">
                        <div class="flex items-center">
                            <a href="javascript:;" class="font-medium truncate mr-5">Admin</a>
                            <div class="text-xs text-slate-400 ml-auto whitespace-nowrap">5 minutes ago</div>
                        </div>
                        <div class="w-full truncate text-slate-500 mt-0.5">Vehicle verification completed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Notifications -->

    <!-- BEGIN: Account Menu -->
    <div class="intro-x dropdown w-8 h-8">
        <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-tw-toggle="dropdown">
            <img alt="User Profile" src="<?php echo e(asset('backend/ui/images/fakers/profile-1.jpg')); ?>">
        </div>
        <div class="dropdown-menu w-56">
            <ul class="dropdown-content bg-primary text-white">
                <li class="p-2">
                    <div class="font-medium"><?php echo e(auth()->user()->name ?? 'Administrator'); ?></div>
                    <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500">Administrator</div>
                </li>
                <li>
                    <hr class="dropdown-divider border-white/[0.08]">
                </li>
                <li>
                    <a href="<?php echo e(route('admin.dashboard.index')); ?>" class="dropdown-item hover:bg-white/5">
                        <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="dropdown-item hover:bg-white/5">
                        <i data-lucide="settings" class="w-4 h-4 mr-2"></i> Settings
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="dropdown-item hover:bg-white/5">
                        <i data-lucide="help-circle" class="w-4 h-4 mr-2"></i> Help
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider border-white/[0.08]">
                </li>
                <li>
                    <a href="<?php echo e(route('logout')); ?>" class="dropdown-item hover:bg-white/5" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Account Menu -->
</div>
<!-- END: Top Bar --><?php /**PATH /media/bot/7E246BE4246B9DC1/laragon/www/parking/resources/views/partials/admin/header.blade.php ENDPATH**/ ?>