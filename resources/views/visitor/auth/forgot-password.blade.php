<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.forgot_password') }} - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('backend/assets/css/app.css') }}" />
    @include('visitor.auth.partials.dark-init')
</head>
<body class="login">
    <div class="container sm:px-10">
        <div class="flex justify-end pt-5">
            <div class="dropdown">
                <button class="dropdown-toggle btn btn-outline-secondary btn-sm" data-tw-toggle="dropdown" type="button">
                    {{ app()->getLocale() === 'bn' ? 'বাংলা' : 'English' }}
                </button>
                <div class="dropdown-menu w-40">
                    <div class="dropdown-content">
                        <form method="POST" action="{{ route('visitor.language.switch', ['locale' => 'en']) }}">@csrf<button type="submit" class="dropdown-item w-full text-left">English</button></form>
                        <form method="POST" action="{{ route('visitor.language.switch', ['locale' => 'bn']) }}">@csrf<button type="submit" class="dropdown-item w-full text-left">বাংলা</button></form>
                    </div>
                </div>
            </div>
        </div>

        <div class="block xl:grid grid-cols-2 gap-4">
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="{{ route('welcome') }}" class="-intro-x flex items-center pt-5">
                    <img alt="{{ config('app.name') }}" class="w-6" src="{{ asset('backend/assets/images/logo.svg') }}">
                    <span class="text-white text-lg ml-3">{{ config('app.name', 'Rubick') }}</span>
                </a>
                <div class="my-auto">
                    <img alt="{{ config('app.name') }}" class="-intro-x w-1/2 -mt-16" src="{{ asset('backend/assets/images/illustration.svg') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                        {{ __('auth.forgot_password') }}
                        <br>
                        {{ __('auth.forgot_password_instruction') }}
                    </div>
                </div>
            </div>

            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">{{ __('auth.forgot_password') }}</h2>
                    <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">{{ __('auth.forgot_password_instruction') }}</div>

                    @if (session('status'))
                        <div class="intro-x alert alert-success-soft mt-5 mb-5">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="intro-x alert alert-danger-soft mt-5 mb-5">
                            <ul class="list-disc list-inside text-xs">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('visitor.password.email') }}">
                        @csrf
                        <div class="intro-x mt-8">
                            <input id="email" name="email" type="email" value="{{ old('email') }}" class="intro-x login__input form-control py-3 px-4 block" placeholder="{{ __('auth.email') }}" required>
                        </div>

                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button type="submit" class="btn btn-primary py-3 px-4 w-full xl:w-auto xl:mr-3 align-top">{{ __('auth.send_reset_link') }}</button>
                            <a href="{{ route('visitor.login') }}" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-auto mt-3 xl:mt-0 align-top">{{ __('auth.login') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
        <div class="mr-4 text-slate-600 dark:text-slate-200">Dark Mode</div>
        <div class="dark-mode-switcher__toggle border"></div>
    </div>

    <script src="{{ asset('backend/assets/js/app.js') }}"></script>
    @include('visitor.auth.partials.theme-toggle-script')
</body>
</html>
