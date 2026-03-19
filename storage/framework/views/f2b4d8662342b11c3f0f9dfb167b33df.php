<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="light">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Smart Parking System - Authentication Portal">
    <meta name="keywords" content="parking, authentication, login, register">
    <meta name="author" content="Smart Parking System">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">

    <title><?php echo $__env->yieldContent('title', 'Authentication'); ?> - <?php echo e(config('app.name')); ?></title>

    <!-- Rubick Theme CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/css/app.css')); ?>">

    <script>
        (function () {
            const mode = localStorage.getItem('darkMode');
            const isSystemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const shouldDark = mode === 'active' || (mode === 'system' && isSystemDark);
            document.documentElement.classList.toggle('dark', shouldDark);
        })();
    </script>
</head>
<body class="bg-background dark:bg-foreground/[.01]">
    <!-- Page Loader -->
    <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
        <div class="loader-spinner !w-14"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Logo & Branding -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 bg-primary/20 flex items-center justify-center rounded-lg">
                        <i data-lucide="car" class="w-6 h-6 stroke-primary"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-foreground"><?php echo e(config('app.name')); ?></h1>
                <p class="text-slate-500 mt-2">Smart Parking System</p>
            </div>

            <!-- Alert Messages -->
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 rounded-lg border border-success/30 bg-success/10 text-success text-sm flex items-start gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <div><?php echo e(session('success')); ?></div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-4 p-4 rounded-lg border border-danger/30 bg-danger/10 text-danger text-sm flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <div><?php echo e(session('error')); ?></div>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-4 p-4 rounded-lg border border-danger/30 bg-danger/10 text-danger text-sm">
                    <div class="font-medium mb-2 flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                        Errors found:
                    </div>
                    <ul class="space-y-1 ml-7">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-disc"><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Main Content -->
            <?php echo $__env->yieldContent('content'); ?>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-slate-500">
                <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Rubick Theme JS -->
    <script src="<?php echo e(asset('backend/assets/js/app.js')); ?>"></script>

    <!-- Auto-hide Flash Messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[class*="bg-success"], [class*="bg-danger"]');
            if (alerts.length > 0) {
                setTimeout(function() {
                    alerts.forEach(alert => {
                        alert.style.transition = 'opacity 0.5s ease-out';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.style.display = 'none', 500);
                    });
                }, 5000);
            }
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /media/bot/7E246BE4246B9DC1/laragon/www/parking/resources/views/layouts/auth.blade.php ENDPATH**/ ?>