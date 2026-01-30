<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Parking System') }} @hasSection('title') - @yield('title') @endif</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900">
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard.index') }}" class="flex items-center">
                        <h1 class="text-xl font-bold text-blue-600">{{ config('app.name') }}</h1>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-2">
                        <form method="POST" action="{{ route('language.switch', 'en') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-sm {{ app()->getLocale() == 'en' ? 'bg-blue-100 text-blue-800' : 'text-gray-600 hover:text-blue-600' }} rounded-md">
                                English
                            </button>
                        </form>
                        <span class="text-gray-300">|</span>
                        <form method="POST" action="{{ route('language.switch', 'bn') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 text-sm {{ app()->getLocale() == 'bn' ? 'bg-blue-100 text-blue-800' : 'text-gray-600 hover:text-blue-600' }} rounded-md">
                                বাংলা
                            </button>
                        </form>
                    </div>

                    @auth
                        <span class="text-sm text-gray-700">{{ Auth::user()->first_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700">
                                {{ __('auth.logout') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">{{ __('auth.login') }}</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm">{{ __('auth.register') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <div class="text-sm text-red-700">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
</body>
</html>
