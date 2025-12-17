<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-eco rounded-xl flex items-center justify-center shadow-eco">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">GO<span class="text-eco-600">SAMTIK</span></span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden sm:flex sm:items-center sm:space-x-8">
                @auth
                    {{-- Customer Navigation --}}
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('dashboard') ? 'text-eco-600 font-medium' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('collections.index') }}" class="text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('collections.*') ? 'text-eco-600 font-medium' : '' }}">
                            My Collections
                        </a>
                        <a href="{{ route('subscriptions.index') }}" class="text-gray-600 hover: text-eco-600 transition-colors {{ request()->routeIs('subscriptions.*') ? 'text-eco-600 font-medium' : '' }}">
                            Subscriptions
                        </a>
                        <a href="{{ route('services.index') }}" class="text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('services.*') ? 'text-eco-600 font-medium' :  '' }}">
                            Services
                        </a>
                    @endif

                    {{-- Driver Navigation --}}
                    @if(auth()->user()->isDriver())
                        <a href="{{ route('driver.dashboard') }}" class="text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('driver.dashboard') ? 'text-eco-600 font-medium' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('driver.routes') }}" class="text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('driver.routes*') ? 'text-eco-600 font-medium' : '' }}">
                            My Routes
                        </a>
                    @endif

                    {{-- Admin/Provider Navigation --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isProvider())
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-eco-600 font-medium' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.collections') }}" class="text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('admin.collections*') ? 'text-eco-600 font-medium' : '' }}">
                            Collections
                        </a>
                        <a href="{{ route('admin.customers') }}" class="text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('admin.customers*') ? 'text-eco-600 font-medium' :  '' }}">
                            Customers
                        </a>
                        <a href="{{ route('admin.drivers.index') }}" class="text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('admin.drivers*') ? 'text-eco-600 font-medium' : '' }}">
                            Drivers
                        </a>
                    @endif
                @endauth
            </div>

            <!-- User Menu -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                @auth
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-400 hover:text-eco-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-eco-100 rounded-full flex items-center justify-center">
                                <span class="text-eco-700 font-medium text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                            
                            {{-- Show role badge --}}
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs text-gray-500">Logged in as</p>
                                <p class="font-medium text-gray-900">{{ ucfirst(auth()->user()->role) }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-eco-50 hover:text-eco-700">
                                Profile Settings
                            </a>
                            
                            @if(auth()->user()->isCustomer())
                                <a href="{{ route('billing.index') }}" class="block px-4 py-2 text-gray-700 hover: bg-eco-50 hover: text-eco-700">
                                    Billing History
                                </a>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-eco-50 hover:text-eco-700">
                                    System Settings
                                </a>
                            @endif

                            <hr class="my-2 border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-eco-600 transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="bg-eco-600 text-white px-5 py-2. 5 rounded-xl font-medium hover:bg-eco-700 transition-colors shadow-eco">
                        Get Started
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="open" x-transition class="sm:hidden bg-white border-t border-gray-100">
        <div class="px-4 py-3 space-y-2">
            @auth
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700">Dashboard</a>
                    <a href="{{ route('collections.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover: bg-eco-50 hover: text-eco-700">My Collections</a>
                    <a href="{{ route('subscriptions.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700">Subscriptions</a>
                @endif

                @if(auth()->user()->isAdmin() || auth()->user()->isProvider())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700">Dashboard</a>
                    <a href="{{ route('admin.collections') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700">Collections</a>
                    <a href="{{ route('admin.customers') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700">Customers</a>
                    <a href="{{ route('admin.drivers.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700">Drivers</a>
                @endif

                @if(auth()->user()->isDriver())
                    <a href="{{ route('driver.dashboard') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700">Dashboard</a>
                    <a href="{{ route('driver.routes') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700">My Routes</a>
                @endif

                <hr class="my-2 border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg text-red-600 hover:bg-red-50">
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50">Sign In</a>
                <a href="{{ route('register') }}" class="block px-4 py-2 rounded-lg bg-eco-600 text-white text-center">Get Started</a>
            @endauth
        </div>
    </div>
</nav>