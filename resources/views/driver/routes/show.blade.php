<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('driver.routes') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Route Details</h1>
                <p class="text-gray-500 mt-1">{{ $route->date->format('l, M d, Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Route Status Card -->
            <x-card class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-3">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $route->date->format('l') }}'s Route</h2>
                            @if($route->status === 'completed')
                                <x-badge color="green">Completed</x-badge>
                            @elseif($route->status === 'in_progress')
                                <x-badge color="blue">In Progress</x-badge>
                            @elseif($route->status === 'planned')
                                <x-badge color="yellow">Planned</x-badge>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $route->completed_stops }} of {{ $route->total_stops }} stops completed
                        </p>
                    </div>
                    <div class="text-right">
                        @if($route->start_time)
                            <p class="text-sm text-gray-500">Started at</p>
                            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($route->start_time)->format('g:i A') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-eco-500 rounded-full transition-all duration-500" style="width: {{ $route->progress_percentage }}%"></div>
                    </div>
                </div>

                <!-- Actions -->
                @if($route->status === 'planned' && $route->date->isToday())
                    <div class="mt-4">
                        <form method="POST" action="{{ route('driver.routes.start', $route) }}">
                            @csrf
                            <x-button type="submit" class="w-full">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14. 752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                                Start Route
                            </x-button>
                        </form>
                    </div>
                @endif
            </x-card>

            <!-- Stops List -->
            <x-card>
                <h3 class="font-semibold text-gray-900 mb-6">Collection Stops</h3>

                <div class="space-y-4">
                    @foreach($route->stops as $index => $stop)
                        <div class="relative flex items-start space-x-4 p-4 rounded-xl
                                    {{ $stop->status === 'completed' ?  'bg-green-50' : ($stop->status === 'arrived' ? 'bg-blue-50' : ($stop->status === 'skipped' ? 'bg-red-50' : 'bg-gray-50')) }}">
                            
                            <!-- Connector Line -->
                            @if(! $loop->last)
                                <div class="absolute left-8 top-16 w-0.5 h-8 bg-gray-200"></div>
                            @endif

                            <!-- Step Number -->
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                        {{ $stop->status === 'completed' ?  'bg-green-500 text-white' : ($stop->status === 'arrived' ? 'bg-blue-500 text-white' : ($stop->status === 'skipped' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-600')) }}">
                                @if($stop->status === 'completed')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($stop->status === 'skipped')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    {{ $stop->sequence_order }}
                                @endif
                            </div>

                            <!-- Stop Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-900">{{ $stop->collection->user->name }}</p>
                                    @if($stop->status === 'completed')
                                        <span class="text-xs text-green-600">Completed {{ $stop->completed_at ? $stop->completed_at->format('g:i A') : '' }}</span>
                                    @elseif($stop->status === 'skipped')
                                        <span class="text-xs text-red-600">Skipped</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $stop->collection->serviceType->name }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17. 657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    {{ $stop->collection->service_address }}
                                </p>

                                @if($stop->notes)
                                    <p class="text-sm text-gray-500 mt-2 italic">Note: {{ $stop->notes }}</p>
                                @endif
                            </div>

                            <!-- Phone -->
                            @if($stop->collection->user->phone)
                                <a href="tel:{{ $stop->collection->user->phone }}" 
                                   class="flex-shrink-0 w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>