<header class="bg-white shadow">
    <nav class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2">
                <i data-lucide="car" class="h-6 w-6 text-primary"></i>
                <span class="text-xl font-semibold">Smart Parking</span>
            </a>
            <div class="flex items-center gap-4">
                <!-- Language Switcher -->
                <div class="relative group">
                    <button class="flex items-center gap-1 text-gray-700 hover:text-primary">
                        <i data-lucide="globe" class="h-5 w-5"></i>
                        <span class="text-sm uppercase">{{ app()->getLocale() }}</span>
                    </button>
                    <div class="absolute right-0 mt-0 w-40 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                        <form action="{{ route('language.switch', 'en') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center gap-2 {{ app()->getLocale() === 'en' ? 'bg-primary/10' : '' }}">
                                <i data-lucide="check" class="h-4 w-4 {{ app()->getLocale() === 'en' ? 'inline' : 'hidden' }}"></i>
                                English
                            </button>
                        </form>
                        <form action="{{ route('language.switch', 'bn') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 flex items-center gap-2 {{ app()->getLocale() === 'bn' ? 'bg-primary/10' : '' }}">
                                <i data-lucide="check" class="h-4 w-4 {{ app()->getLocale() === 'bn' ? 'inline' : 'hidden' }}"></i>
                                বাংলা (Bengali)
                            </button>
                        </form>
                    </div>
                </div>

                @auth
                    <a href="{{ route('dashboard.index') }}" class="text-gray-700 hover:text-primary">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-primary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary">Login</a>
                    <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90">Register</a>
                @endauth
            </div>
        </div>
    </nav>
</header>
