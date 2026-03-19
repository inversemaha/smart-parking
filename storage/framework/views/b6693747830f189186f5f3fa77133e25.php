<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Smart Parking')); ?> - <?php echo e(__('general.welcome')); ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('backend/ui/css/app.css')); ?>">

    <style>
        .hero-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h2 class="text-xl font-bold text-gray-900">
                            <?php echo e(config('app.name')); ?>

                        </h2>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative">
                        <button class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100" data-tw-toggle="modal" data-tw-target="#language-modal">
                            <i data-lucide="globe" class="size-4"></i>
                            <span class="text-sm"><?php echo e(app()->getLocale() === 'bn' ? 'বাংলা' : 'English'); ?></span>
                        </button>
                    </div>

                    <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('visitor.login')); ?>" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            <?php echo e(__('auth.login')); ?>

                        </a>
                        <a href="<?php echo e(route('visitor.register')); ?>" class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary/90">
                            <?php echo e(__('auth.register')); ?>

                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('visitor.dashboard')); ?>" class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary/90">
                            <?php echo e(__('general.dashboard')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    <?php echo e(__('general.smart_parking_system')); ?>

                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90">
                    <?php echo e(__('general.find_book_park_easily')); ?>

                </p>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold"><?php echo e($totalLocations ?? 0); ?></div>
                        <div class="text-sm opacity-80"><?php echo e(__('parking.locations')); ?></div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold"><?php echo e($totalSlots ?? 0); ?></div>
                        <div class="text-sm opacity-80"><?php echo e(__('parking.total_slots')); ?></div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold"><?php echo e($availableSlots ?? 0); ?></div>
                        <div class="text-sm opacity-80"><?php echo e(__('parking.available_now')); ?></div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold">24/7</div>
                        <div class="text-sm opacity-80"><?php echo e(__('general.support')); ?></div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="<?php echo e(route('visitor.parking.locations')); ?>" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        <?php echo e(__('parking.find_parking')); ?>

                    </a>
                    <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('visitor.register')); ?>" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                            <?php echo e(__('auth.get_started')); ?>

                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('visitor.bookings.create')); ?>" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                            <?php echo e(__('bookings.book_now')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    <?php echo e(__('general.why_choose_us')); ?>

                </h2>
                <p class="text-xl text-gray-600">
                    <?php echo e(__('general.features_description')); ?>

                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Real-time Availability -->
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="clock" class="size-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e(__('general.real_time_availability')); ?></h3>
                    <p class="text-gray-600"><?php echo e(__('general.real_time_description')); ?></p>
                </div>

                <!-- Easy Booking -->
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="smartphone" class="size-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e(__('general.easy_booking')); ?></h3>
                    <p class="text-gray-600"><?php echo e(__('general.easy_booking_description')); ?></p>
                </div>

                <!-- Secure Payment -->
                <div class="feature-card bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="shield-check" class="size-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e(__('general.secure_payment')); ?></h3>
                    <p class="text-gray-600"><?php echo e(__('general.secure_payment_description')); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Locations -->
    <?php if(isset($featuredLocations) && $featuredLocations->count() > 0): ?>
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    <?php echo e(__('parking.featured_locations')); ?>

                </h2>
                <p class="text-xl text-gray-600">
                    <?php echo e(__('parking.featured_description')); ?>

                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__currentLoopData = $featuredLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden feature-card">
                    <?php if($location->image): ?>
                        <img src="<?php echo e(Storage::url($location->image)); ?>" alt="<?php echo e($location->name); ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <i data-lucide="map-pin" class="size-12 text-white"></i>
                        </div>
                    <?php endif; ?>

                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo e($location->name); ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo e($location->address); ?></p>

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-600"><?php echo e($location->available_slots_count ?? 0); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e(__('parking.available')); ?></div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-600">৳<?php echo e($location->hourly_rate ?? 0); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e(__('parking.per_hour')); ?></div>
                                </div>
                            </div>
                        </div>

                        <a href="<?php echo e(route('visitor.parking.location.details', $location)); ?>" class="w-full bg-primary text-white text-center py-2 rounded-lg hover:bg-primary/90 transition-colors">
                            <?php echo e(__('general.view_details')); ?>

                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="text-center mt-12">
                <a href="<?php echo e(route('visitor.parking.locations')); ?>" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                    <?php echo e(__('parking.view_all_locations')); ?>

                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA Section -->
    <section class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                <?php echo e(__('general.ready_to_start')); ?>

            </h2>
            <p class="text-xl mb-8 opacity-90">
                <?php echo e(__('general.cta_description')); ?>

            </p>

            <?php if(auth()->guard()->guest()): ?>
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="<?php echo e(route('visitor.register')); ?>" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                        <?php echo e(__('auth.register_free')); ?>

                    </a>
                    <a href="<?php echo e(route('visitor.parking.locations')); ?>" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition-colors">
                        <?php echo e(__('parking.browse_locations')); ?>

                    </a>
                </div>
            <?php else: ?>
                <a href="<?php echo e(route('visitor.bookings.create')); ?>" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                    <?php echo e(__('bookings.start_booking')); ?>

                </a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-semibold mb-4"><?php echo e(config('app.name')); ?></h3>
                    <p class="text-sm"><?php echo e(__('general.app_description')); ?></p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4"><?php echo e(__('general.quick_links')); ?></h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="<?php echo e(route('visitor.parking.locations')); ?>" class="hover:text-white"><?php echo e(__('parking.find_parking')); ?></a></li>
                        <li><a href="<?php echo e(route('visitor.register')); ?>" class="hover:text-white"><?php echo e(__('auth.register')); ?></a></li>
                        <li><a href="<?php echo e(route('visitor.login')); ?>" class="hover:text-white"><?php echo e(__('auth.login')); ?></a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4"><?php echo e(__('general.support')); ?></h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white"><?php echo e(__('general.help_center')); ?></a></li>
                        <li><a href="#" class="hover:text-white"><?php echo e(__('general.contact_us')); ?></a></li>
                        <li><a href="#" class="hover:text-white"><?php echo e(__('general.faq')); ?></a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4"><?php echo e(__('general.legal')); ?></h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white"><?php echo e(__('general.privacy_policy')); ?></a></li>
                        <li><a href="#" class="hover:text-white"><?php echo e(__('general.terms_of_service')); ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. <?php echo e(__('general.all_rights_reserved')); ?></p>
            </div>
        </div>
    </footer>

    <!-- Language Modal -->
    <?php echo $__env->make('visitor.partials.language-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- JavaScript -->
    <script src="<?php echo e(asset('backend/ui/js/app.js')); ?>"></script>
    <script>
        // Initialize Lucide icons
        if (window.createIcons && window.icons) {
            window.createIcons({ icons: window.icons, nameAttr: 'data-lucide' });
        }
    </script>
</body>
</html>
<?php /**PATH /media/bot/7E246BE4246B9DC1/laragon/www/parking/resources/views/visitor/welcome.blade.php ENDPATH**/ ?>