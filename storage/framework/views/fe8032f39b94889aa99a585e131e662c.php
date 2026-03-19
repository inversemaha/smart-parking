<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="light">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(__('auth.login')); ?> - <?php echo e(config('app.name')); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('backend/ui/css/app.css')); ?>" />
    <?php echo $__env->make('visitor.auth.partials.dark-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body class="login">
    <div class="container sm:px-10">
        <div class="flex justify-end pt-5">
            <div class="dropdown">
                <button class="dropdown-toggle btn btn-outline-secondary btn-sm" data-tw-toggle="dropdown" type="button">
                    <?php echo e(app()->getLocale() === 'bn' ? 'বাংলা' : 'English'); ?>

                </button>
                <div class="dropdown-menu w-40">
                    <div class="dropdown-content">
                        <form method="POST" action="<?php echo e(route('visitor.language.switch', ['locale' => 'en'])); ?>"><?php echo csrf_field(); ?><button type="submit" class="dropdown-item w-full text-left">English</button></form>
                        <form method="POST" action="<?php echo e(route('visitor.language.switch', ['locale' => 'bn'])); ?>"><?php echo csrf_field(); ?><button type="submit" class="dropdown-item w-full text-left">বাংলা</button></form>
                    </div>
                </div>
            </div>
        </div>

        <div class="block xl:grid grid-cols-2 gap-4">
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="<?php echo e(route('welcome')); ?>" class="-intro-x flex items-center pt-5">
                    <img alt="<?php echo e(config('app.name')); ?>" class="w-6" src="<?php echo e(asset('backend/ui/images/logo.svg')); ?>">
                    <span class="text-white text-lg ml-3"><?php echo e(config('app.name', 'Rubick')); ?></span>
                </a>
                <div class="my-auto">
                    <img alt="<?php echo e(config('app.name')); ?>" class="-intro-x w-1/2 -mt-16" src="<?php echo e(asset('backend/ui/images/illustration.svg')); ?>">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                        <?php echo e(__('auth.welcome_back')); ?>

                        <br>
                        <?php echo e(__('auth.login_description')); ?>

                    </div>
                    <div class="-intro-x mt-5 text-lg text-white text-opacity-70"><?php echo e(__('general.find_book_park_easily')); ?></div>
                </div>
            </div>

            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left"><?php echo e(__('auth.login')); ?></h2>
                    <div class="intro-x mt-2 text-slate-400 xl:hidden text-center"><?php echo e(__('auth.login_description')); ?></div>

                    <?php if($errors->any()): ?>
                        <div class="intro-x alert alert-danger-soft mb-5">
                            <ul class="list-disc list-inside text-xs">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('visitor.login.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="intro-x mt-8">
                            <input id="login" name="login" type="text" value="<?php echo e(old('login')); ?>" class="intro-x login__input form-control py-3 px-4 block" placeholder="<?php echo e(__('auth.email_or_mobile')); ?>" required>
                            <input id="password" name="password" type="password" class="intro-x login__input form-control py-3 px-4 block mt-4" placeholder="<?php echo e(__('auth.password')); ?>" required>
                        </div>

                        <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input id="remember-me" name="remember" type="checkbox" class="form-check-input border mr-2">
                                <label class="cursor-pointer select-none" for="remember-me"><?php echo e(__('auth.remember_me')); ?></label>
                            </div>
                            <a href="<?php echo e(route('visitor.password.request')); ?>"><?php echo e(__('auth.forgot_password')); ?></a>
                        </div>

                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top" type="submit"><?php echo e(__('auth.login')); ?></button>
                            <a href="<?php echo e(route('visitor.register')); ?>" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top"><?php echo e(__('auth.register')); ?></a>
                        </div>
                    </form>

                    <div class="intro-x mt-10 xl:mt-24 text-slate-600 dark:text-slate-500 text-center xl:text-left">
                        <?php echo e(__('auth.agree_to')); ?>

                        <a class="text-primary dark:text-slate-200" href="#"><?php echo e(__('general.terms_of_service')); ?></a>
                        <?php echo e(__('general.and')); ?>

                        <a class="text-primary dark:text-slate-200" href="#"><?php echo e(__('general.privacy_policy')); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
        <div class="mr-4 text-slate-600 dark:text-slate-200">Dark Mode</div>
        <div class="dark-mode-switcher__toggle border"></div>
    </div>

    <script src="<?php echo e(asset('backend/ui/js/app.js')); ?>"></script>
    <?php echo $__env->make('visitor.auth.partials.theme-toggle-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH /media/bot/7E246BE4246B9DC1/laragon/www/parking/resources/views/visitor/auth/login.blade.php ENDPATH**/ ?>