<!-- Visitor Top Bar -->
<div class="top-bar relative z-[51] flex h-16 items-center">
    <!-- Mobile Menu -->
    <button class="toggle-side-nav button button--sm mr-4 border-foreground/10 outline-none xl:hidden">
        <i data-lucide="align-justify" class="stroke-[1.3] size-4"></i>
    </button>

    <!-- Breadcrumbs -->
    <div class="mr-auto">
        <div class="text-base font-medium">
            @yield('page-title', __('general.visitor_dashboard'))
        </div>
        @if(isset($breadcrumbs) || View::hasSection('breadcrumbs'))
            <div class="text-xs text-slate-500">
                @yield('breadcrumbs')
                @isset($breadcrumbs)
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        @if($index > 0) / @endif
                        @if(isset($breadcrumb['url']) && !$loop->last)
                            <a href="{{ $breadcrumb['url'] }}" class="text-primary hover:underline">{{ $breadcrumb['name'] }}</a>
                        @else
                            <span>{{ $breadcrumb['name'] }}</span>
                        @endif
                    @endforeach
                @endisset
            </div>
        @endif
    </div>

    <!-- Notifications -->
    <div class="flex items-center space-x-4">
        <!-- Quick Stats -->
        @auth
            <div class="hidden md:flex items-center space-x-4 text-xs">
                <div class="text-center">
                    <div class="font-semibold text-primary">{{ auth()->user()->vehicles()->count() }}</div>
                    <div class="text-slate-500">{{ __('vehicles.title') }}</div>
                </div>
                <div class="text-center">
                    <div class="font-semibold text-green-600">{{ auth()->user()->bookings()->where('status', 'active')->count() }}</div>
                    <div class="text-slate-500">{{ __('bookings.active') }}</div>
                </div>
                <div class="text-center">
                    <div class="font-semibold text-blue-600">{{ number_format(auth()->user()->payments()->where('status', 'paid')->sum('amount'), 0) }} BDT</div>
                    <div class="text-slate-500">{{ __('payments.total_spent') }}</div>
                </div>
            </div>

            <!-- Notification Bell -->
            <div class="relative">
                <button class="relative p-2 text-slate-600 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100">
                    <i data-lucide="bell" class="size-5"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </button>

                <!-- Notification Dropdown -->
                <div class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold">{{ __('general.notifications') }}</h3>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <!-- Sample notifications -->
                        <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                            <div class="text-sm font-medium">{{ __('notifications.booking_confirmed') }}</div>
                            <div class="text-xs text-gray-500">{{ __('general.minutes_ago', ['minutes' => 5]) }}</div>
                        </div>
                        <!-- More notifications... -->
                    </div>
                    <div class="p-3 text-center border-t border-gray-200 dark:border-gray-700">
                        <a href="#" class="text-sm text-primary hover:underline">{{ __('general.view_all') }}</a>
                    </div>
                </div>
            </div>
        @endauth

        <!-- Language Switcher -->
        <div class="relative">
            <button class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" data-tw-toggle="modal" data-tw-target="#language-modal">
                <i data-lucide="globe" class="size-4"></i>
                <span class="hidden sm:block text-sm">{{ app()->getLocale() === 'bn' ? 'বাংলা' : 'English' }}</span>
            </button>
        </div>

        <!-- User Menu -->
        @auth
            <div class="relative group">
                <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    @if(auth()->user()->avatar_path)
                        <img src="{{ Storage::url(auth()->user()->avatar_path) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-medium">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <span class="hidden md:block text-sm font-medium">{{ auth()->user()->name }}</span>
                    <i data-lucide="chevron-down" class="size-4"></i>
                </button>

                <!-- User Dropdown -->
                <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                        <div class="font-medium">{{ auth()->user()->name }}</div>
                        <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('visitor.profile.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i data-lucide="user" class="size-4 mr-2"></i>
                            {{ __('user.profile') }}
                        </a>
                        <a href="{{ route('visitor.profile.edit') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i data-lucide="settings" class="size-4 mr-2"></i>
                            {{ __('user.settings') }}
                        </a>
                        <div class="border-t border-gray-200 dark:border-gray-700"></div>
                        <form method="POST" action="{{ route('visitor.logout') }}" class="inline w-full">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                <i data-lucide="log-out" class="size-4 mr-2"></i>
                                {{ __('auth.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="flex items-center space-x-2">
                <a href="{{ route('visitor.login') }}" class="btn btn--sm btn--primary">{{ __('auth.login') }}</a>
                <a href="{{ route('visitor.register') }}" class="btn btn--sm btn--outline-primary">{{ __('auth.register') }}</a>
            </div>
        @endauth
    </div>
</div>
