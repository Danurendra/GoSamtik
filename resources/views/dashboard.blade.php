<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-500 mt-1">Here's what's happening with your waste collection. </p>
            </div>
            <a href="{{ route('collections.create') }}">
                <x-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Schedule Pickup
                </x-button>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md: grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-stat-card 
                    title="Total Collections" 
                    : value="$stats['total_collections']"
                    trend="positive"
                    trend-value="+12%"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>

                <x-stat-card 
                    title="Active Subscriptions" 
                    :value="$stats['active_subscriptions']"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-. 581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>

                <x-stat-card 
                    title="Pending Pickups" 
                    :value="$stats['pending_collections']"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>

                <x-stat-card 
                    title="Total Spent" 
                    : value="'$' . number_format($stats['total_spent'], 2)"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 . 895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-. 402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Upcoming Collections -->
                <div class="lg:col-span-2">
                    <x-card>
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-900">Upcoming Collections</h2>
                            <a href="{{ route('collections.index') }}" class="text-sm text-eco-600 hover: text-eco-700 font-medium">
                                View All →
                            </a>
                        </div>

                        @if($upcomingCollections->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingCollections as $collection)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-eco-50 transition-colors">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: {{ $collection->serviceType->color }}20">
                                                <svg class="w-6 h-6" style="color: {{ $collection->serviceType->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $collection->serviceType->name }}</p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $collection->scheduled_date->format('D, M d') }} • {{ $collection->time_window }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <x-badge : color="$collection->status_color">
                                                {{ ucfirst($collection->status) }}
                                            </x-badge>
                                            <a href="{{ route('collections.show', $collection) }}" class="p-2 text-gray-400 hover:text-eco-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No upcoming collections</h3>
                                <p class="text-gray-500 mb-4">Schedule a pickup to get started</p>
                                <a href="{{ route('collections.create') }}">
                                    <x-button variant="outline" size="sm">Schedule Now</x-button>
                                </a>
                            </div>
                        @endif
                    </x-card>
                </div>

                <!-- Active Subscriptions -->
                <div>
                    <x-card>
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-900">My Subscriptions</h2>
                            <a href="{{ route('subscriptions.create') }}" class="text-sm text-eco-600 hover: text-eco-700 font-medium">
                                + Add
                            </a>
                        </div>

                        @if($activeSubscriptions->count() > 0)
                            <div class="space-y-4">
                                @foreach($activeSubscriptions as $subscription)
                                    <a href="{{ route('subscriptions.show', $subscription) }}" class="block p-4 border border-gray-100 rounded-xl hover:border-eco-200 hover:shadow-sm transition-all">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900">{{ $subscription->subscriptionPlan->name }}</span>
                                            <x-badge color="green">Active</x-badge>
                                        </div>
                                        <p class="text-sm text-gray-500 mb-2">
                                            {{ $subscription->subscriptionPlan->serviceType->name }}
                                        </p>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $subscription->subscriptionPlan->frequency_label }}
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                                            <span class="text-sm text-gray-500">Next billing</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $subscription->next_billing_date->format('M d, Y') }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 mb-4">No active subscriptions</p>
                                <a href="{{ route('subscriptions.create') }}">
                                    <x-button variant="outline" size="sm">Browse Plans</x-button>
                                </a>
                            </div>
                        @endif
                    </x-card>

                    <!-- Quick Actions -->
                    <x-card class="mt-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('collections.create') }}" class="flex items-center p-3 rounded-lg hover:bg-eco-50 transition-colors group">
                                <div class="w-10 h-10 bg-eco-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-eco-200 transition-colors">
                                    <svg class="w-5 h-5 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Schedule One-time Pickup</span>
                            </a>
                            <a href="{{ route('complaints.create') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-gray-200 transition-colors">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18. 364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">Report an Issue</span>
                            </a>
                            <a href="{{ route('billing.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-gray-200 transition-colors">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">View Invoices</span>
                            </a>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Recent Collections -->
            @if($recentCollections->count() > 0)
                <x-card class="mt-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Collections</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-sm text-gray-500 border-b border-gray-100">
                                    <th class="pb-3 font-medium">Service</th>
                                    <th class="pb-3 font-medium">Date</th>
                                    <th class="pb-3 font-medium">Status</th>
                                    <th class="pb-3 font-medium">Rating</th>
                                    <th class="pb-3 font-medium text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($recentCollections as $collection)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3" style="background-color: {{ $collection->serviceType->color }}20">
                                                    <svg class="w-4 h-4" style="color: {{ $collection->serviceType->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                    </svg>
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $collection->serviceType->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 text-gray-600">{{ $collection->completed_at->format('M d, Y') }}</td>
                                        <td class="py-4">
                                            <x-badge color="green">Completed</x-badge>
                                        </td>
                                        <td class="py-4">
                                            @if($collection->rating)
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $collection->rating->overall_rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c. 969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-. 69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            @elseif($collection->canBeRated())
                                                <button onclick="openRatingModal({{ $collection->id }})" class="text-sm text-eco-600 hover: text-eco-700 font-medium">
                                                    Rate Now
                                                </button>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right font-medium text-gray-900">${{ number_format($collection->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>