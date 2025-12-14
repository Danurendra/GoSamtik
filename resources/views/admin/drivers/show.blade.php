<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.drivers.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $driver->user->name }}</h1>
                    <p class="text-gray-500 mt-1">Driver Profile</p>
                </div>
            </div>
            <a href="{{ route('admin.drivers. edit', $driver) }}">
                <x-button variant="secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </x-button>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg: px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <x-card class="text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_collections'] }}</p>
                            <p class="text-sm text-gray-500">Total Pickups</p>
                        </x-card>
                        <x-card class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $stats['completed_this_month'] }}</p>
                            <p class="text-sm text-gray-500">This Month</p>
                        </x-card>
                        <x-card class="text-center">
                            <p class="text-2xl font-bold text-eco-600">{{ $stats['total_routes'] }}</p>
                            <p class="text-sm text-gray-500">Total Routes</p>
                        </x-card>
                        <x-card class="text-center">
                            <div class="flex items-center justify-center space-x-1">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c. 3-.921 1.603-. 921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-. 57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_rating'], 1) }}</span>
                            </div>
                            <p class="text-sm text-gray-500">Rating</p>
                        </x-card>
                    </div>

                    <!-- Upcoming Assignments -->
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Assignments</h2>
                        
                        @if($upcomingCollections->count() > 0)
                            <div class="space-y-3">
                                @foreach($upcomingCollections as $collection)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-lg flex flex-col items-center justify-center bg-white border border-gray-200">
                                                <span class="text-xs text-gray-500">{{ $collection->scheduled_date->format('M') }}</span>
                                                <span class="text-sm font-bold text-gray-900">{{ $collection->scheduled_date->format('d') }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 text-sm">{{ $collection->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $collection->serviceType->name }}</p>
                                            </div>
                                        </div>
                                        <x-badge : color="$collection->status_color">
                                            {{ ucfirst($collection->status) }}
                                        </x-badge>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No upcoming assignments</p>
                        @endif
                    </x-card>

                    <!-- Recent Collections -->
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Collections</h2>
                        
                        @if($recentCollections->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentCollections as $collection)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                                 style="background-color: {{ $collection->serviceType->color }}20">
                                                <svg class="w-5 h-5" style="color: {{ $collection->serviceType->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 text-sm">{{ $collection->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $collection->scheduled_date->format('M d, Y') }} • {{ $collection->serviceType->name }}</p>
                                            </div>
                                        </div>
                                        <x-badge :color="$collection->status_color">
                                            {{ ucfirst($collection->status) }}
                                        </x-badge>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No collections yet</p>
                        @endif
                    </x-card>

                    <!-- Recent Ratings -->
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Ratings</h2>
                        
                        @if($recentRatings->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentRatings as $rating)
                                    <div class="pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900">{{ $rating->user->name }}</span>
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $rating->overall_rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-. 921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($rating->comment)
                                            <p class="text-sm text-gray-600 italic">"{{ $rating->comment }}"</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">{{ $rating->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No ratings yet</p>
                        @endif
                    </x-card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Driver Info Card -->
                    <x-card>
                        <div class="text-center mb-6">
                            <div class="w-24 h-24 bg-eco-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-eco-700 font-bold text-4xl">{{ substr($driver->user->name, 0, 1) }}</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $driver->user->name }}</h3>
                            
                            <!-- Status Badge -->
                            <div class="mt-2">
                                @if($driver->availability_status === 'available')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        Available
                                    </span>
                                @elseif($driver->availability_status === 'on_route')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>
                                        On Route
                                    </span>
                                @elseif($driver->availability_status === 'offline')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                        Offline
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Email</span>
                                <span class="text-gray-900">{{ $driver->user->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Phone</span>
                                <span class="text-gray-900">{{ $driver->user->phone ??  'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Joined</span>
                                <span class="text-gray-900">{{ $driver->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </x-card>

                    <!-- License Info -->
                    <x-card>
                        <h3 class="font-semibold text-gray-900 mb-4">License Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">License #</span>
                                <span class="font-mono text-gray-900">{{ $driver->license_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Expiry</span>
                                <span class="text-gray-900 {{ $driver->license_expiry->isPast() ? 'text-red-600' : '' }}">
                                    {{ $driver->license_expiry->format('M d, Y') }}
                                </span>
                            </div>
                            @if($driver->license_expiry->isPast())
                                <div class="p-2 bg-red-50 rounded-lg">
                                    <p class="text-sm text-red-600">⚠️ License has expired! </p>
                                </div>
                            @elseif($driver->license_expiry->diffInDays(now()) <= 30)
                                <div class="p-2 bg-yellow-50 rounded-lg">
                                    <p class="text-sm text-yellow-600">⚠️ License expiring soon</p>
                                </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Vehicle Info -->
                    <x-card>
                        <h3 class="font-semibold text-gray-900 mb-4">Vehicle Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Type</span>
                                <span class="text-gray-900">{{ $driver->vehicle_type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Plate</span>
                                <span class="font-mono text-gray-900">{{ $driver->vehicle_plate }}</span>
                            </div>
                            @if($driver->vehicle_capacity)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Capacity</span>
                                    <span class="text-gray-900">{{ $driver->vehicle_capacity }}</span>
                                </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Quick Status Update -->
                    <x-card>
                        <h3 class="font-semibold text-gray-900 mb-4">Update Status</h3>
                        <form method="POST" action="{{ route('admin.drivers.status', $driver) }}">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <select name="availability_status" class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                    <option value="available" {{ $driver->availability_status == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="on_route" {{ $driver->availability_status == 'on_route' ? 'selected' : '' }}>On Route</option>
                                    <option value="offline" {{ $driver->availability_status == 'offline' ?  'selected' : '' }}>Offline</option>
                                    <option value="inactive" {{ $driver->availability_status == 'inactive' ? 'selected' :  '' }}>Inactive</option>
                                </select>
                            </div>
                            <x-button type="submit" class="w-full" variant="secondary">Update Status</x-button>
                        </form>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>