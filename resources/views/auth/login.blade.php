<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/app.css') }}">
    <script>
        (function () {
            const mode = localStorage.getItem('darkMode');
            const isSystemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const shouldDark = mode === 'active' || (mode === 'system' && isSystemDark);
            document.documentElement.classList.toggle('dark', shouldDark);
        })();
    </script>
</head>
<body class="login">
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="{{ route('home') }}" class="-intro-x flex items-center pt-5">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-white text-lg ml-3">{{ config('app.name') }}</span>
                </a>
                <div class="my-auto">
                    <img alt="Parking System" class="-intro-x w-1/2 -mt-16" src="{{ asset('backend/assets/images/error-illustration.svg') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                        A few more clicks to
                        <br>
                        sign in to your account.
                    </div>
                    <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">Manage your smart parking system in one place</div>
                </div>
            </div>
            <!-- END: Login Info -->
            
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-slate-800 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                        Sign In
                    </h2>
                    <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">A few more clicks to sign in to your account. Manage your parking system in one place</div>
                    
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="intro-x mt-6 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-lg">
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 dark:text-red-400 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="intro-x mt-8">
                        @csrf

                        <div class="intro-x">
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email', 'admin@parking.com') }}"
                                required
                                autofocus
                                class="intro-x login__input form-control py-3 px-4 block{{ $errors->has('email') ? ' border-red-500' : '' }}" 
                                placeholder="Email"
                            >
                            <input 
                                type="password" 
                                name="password" 
                                value="password"
                                required
                                class="intro-x login__input form-control py-3 px-4 block mt-4{{ $errors->has('password') ? ' border-red-500' : '' }}" 
                                placeholder="Password"
                            >
                        </div>

                        <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input id="remember-me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="form-check-input border mr-2">
                                <label class="cursor-pointer select-none" for="remember-me">Remember me</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            @endif
                        </div>

                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button type="submit" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Login</button>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top">Register</a>
                            @endif
                        </div>
                    </form>

                    <div class="intro-x mt-10 xl:mt-24 text-slate-600 dark:text-slate-500 text-center xl:text-left text-sm">
                        By signing in, you agree to our 
                        <a class="text-primary dark:text-slate-200" href="#">Terms and Conditions</a> & 
                        <a class="text-primary dark:text-slate-200" href="#">Privacy Policy</a>
                    </div>
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>

    <!-- BEGIN: Dark Mode Switcher-->
    <div id="dark-mode-switcher" class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box border rounded-full w-40 h-12 flex items-center justify-between z-50 mb-10 mr-10 px-3">
        <div class="text-slate-600 dark:text-slate-200 text-sm font-medium">Dark Mode</div>
        <div class="relative w-12 h-7 bg-slate-300 rounded-full">
            <div class="dark-mode-switcher__toggle"></div>
        </div>
    </div>
    <!-- END: Dark Mode Switcher-->

    <style>
        /* Dark mode switcher - proper toggle switch styling */
        .dark-mode-switcher {
            display: flex;
            align-items: center;
            gap: 12px;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 8px 14px;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: fixed;
            bottom: 40px;
            right: 40px;
            z-index: 50;
            width: auto;
            height: auto;
        }
        
        .dark-mode-switcher:hover {
            background-color: #e2e8f0;
            border-color: #cbd5e1;
        }
        
        .dark-mode-switcher.active {
            background-color: #1e293b;
            border-color: #475569;
        }
        
        .dark-mode-switcher.active:hover {
            background-color: #0f172a;
        }
        
        /* Text styling */
        .dark-mode-switcher .text-slate-600 {
            color: #475569;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            transition: color 0.3s ease;
        }
        
        .dark-mode-switcher.active .text-slate-600 {
            color: #cbd5e1;
        }
        
        /* Toggle switch container */
        .dark-mode-switcher .relative {
            background-color: #cbd5e1;
            width: 44px;
            height: 24px;
            border-radius: 12px;
            transition: background-color 0.3s ease;
            position: relative;
            flex-shrink: 0;
        }
        
        .dark-mode-switcher.active .relative {
            background-color: #64748b;
        }
        
        /* Toggle circle - only this moves */
        .dark-mode-switcher__toggle {
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            position: absolute;
            bottom: 2px;
            left: 2px;
            transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }
        
        .dark-mode-switcher.active .dark-mode-switcher__toggle {
            left: 22px;
        }
    </style>

    <script src="{{ asset('backend/assets/js/app.js') }}"></script>
    <script>
        // Wait for DOM to be fully loaded
        setTimeout(function() {
            const darkModeSwitcher = document.querySelector('.dark-mode-switcher');
            
            if (darkModeSwitcher) {
                // Remove the data-url attribute to prevent navigation
                darkModeSwitcher.removeAttribute('data-url');
                
                // Remove all existing click listeners by cloning
                const clonedSwitcher = darkModeSwitcher.cloneNode(true);
                clonedSwitcher.removeAttribute('data-url');
                darkModeSwitcher.parentNode.replaceChild(clonedSwitcher, darkModeSwitcher);
                
                // Add our custom click handler
                const newSwitcher = document.querySelector('.dark-mode-switcher');
                
                // Initialize toggle state based on current dark mode
                if (document.documentElement.classList.contains('dark')) {
                    newSwitcher.classList.add('active');
                }
                
                newSwitcher.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Toggle dark class on html element
                    const html = document.documentElement;
                    const isDark = html.classList.contains('dark');
                    
                    if (isDark) {
                        html.classList.remove('dark');
                        this.classList.remove('active');
                        localStorage.setItem('darkMode', 'inactive');
                        console.log('Dark mode OFF');
                    } else {
                        html.classList.add('dark');
                        this.classList.add('active');
                        localStorage.setItem('darkMode', 'active');
                        console.log('Dark mode ON');
                    }
                });
            }
        }, 500);

        // Initialize dark mode on page load
        document.addEventListener('DOMContentLoaded', function() {
            const darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'active') {
                document.documentElement.classList.add('dark');
                const switcher = document.querySelector('.dark-mode-switcher');
                if (switcher) {
                    switcher.classList.add('active');
                }
            }
        });
    </script>
</body>
</html>
