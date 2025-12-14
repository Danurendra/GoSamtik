<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Routes</h1>
            <p class="text-gray-500 mt-1">View your past and upcoming routes</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($routes->count() > 0)
                <div class="space-y-4">
                    @foreach($routes as $route)
                        <a href="{{ route('driver.routes. show', $route) }}" class="block">
                            <x-card hover>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center
                                                    {{ $route->date->isToday() ? 'bg-eco-100' : 'bg-gray-100' }}">
                                            <span class="text-xs {{ $route->date->isToday() ? 'text-eco-600' : 'text-gray-500' }}">
                                                {{ $route->date->format('M') }}
                                            </span>
                                            <span class="text-xl font-bold {{ $route->date->isToday() ? 'text-eco-700' : 'text-gray-700' }}">
                                                {{ $route->date->format('d') }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <h3 class="font-semibold text-gray-900">
                                                    {{ $route->date->format('l') }}
                                                    @if($route->date->isToday())
                                                        <span class="text-eco-600">(Today)</span>
                                                    @endif
                                                </h3>
                                                @if($route->status === 'completed')
                                                    <x-badge color="green">Completed</x-badge>
                                                @elseif($route->status === 'in_progress')
                                                    <x-badge color="blue">In Progress</x-badge>
                                                @elseif($route->status === 'planned')
                                                    <x-badge color="yellow">Planned</x-badge>
                                                @else
                                                    <x-badge color="gray">{{ ucfirst($route->status) }}</x-badge>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $route->total_stops }} stops • 
                                                {{ $route->completed_stops }} completed
                                                @if($route->total_distance_km)
                                                    • {{ $route->total_distance_km }} km
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        @if($route->status !== 'planned')
                                            <div class="text-right">
                                                <p class="text-sm text-gray-500">Time</p>
                                                <p class="font-medium text-gray-900">
                                                    {{ $route->start_time ?  \Carbon\Carbon::parse($route->start_time)->format('g:i A') : '-' }}
                                                    @if($route->end_time)
                                                        - {{ \Carbon\Carbon::parse($route->end_time)->format('g:i A') }}
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                        <!-- Progress Bar -->
                                        <div class="w-24">
                                            <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                                <span>Progress</span>
                                                <span>{{ $route->progress_percentage }}%</span>
                                            </div>
                                            <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-eco-500 rounded-full" style="width: {{ $route->progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </x-card>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $routes->links() }}
                </div>
            @else
                <x-card>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-. 894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No routes yet</h3>
                        <p class="text-gray-500">Your assigned routes will appear here. </p>
                    </div>
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>