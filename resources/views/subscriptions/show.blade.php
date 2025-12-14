<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('subscriptions.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $subscription->subscriptionPlan->name }}</h1>
                <p class="text-gray-500 mt-1">Subscription Details</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg: px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Status Card -->
                    <x-card>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center"
                                     style="background-color: {{ $subscription->subscriptionPlan->serviceType->color }}20">
                                    <svg class="w-7 h-7" style="color: {{ $subscription->subscriptionPlan->serviceType->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">{{ $subscription->subscriptionPlan->name }}</h2>
                                    <p class="text-gray-500">{{ $subscription->subscriptionPlan->serviceType->name }}</p>
                                </div>
                            </div>
                            <div>
                                @if($subscription->status === 'active')
                                    <x-badge color="green" class="text-sm px-3 py-1">Active</x-badge>
                                @elseif($subscription->status === 'paused')
                                    <x-badge color="yellow" class="text-sm px-3 py-1">Paused</x-badge>
                                @else
                                    <x-badge color="gray" class="text-sm px-3 py-1">{{ ucfirst($subscription->status) }}</x-badge>
                                @endif
                            </div>
                        </div>

                        @if($subscription->status === 'paused')
                            <div class="mt-4 p-4 bg-yellow-50 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-yellow-800">
                                        Paused until <strong>{{ $subscription->paused_until->format('F d, Y') }}</strong>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </x-card>

                    <!-- Schedule Details -->
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Collection Schedule</h3>
                        
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Frequency</p>
                                <p class="font-semibold text-gray-900">{{ $subscription->subscriptionPlan->frequency_label }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Time Window</p>
                                <p class="font-semibold text-gray-900">
                                    @if($subscription->schedules->first())
                                        {{ $subscription->schedules->first()->time_window }}
                                    @else
                                        Not set
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-3">Collection Days</p>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $allDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                    $scheduledDays = $subscription->schedules->pluck('day_of_week')->toArray();
                                @endphp
                                @foreach($allDays as $day)
                                    <span class="px-4 py-2 rounded-xl text-sm font-medium
                                                 {{ in_array($day, $scheduledDays) ? 'bg-eco-100 text-eco-700' :  'bg-gray-100 text-gray-400' }}">
                                        {{ ucfirst($day) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </x-card>

                    <!-- Service Address -->
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Address</h3>
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17. 657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-gray-900">{{ $subscription->service_address }}</p>
                                @if($subscription->special_instructions)
                                    <p class="text-sm text-gray-500 mt-2">
                                        <span class="font-medium">Note:</span> {{ $subscription->special_instructions }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </x-card>

                    <!-- Upcoming Collections -->
                    <x-card>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Upcoming Collections</h3>
                            <a href="{{ route('collections.index') }}? subscription={{ $subscription->id }}" class="text-sm text-eco-600 hover: text-eco-700 font-medium">
                                View All →
                            </a>
                        </div>

                        @if($upcomingCollections->count() > 0)
                            <div class="space-y-3">
                                @foreach($upcomingCollections as $collection)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-white rounded-lg flex flex-col items-center justify-center border border-gray-200">
                                                <span class="text-xs text-gray-500">{{ $collection->scheduled_date->format('M') }}</span>
                                                <span class="text-lg font-bold text-gray-900">{{ $collection->scheduled_date->format('d') }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $collection->scheduled_date->format('l') }}</p>
                                                <p class="text-sm text-gray-500">{{ $collection->time_window }}</p>
                                            </div>
                                        </div>
                                        <x-badge : color="$collection->status_color">
                                            {{ ucfirst($collection->status) }}
                                        </x-badge>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No upcoming collections scheduled.</p>
                        @endif
                    </x-card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Billing Summary -->
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Monthly Price</span>
                                <span class="font-bold text-gray-900">${{ number_format($subscription->subscriptionPlan->monthly_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Per Pickup</span>
                                <span class="text-gray-900">${{ number_format($subscription->subscriptionPlan->per_pickup_price, 2) }}</span>
                            </div>
                            <hr class="border-gray-100">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Started</span>
                                <span class="text-gray-900">{{ $subscription->start_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Next Billing</span>
                                <span class="font-medium text-gray-900">{{ $subscription->next_billing_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </x-card>

                    <!-- Actions -->
                    @if($subscription->isActive())
                        <x-card>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Manage Subscription</h3>
                            <div class="space-y-3">
                                <a href="{{ route('subscriptions.edit', $subscription) }}"
                                   class="w-full flex items-center justify-center px-4 py-2.5 bg-eco-50 text-eco-700 rounded-xl hover:bg-eco-100 transition-colors font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Schedule
                                </a>
                                
                                <button type="button" onclick="document.getElementById('pauseModal').classList.remove('hidden')"
                                        class="w-full flex items-center justify-center px-4 py-2.5 border border-yellow-300 text-yellow-700 rounded-xl hover:bg-yellow-50 transition-colors font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pause Subscription
                                </button>
                                
                                <button type="button" onclick="document.getElementById('cancelModal').classList.remove('hidden')"
                                        class="w-full flex items-center justify-center px-4 py-2.5 border border-red-300 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel Subscription
                                </button>
                            </div>
                        </x-card>
                    @elseif($subscription->isPaused())
                        <x-card>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resume Subscription</h3>
                            <form method="POST" action="{{ route('subscriptions.resume', $subscription)