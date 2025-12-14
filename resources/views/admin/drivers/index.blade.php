<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Drivers</h1>
                <p class="text-gray-500 mt-1">Manage your delivery team</p>
            </div>
            <a href="{{ route('admin.drivers.create') }}">
                <x-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Driver
                </x-button>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <x-card class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    <p class="text-sm text-gray-500">Total Drivers</p>
                </x-card>
                <x-card class="text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['available'] }}</p>
                    </div>
                    <p class="text-sm text-gray-500">Available</p>
                </x-card>
                <x-card class="text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <span class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></span>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['on_route'] }}</p>
                    </div>
                    <p class="text-sm text-gray-500">On Route</p>
                </x-card>
                <x-card class="text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <span class="w-3 h-3 bg-gray-400 rounded-full"></span>
                        <p class="text-2xl font-bold text-gray-600">{{ $stats['offline'] }}</p>
                    </div>
                    <p class="text-sm text-gray-500">Offline</p>
                </x-card>
            </div>

            <!-- Filters -->
            <x-card class="mb-6">
                <form method="GET" action="{{ route('admin.drivers.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[250px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by name, email, or plate..."
                               class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                    </div>
                    <div class="w-44">
                        <select name="status" class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="on_route" {{ request('status') == 'on_route' ?  'selected' : '' }}>On Route</option>
                            <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Offline</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <x-button type="submit" variant="secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </x-button>
                </form>
            </x-card>

            <!-- Drivers Grid -->
            @if($drivers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($drivers as $driver)
                        <x-card hover class="relative">
                            <!-- Status Indicator -->
                            <div class="absolute top-4 right-4">
                                @if($driver->availability_status === 'available')
                                    <span class="inline-flex items-center px-2. 5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                        Available
                                    </span>
                                @elseif($driver->availability_status === 'on_route')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-1.5 animate-pulse"></span>
                                        On Route
                                    </span>
                                @elseif($driver->availability_status === 'offline')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-1.5"></span>
                                        Offline
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            <!-- Driver Info -->
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-14 h-14 bg-eco-100 rounded-full flex items-center justify-center">
                                    <span class="text-eco-700 font-bold text-xl">{{ substr($driver->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $driver->user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $driver->user->email }}</p>
                                </div>
                            </div>

                            <!-- Vehicle Info -->
                            <div class="bg-gray-50 rounded-xl p-3 mb-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">{{ $driver->vehicle_type }}</span>
                                    </div>
                                    <span class="text-sm font-mono text-gray-600">{{ $driver->vehicle_plate }}</span>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center">
                                    <p class="text-lg font-bold text-gray-900">{{ $driver->total_collections }}</p>
                                    <p class="text-xs text-gray-500">Total Pickups</p>
                                </div>
                                <div class="text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        <span class="text-lg font-bold text-gray-900">{{ number_format($driver->average_rating, 1) }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Rating</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.drivers. show', $driver) }}" class="flex-1">
                                    <x-button variant="secondary" class="w-full" size="sm">View</x-button>
                                </a>
                                <a href="{{ route('admin.drivers. edit', $driver) }}" class="flex-1">
                                    <x-button variant="outline" class="w-full" size="sm">Edit</x-button>
                                </a>
                            </div>
                        </x-card>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $drivers->links() }}
                </div>
            @else
                <x-card>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No drivers found</h3>
                        <p class="text-gray-500 mb-6">Get started by adding your first driver. </p>
                        <a href="{{ route('admin.drivers.create') }}">
                            <x-button>Add Driver</x-button>
                        </a>
                    </div>
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>