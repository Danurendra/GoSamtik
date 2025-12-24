<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-eco-100 bg-white/80 backdrop-blur-lg transition-colors duration-300 supports-[backdrop-filter]:bg-white/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center shadow-sm group-hover:border-eco-300 group-hover:shadow-eco/20 transition-all duration-300">
                        <div class="relative">
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-eco-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <div class="absolute -bottom-1 -right-1 bg-eco-500 rounded-full p-0.5 border border-white">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <span class="text-xl font-extrabold text-gray-900 tracking-tight">
                        Go<span class="text-eco-600">Samtik</span>
                    </span>
                </a>
            </div>

            <div class="hidden sm:flex sm:items-center sm:space-x-8">
                @auth
                    {{-- Customer Navigation --}}
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('dashboard') ? 'text-eco-600' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('collections.index') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('collections.*') ? 'text-eco-600' : '' }}">
                            My Collections
                        </a>
                        <a href="{{ route('subscriptions.index') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('subscriptions.*') ? 'text-eco-600' : '' }}">
                            Subscriptions
                        </a>
                        <a href="{{ route('services.index') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('services.*') ? 'text-eco-600' :  '' }}">
                            Services
                        </a>
                    @endif

                    {{-- Driver Navigation --}}
                    @if(auth()->user()->isDriver())
                        <a href="{{ route('driver.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('driver.dashboard') ? 'text-eco-600' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('driver.routes') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('driver.routes*') ? 'text-eco-600' : '' }}">
                            My Routes
                        </a>
                    @endif

                    {{-- Admin/Provider Navigation --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isProvider())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-eco-600' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.collections') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('admin.collections*') ? 'text-eco-600' : '' }}">
                            Collections
                        </a>
                        <a href="{{ route('admin.customers') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('admin.customers*') ? 'text-eco-600' :  '' }}">
                            Customers
                        </a>
                        <a href="{{ route('admin.drivers.index') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors {{ request()->routeIs('admin.drivers*') ? 'text-eco-600' : '' }}">
                            Drivers
                        </a>
                    @endif
                @endauth
            </div>

            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                @auth
                    <button class="relative p-2 text-gray-400 hover:text-eco-600 transition-colors rounded-full hover:bg-eco-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 p-1.5 pr-3 rounded-full border border-gray-100 hover:border-eco-200 bg-white hover:bg-eco-50 transition-all shadow-sm">
                            <div class="w-8 h-8 bg-gradient-to-br from-eco-100 to-eco-200 rounded-full flex items-center justify-center text-eco-700 font-bold text-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="text-gray-700 font-medium text-sm">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white/90 backdrop-blur-xl rounded-xl shadow-xl border border-eco-100 py-2 z-50">

                            {{-- Show role badge --}}
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Logged in as</p>
                                <p class="font-bold text-eco-700 text-sm mt-0.5">{{ ucfirst(auth()->user()->role) }}</p>
                            </div>

                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-eco-50 hover:text-eco-700 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Profile Settings
                                </a>

                                @if(auth()->user()->isCustomer())
                                    <a href="{{ route('billing.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-eco-50 hover:text-eco-700 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        Billing History
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-eco-50 hover:text-eco-700 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        System Settings
                                    </a>
                                @endif
                            </div>

                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-eco-600 transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="bg-eco-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-eco-700 transition-colors shadow-lg shadow-eco/30">
                        Get Started
                    </a>
                @endauth
            </div>

            <div class="sm:hidden flex items-center">
                <button @click="open = !open" class="p-2 rounded-lg text-gray-500 hover:text-eco-600 hover:bg-eco-50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-transition class="sm:hidden bg-white/95 backdrop-blur-xl border-t border-eco-100">
        <div class="px-4 py-3 space-y-2">
            @auth
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700 font-medium">Dashboard</a>
                    <a href="{{ route('collections.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700 font-medium">My Collections</a>
                    <a href="{{ route('subscriptions.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700 font-medium">Subscriptions</a>
                @endif

                @if(auth()->user()->isAdmin() || auth()->user()->isProvider())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700 font-medium">Dashboard</a>
                    <a href="{{ route('admin.collections') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700 font-medium">Collections</a>
                    <a href="{{ route('admin.customers') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700 font-medium">Customers</a>
                    <a href="{{ route('admin.drivers.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700 font-medium">Drivers</a>
                @endif

                @if(auth()->user()->isDriver())
                    <a href="{{ route('driver.dashboard') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700 font-medium">Dashboard</a>
                    <a href="{{ route('driver.routes') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 hover:text-eco-700 font-medium">My Routes</a>
                @endif

                <div class="border-t border-gray-100 my-2 pt-2">
                    <div class="px-4 py-2 flex items-center space-x-3">
                        <div class="w-8 h-8 bg-eco-100 rounded-full flex items-center justify-center text-eco-700 font-bold text-xs">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                            <div class="font-medium text-xs text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 rounded-lg text-red-600 hover:bg-red-50 font-medium">
                            Sign Out
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-eco-50 font-medium">Sign In</a>
                <a href="{{ route('register') }}" class="block px-4 py-2 rounded-lg bg-eco-600 text-white text-center font-medium shadow-md">Get Started</a>
            @endauth
        </div>
    </div>
</nav>
