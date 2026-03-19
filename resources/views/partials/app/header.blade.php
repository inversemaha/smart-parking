<header class="bg-white shadow">
    <nav class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2">
                <i data-lucide="car" class="h-6 w-6 text-primary"></i>
                <span class="text-xl font-semibold">Smart Parking</span>
            </a>
            <div class="flex items-center gap-4">
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
