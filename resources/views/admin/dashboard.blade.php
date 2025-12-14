<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-500 mt-1">Welcome back!   Here's an overview of your operations. </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Today's Overview -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Today's Overview - {{ now()->format('l, M d, Y') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-card class="bg-gradient-to-br from-eco-500 to-eco-600 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-eco-100 text-sm">Collections Today</p>
                                <p class="text-3xl font-bold mt-1">{{ $todayStats['collections_today'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </x-card>

                    <x-card class="bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm">Completed</p>
                                <p class="text-3xl font-bold mt-1">{{ $todayStats['completed_today'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </x-card>

                    <x-card class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm">Pending</p>
                                <p class="text-3xl font-bold mt-1">{{ $todayStats['pending_today'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <x-stat-card title="Total Customers" : value="$stats['total_customers']">
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-. 656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>

                <x-stat-card title="Active Drivers" :value="$stats['total_drivers']">
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>

                <x-stat-card title="Active Subscriptions" :value="$stats['active_subscriptions']">
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>

                <x-stat-card title="Open Tickets" :value="$stats['open_complaints']">
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18. 364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>
            </div>

            <!-- Revenue & Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Revenue Card -->
                <x-card>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">This Month</p>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-3xl font-bold text-gray-900">${{ number_format($revenue['this_month'], 2) }}</span>
                                @if($revenue['growth'] > 0)
                                    <span class="text-sm text-green-600 font-medium">+{{ $revenue['growth'] }}%</span>
                                @elseif($revenue['growth'] < 0)
                                    <span class="text-sm text-red-600 font-medium">{{ $revenue['growth'] }}%</span>
                                @endif
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Last Month</span>
                                <span class="text-gray-900">${{ number_format($revenue['last_month'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-2">
                                <span class="text-gray-500">All Time</span>
                                <span class="font-medium text-gray-900">${{ number_format($revenue['total'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Available Drivers -->
                <x-card>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Available Drivers</h3>
                        <span class="text-sm text-eco-600 font-medium">{{ $availableDrivers->count() }} online</span>
                    </div>
                    
                    @if($availableDrivers->count() > 0)
                        <div class="space-y-3">
                            @foreach($availableDrivers->take(5) as $driver)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center">
                                            <span class="text-eco-700 font-medium">{{ substr($driver->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 text-sm">{{ $driver->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $driver->vehicle_type }} • {{ $driver->vehicle_plate }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        <span class="text-xs text-gray-500">Available</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No drivers available</p>
                    @endif
                </x-card>

                <!-- Recent Complaints -->
                <x-card>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Open Tickets</h3>
                        <a href="#" class="text-sm text-eco-600 font-medium">View All →</a>
                    </div>
                    
                    @if($recentComplaints->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentComplaints as $complaint)
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-mono text-gray-500">{{ $complaint->ticket_number }}</span>
                                        <x-badge : color="$complaint->priority_color" class="text-xs">
                                            {{ ucfirst($complaint->priority) }}
                                        </x-badge>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $complaint->subject }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $complaint->user->name }} • {{ $complaint->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm">No open tickets</p>
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- Today's Collections -->
            <x-card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Today's Collections</h3>
                    <a href="#" class="text-sm text-eco-600 font-medium">View All →</a>
                </div>

                @if($todayCollections->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-sm text-gray-500 border-b border-gray-100">
                                    <th class="pb-3 font-medium">Customer</th>
                                    <th class="pb-3 font-medium">Service</th>
                                    <th class="pb-3 font-medium">Time</th>
                                    <th class="pb-3 font-medium">Driver</th>
                                    <th class="pb-3 font-medium">Status</th>
                                    <th class="pb-3 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($todayCollections as $collection)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-600 font-medium text-sm">{{ substr($collection->user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900 text-sm">{{ $collection->user->name }}</p>
                                                    <p class="text-xs text-gray-500 truncate max-w-[150px]">{{ $collection->service_address }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <span class="text-sm text-gray-900">{{ $collection->serviceType->name }}</span>
                                        </td>
                                        <td class="py-4">
                                            <span class="text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($collection->scheduled_time_start)->format('g:i A') }}
                                            </span>
                                        </td>
                                        <td class="py-4">
                                            @if($collection->driver)
                                                <span class="text-sm text-gray-900">{{ $collection->driver->user->name }}</span>
                                            @else
                                                <span class="text-sm text-yellow-600">Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            <x-badge :color="$collection->status_color">
                                                {{ ucfirst($collection->status) }}
                                            </x-badge>
                                        </td>
                                        <td class="py-4 text-right">
                                            <button class="text-eco-600 hover:text-eco-700 text-sm font-medium">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500">No collections scheduled for today</p>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>