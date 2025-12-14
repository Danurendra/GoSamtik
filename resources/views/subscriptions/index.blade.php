<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Subscriptions</h1>
                <p class="text-gray-500 mt-1">Manage your recurring waste collection plans</p>
            </div>
            <a href="{{ route('subscriptions.create') }}">
                <x-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Subscription
                </x-button>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($subscriptions->count() > 0)
                <!-- Active Subscriptions -->
                @php
                    $activeSubscriptions = $subscriptions->where('status', 'active');
                    $pausedSubscriptions = $subscriptions->where('status', 'paused');
                    $cancelledSubscriptions = $subscriptions->whereIn('status', ['cancelled', 'expired']);
                @endphp

                @if($activeSubscriptions->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Active Subscriptions</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($activeSubscriptions as $subscription)
                                <x-card hover class="relative overflow-hidden">
                                    <!-- Status Badge -->
                                    <div class="absolute top-4 right-4">
                                        <x-badge color="green">Active</x-badge>
                                    </div>

                                    <!-- Service Icon & Name -->
                                    <div class="flex items-start space-x-4 mb-4">
                                        <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0" 
                                             style="background-color: {{ $subscription->subscriptionPlan->serviceType->color }}20">
                                            <svg class="w-7 h-7" style="color: {{ $subscription->subscriptionPlan->serviceType->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 truncate">{{ $subscription->subscriptionPlan->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $subscription->subscriptionPlan->serviceType->name }}</p>
                                        </div>
                                    </div>

                                    <!-- Frequency -->
                                    <div class="flex items-center text-sm text-gray-600 mb-3">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $subscription->subscriptionPlan->frequency_label }}
                                    </div>

                                    <!-- Schedule Days -->
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        @foreach($subscription->schedules as $schedule)
                                            <span class="px-2 py-1 bg-eco-100 text-eco-700 text-xs font-medium rounded-lg">
                                                {{ substr(ucfirst($schedule->day_of_week), 0, 3) }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Price -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div>
                                            <p class="text-sm text-gray-500">Monthly</p>
                                            <p class="text-xl font-bold text-gray-900">${{ number_format($subscription->subscriptionPlan->monthly_price, 2) }}</p>
                                        </div>
                                        <a href="{{ route('subscriptions.show', $subscription) }}" 
                                           class="inline-flex items-center text-eco-600 hover:text-eco-700 font-medium text-sm">
                                            Manage
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>

                                    <!-- Next Billing -->
                                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500">Next billing</span>
                                            <span class="font-medium text-gray-900">{{ $subscription->next_billing_date->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </x-card>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Paused Subscriptions -->
                @if($pausedSubscriptions->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Paused Subscriptions</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($pausedSubscriptions as $subscription)
                                <x-card class="relative overflow-hidden opacity-75">
                                    <div class="absolute top-4 right-4">
                                        <x-badge color="yellow">Paused</x-badge>
                                    </div>

                                    <div class="flex items-start space-x-4 mb-4">
                                        <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 bg-gray-100">
                                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 truncate">{{ $subscription->subscriptionPlan->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $subscription->subscriptionPlan->serviceType->name }}</p>
                                        </div>
                                    </div>

                                    <div class="p-3 bg-yellow-50 rounded-lg mb-4">
                                        <p class="text-sm text-yellow-800">
                                            <span class="font-medium">Paused until:</span> 
                                            {{ $subscription->paused_until->format('M d, Y') }}
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <p class="text-lg font-bold text-gray-900">${{ number_format($subscription->subscriptionPlan->monthly_price, 2) }}/mo</p>
                                        <a href="{{ route('subscriptions. show', $subscription) }}" 
                                           class="inline-flex items-center text-eco-600 hover:text-eco-700 font-medium text-sm">
                                            Resume
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </x-card>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Cancelled/Expired Subscriptions -->
                @if($cancelledSubscriptions->count() > 0)
                    <div>
                        <h2 class="text-lg font-semibold text-gray-500 mb-4">Past Subscriptions</h2>
                        <div class="space-y-3">
                            @foreach($cancelledSubscriptions as $subscription)
                                <x-card class="opacity-60">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-gray-100">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="font-medium text-gray-700">{{ $subscription->subscriptionPlan->name }}</h3>
                                                <p class="text-sm text-gray-400">
                                                    {{ ucfirst($subscription->status) }} on {{ $subscription->end_date? ->format('M d, Y') ??  $subscription->updated_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <x-badge color="gray">{{ ucfirst($subscription->status) }}</x-badge>
                                    </div>
                                </x-card>
                            @endforeach
                        </div>
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <x-card>
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-eco-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No subscriptions yet</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">
                            Subscribe to a plan and enjoy automatic recurring waste collection at your doorstep.
                        </p>
                        <a href="{{ route('subscriptions.create') }}">
                            <x-button size="lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Browse Subscription Plans
                            </x-button>
                        </a>
                    </div>
                </x-card>

                <!-- Benefits Section -->
                <div class="mt-8 grid grid-cols-1 md: grid-cols-3 gap-6">
                    <x-card class="text-center">
                        <div class="w-12 h-12 bg-eco-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Save Money</h3>
                        <p class="text-sm text-gray-500">Get up to 20% discount with subscription plans compared to one-time pickups. </p>
                    </x-card>

                    <x-card class="text-center">
                        <div class="w-12 h-12 bg-eco-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Never Miss a Pickup</h3>
                        <p class="text-sm text-gray-500">Automatic scheduling ensures your waste is collected on time, every time.</p>
                    </x-card>

                    <x-card class="text-center">
                        <div class="w-12 h-12 bg-eco-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Flexible Management</h3>
                        <p class="text-sm text-gray-500">Pause, modify, or cancel your subscription anytime with no hidden fees.</p>
                    </x-card>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>