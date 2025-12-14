<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Driver Dashboard</h1>
                <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Status: </span>
                @if($driver->availability_status === 'available')
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        Available
                    </span>
                @elseif($driver->availability_status === 'on_route')
                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>
                        On Route
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                        <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                        {{ ucfirst(str_replace('_', ' ', $driver->availability_status)) }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <x-card class="text-center">
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['today_collections'] }}</p>
                    <p class="text-sm text-gray-500">Today's Pickups</p>
                </x-card>
                <x-card class="text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $stats['completed_today'] }}</p>
                    <p class="text-sm text-gray-500">Completed</p>
                </x-card>
                <x-card class="text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_today'] }}</p>
                    <p class="text-sm text-gray-500">Pending</p>
                </x-card>
                <x-card class="text-center">
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_collections'] }}</p>
                    <p class="text-sm text-gray-500">Total Pickups</p>
                </x-card>
                <x-card class="text-center">
                    <div class="flex items-center justify-center space-x-1">
                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c. 3-.921 1.603-. 921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="text-3xl font-bold text-gray-900">{{ number_format($stats['average_rating'], 1) }}</span>
                    </div>
                    <p class="text-sm text-gray-500">Rating</p>
                </x-card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Today's Collections -->
                <div class="lg:col-span-2">
                    <x-card>
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-900">Today's Collections</h2>
                            <span class="text-sm text-gray-500">{{ now()->format('l, M d') }}</span>
                        </div>

                        @if($todayCollections->count() > 0)
                            @if($todayRoute && $todayRoute->status === 'planned')
                                <div class="mb-4">
                                    <form method="POST" action="{{ route('driver.routes.start', $todayRoute) }}">
                                        @csrf
                                        <x-button type="submit" class="w-full">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14. 752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Start Today's Route
                                        </x-button>
                                    </form>
                                </div>
                            @endif

                            <div class="space-y-4">
                                @foreach($todayCollections as $index => $collection)
                                    <div class="relative flex items-start space-x-4 p-4 rounded-xl
                                                {{ $collection->status === 'completed' ?  'bg-green-50' : ($collection->status === 'in_progress' ? 'bg-blue-50' : 'bg-gray-50') }}">
                                        <!-- Step Number -->
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                                    {{ $collection->status === 'completed' ?  'bg-green-500 text-white' : ($collection->status === 'in_progress' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-600') }}">
                                            @if($collection->status === 'completed')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>

                                        <!-- Collection Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="font-medium text-gray-900">{{ $collection->user->name }}</p>
                                                <x-badge : color="$collection->status_color">
                                                    {{ ucfirst($collection->status) }}
                                                </x-badge>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $collection->serviceType->name }} • 
                                                {{ \Carbon\Carbon::parse($collection->scheduled_time_start)->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($collection->scheduled_time_end)->format('g:i A') }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1 truncate">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                                {{ $collection->service_address }}
                                            </p>

                                            @if($collection->special_instructions)
                                                <p class="text-sm text-yellow-700 bg-yellow-100 px-2 py-1 rounded mt-2 inline-block">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $collection->special_instructions }}
                                                </p>
                                            @endif

                                            <!-- Action Buttons -->
                                            @if($todayRoute && $todayRoute->status === 'in_progress' && $collection->status === 'in_progress')
                                                @php
                                                    $stop = $todayRoute->stops->where('collection_id', $collection->id)->first();
                                                @endphp
                                                @if($stop)
                                                    <div class="flex space-x-2 mt-3">
                                                        @if($stop->status === 'pending')
                                                            <form method="POST" action="{{ route('driver.stops.arrive', $stop) }}">
                                                                @csrf
                                                                <x-button type="submit" size="sm">
                                                                    I've Arrived
                                                                </x-button>
                                                            </form>
                                                        @elseif($stop->status === 'arrived')
                                                            <form method="POST" action="{{ route('driver.stops.complete', $stop) }}">
                                                                @csrf
                                                                <x-button type="submit" size="sm">
                                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                    Mark Complete
                                                                </x-button>
                                                            </form>
                                                            <button type="button" onclick="document.getElementById('skipModal{{ $stop->id }}').classList.remove('hidden')"
                                                                    class="px-3 py-1.5 text-sm border border-red-300 text-red-600 rounded-lg hover:bg-red-50">
                                                                Skip
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- Phone Call -->
                                        @if($collection->user->phone)
                                            <a href="tel:{{ $collection->user->phone }}" 
                                               class="flex-shrink-0 w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center text-eco-600 hover:bg-eco-200 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Skip Modal -->
                                    @if($todayRoute)
                                        @php $stop = $todayRoute->stops->where('collection_id', $collection->id)->first(); @endphp
                                        @if($stop)
                                            <div id="skipModal{{ $stop->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Skip This Collection? </h3>
                                                    <form method="POST" action="{{ route('driver.stops.skip', $stop) }}">
                                                        @csrf
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                                                            <select name="reason" required class="w-full rounded-xl border-gray-200">
                                                                <option value="">Select a reason</option>
                                                                <option value="Customer not available">Customer not available</option>
                                                                <option value="Wrong address">Wrong address</option>
                                                                <option value="No waste to collect">No waste to collect</option>
                                                                <option value="Access denied">Access denied</option>
                                                                <option value="Unsafe location">Unsafe location</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="flex space-x-3">
                                                            <button type="button" onclick="document.getElementById('skipModal{{ $stop->id }}').classList.add('hidden')"
                                                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">
                                                                Skip Collection
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>

                            @if($todayRoute && $todayRoute->status === 'in_progress' && $todayRoute->completed_stops >= $todayRoute->total_stops)
                                <div class="mt-6">
                                    <form method="POST" action="{{ route('driver.routes.complete', $todayRoute) }}">
                                        @csrf
                                        <x-button type="submit" class="w-full" variant="outline">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Complete Route
                                        </x-button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No collections today</h3>
                                <p class="text-gray-500">Enjoy your day off!</p>
                            </div>
                        @endif
                    </x-card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Vehicle Info -->
                    <x-card>
                        <h3 class="font-semibold text-gray-900 mb-4">Your Vehicle</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Type</span>
                                <span class="font-medium text-gray-900">{{ $driver->vehicle_type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Plate</span>
                                <span class="font-medium text-gray-900">{{ $driver->vehicle_plate }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Capacity</span>
                                <span class="font-medium text-gray-900">{{ $driver->vehicle_capacity ??  'N/A' }}</span>
                            </div>
                        </div>
                    </x-card>

                    <!-- Recent Ratings -->
                    <x-card>
                        <h3 class="font-semibold text-gray-900 mb-4">Recent Ratings</h3>
                        @if($recentRatings->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentRatings as $rating)
                                    <div class="pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-900">{{ $rating->user->name }}</span>
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $rating->overall_rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-. 921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-. 57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($rating->comment)
                                            <p class="text-sm text-gray-600 italic">"{{ Str::limit($rating->comment, 60) }}"</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">{{ $rating->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm text-center py-4">No ratings yet</p>
                        @endif
                    </x-card>

                    <!-- Quick Links -->
                    <x-card class="bg-gray-50 border-0">
                        <h3 class="font-semibold text-gray-900 mb-3">Quick Links</h3>
                        <div class="space-y-2">
                            <a href="{{ route('driver.routes') }}" class="flex items-center text-eco-600 hover:text-eco-700 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                                View All Routes
                            </a>
                            <a href="{{ route('profile. edit') }}" class="flex items-center text-gray-600 hover:text-gray-700 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-. 94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Profile Settings
                            </a>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>