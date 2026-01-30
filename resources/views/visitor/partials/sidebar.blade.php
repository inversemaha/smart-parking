<!-- Visitor Sidebar -->
<nav class="side-nav side-nav--simple">
    <ul>
        <!-- Logo -->
        <li>
            <a href="{{ route('visitor.dashboard') }}" class="side-menu__link">
                <div class="side-menu__icon">
                    <i data-lucide="home"></i>
                </div>
                <div class="side-menu__title">
                    {{ config('app.name') }}
                    <div class="side-menu__sub-title">{{ __('general.visitor_panel') }}</div>
                </div>
            </a>
        </li>

        <li class="side-nav__devider">{{ __('general.main_menu') }}</li>

        <!-- Dashboard -->
        <li>
            <a href="{{ route('visitor.dashboard') }}" class="side-menu__link {{ request()->routeIs('visitor.dashboard') ? 'side-menu__link--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="gauge"></i>
                </div>
                <div class="side-menu__title">{{ __('general.dashboard') }}</div>
            </a>
        </li>

        <!-- Parking Locations -->
        <li>
            <a href="{{ route('visitor.parking.locations') }}" class="side-menu__link {{ request()->routeIs('visitor.parking.*') ? 'side-menu__link--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="map-pin"></i>
                </div>
                <div class="side-menu__title">{{ __('parking.locations') }}</div>
            </a>
        </li>

        <!-- Bookings -->
        <li>
            <a href="javascript:;" class="side-menu__link {{ request()->routeIs('visitor.bookings.*') ? 'side-menu__link--active side-menu__link--open' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="calendar-check"></i>
                </div>
                <div class="side-menu__title">
                    {{ __('bookings.title') }}
                    <div class="side-menu__sub-icon transform rotate-180"></div>
                </div>
            </a>
            <ul class="{{ request()->routeIs('visitor.bookings.*') ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('visitor.bookings.index') }}" class="side-menu__link {{ request()->routeIs('visitor.bookings.index') ? 'side-menu__link--active' : '' }}">
                        <div class="side-menu__icon">
                            <i data-lucide="list"></i>
                        </div>
                        <div class="side-menu__title">{{ __('bookings.my_bookings') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('visitor.bookings.create') }}" class="side-menu__link {{ request()->routeIs('visitor.bookings.create') ? 'side-menu__link--active' : '' }}">
                        <div class="side-menu__icon">
                            <i data-lucide="plus-circle"></i>
                        </div>
                        <div class="side-menu__title">{{ __('bookings.new_booking') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Vehicles -->
        <li>
            <a href="javascript:;" class="side-menu__link {{ request()->routeIs('visitor.vehicles.*') ? 'side-menu__link--active side-menu__link--open' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="car"></i>
                </div>
                <div class="side-menu__title">
                    {{ __('vehicles.title') }}
                    <div class="side-menu__sub-icon transform rotate-180"></div>
                </div>
            </a>
            <ul class="{{ request()->routeIs('visitor.vehicles.*') ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('visitor.vehicles.index') }}" class="side-menu__link {{ request()->routeIs('visitor.vehicles.index') ? 'side-menu__link--active' : '' }}">
                        <div class="side-menu__icon">
                            <i data-lucide="list"></i>
                        </div>
                        <div class="side-menu__title">{{ __('vehicles.my_vehicles') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('visitor.vehicles.create') }}" class="side-menu__link {{ request()->routeIs('visitor.vehicles.create') ? 'side-menu__link--active' : '' }}">
                        <div class="side-menu__icon">
                            <i data-lucide="plus-circle"></i>
                        </div>
                        <div class="side-menu__title">{{ __('vehicles.add_vehicle') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Payments -->
        <li>
            <a href="{{ route('visitor.payments.index') }}" class="side-menu__link {{ request()->routeIs('visitor.payments.*') ? 'side-menu__link--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="credit-card"></i>
                </div>
                <div class="side-menu__title">{{ __('payments.title') }}</div>
            </a>
        </li>

        <li class="side-nav__devider">{{ __('general.account') }}</li>

        <!-- Profile -->
        <li>
            <a href="{{ route('visitor.profile.index') }}" class="side-menu__link {{ request()->routeIs('visitor.profile.*') ? 'side-menu__link--active' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="user"></i>
                </div>
                <div class="side-menu__title">{{ __('user.profile') }}</div>
            </a>
        </li>

        <!-- Language Switcher -->
        <li>
            <a href="javascript:;" class="side-menu__link" data-tw-toggle="modal" data-tw-target="#language-modal">
                <div class="side-menu__icon">
                    <i data-lucide="globe"></i>
                </div>
                <div class="side-menu__title">
                    {{ app()->getLocale() === 'bn' ? 'ভাষা' : 'Language' }}
                </div>
            </a>
        </li>

        <!-- Logout -->
        <li>
            <form method="POST" action="{{ route('visitor.logout') }}" class="inline">
                @csrf
                <button type="submit" class="side-menu__link w-full text-left">
                    <div class="side-menu__icon">
                        <i data-lucide="log-out"></i>
                    </div>
                    <div class="side-menu__title">{{ __('auth.logout') }}</div>
                </button>
            </form>
        </li>
    </ul>
</nav>
