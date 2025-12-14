<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.customers') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-500 mt-1">Customer Profile</p>
            </div>
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
                            <p class="text-sm text-gray-500">Total Collections</p>
                        </x-card>
                        <x-card class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $stats['completed_collections'] }}</p>
                            <p class="text-sm text-gray-500">Completed</p>
                        </x-card>
                        <x-card class="text-center">
                            <p class="text-2xl font-bold text-eco-600">{{ $stats['active_subscriptions'] }}</p>
                            <p class="text-sm text-gray-500">Active Subs</p>
                        </x-card>
                        <x-card class="text-center">
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_spent'], 2) }}</p>
                            <p class="text-sm text-gray-500">Total Spent</p>
                        </x-card>
                    </div>

                    <!-- Recent Collections -->
                    <x-card>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Recent Collections</h2>
                            <a href="{{ route('admin.collections') }}? search={{ $user->email }}" class="text-sm text-eco-600 hover: text-eco-700 font-medium">
                                View All →
                            </a>
                        </div>

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
                                                <p class="font-medium text-gray-900 text-sm">{{ $collection->serviceType->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $collection->scheduled_date->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <x-badge : color="$collection->status_color">
                                                {{ ucfirst($collection->status) }}
                                            </x-badge>
                                            <span class="text-sm font-medium text-gray-900">${{ number_format($collection->total_amount, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No collections yet</p>
                        @endif
                    </x-card>

                    <!-- Subscriptions -->
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Subscriptions</h2>

                        @if($subscriptions->count() > 0)
                            <div class="space-y-3">
                                @foreach($subscriptions as $subscription)
                                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $subscription->subscriptionPlan->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $subscription->subscriptionPlan->serviceType->name }} • {{ $subscription->subscriptionPlan->frequency_label }}</p>
                                        </div>
                                        <div class="text-right">
                                            @if($subscription->status === 'active')
                                                <x-badge color="green">Active</x-badge>
                                            @elseif($subscription->status === 'paused')
                                                <x-badge color="yellow">Paused</x-badge>
                                            @else
                                                <x-badge color="gray">{{ ucfirst($subscription->status) }}</x-badge>
                                            @endif
                                            <p class="text-sm text-gray-500 mt-1">${{ number_format($subscription->subscriptionPlan->monthly_price, 2) }}/mo</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No subscriptions</p>
                        @endif
                    </x-card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Info -->
                    <x-card>
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-eco-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-eco-700 font-bold text-3xl">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-gray-500">{{ $user->email }}</p>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Phone</span>
                                <span class="text-gray-900">{{ $user->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Status</span>
                                @if($user->status === 'active')
                                    <x-badge color="green">Active</x-badge>
                                @elseif($user->status === 'inactive')
                                    <x-badge color="gray">Inactive</x-badge>
                                @else
                                    <x-badge color="red">Suspended</x-badge>
                                @endif
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Joined</span>
                                <span class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        @if($user->address)
                            <hr class="my-4 border-gray-100">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Address</p>
                                <p class="text-gray-900">{{ $user->address }}</p>
                            </div>
                        @endif
                    </x-card>

                    <!-- Status Update -->
                    <x-card>
                        <h3 class="font-semibold text-gray-900 mb-4">Update Status</h3>
                        <form method="POST" action="{{ route('admin.customers.status', $user) }}">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <select name="status" class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ $user->status == 'suspended' ?  'selected' : '' }}>Suspended</option>
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