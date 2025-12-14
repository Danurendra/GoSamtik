<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
            <p class="text-gray-500 mt-1">Manage all registered customers</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-stat-card title="Total Customers" : value="$stats['total']">
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-. 656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>

                <x-stat-card title="Active Customers" :value="$stats['active']">
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>

                <x-stat-card title="New This Month" :value="$stats['new_this_month']" trend="positive" trend-value="+{{ $stats['new_this_month'] }}">
                    <x-slot name="icon">
                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </x-slot>
                </x-stat-card>
            </div>

            <!-- Filters -->
            <x-card class="mb-6">
                <form method="GET" action="{{ route('admin.customers') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[250px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by name, email, or phone..."
                               class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                    </div>
                    <div class="w-40">
                        <select name="status" class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' :  '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' :  '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
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

            <!-- Customers Table -->
            <x-card>
                @if($customers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-sm text-gray-500 border-b border-gray-100">
                                    <th class="pb-3 font-medium">Customer</th>
                                    <th class="pb-3 font-medium">Contact</th>
                                    <th class="pb-3 font-medium">Collections</th>
                                    <th class="pb-3 font-medium">Subscriptions</th>
                                    <th class="pb-3 font-medium">Status</th>
                                    <th class="pb-3 font-medium">Joined</th>
                                    <th class="pb-3 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($customers as $customer)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center">
                                                    <span class="text-eco-700 font-medium">{{ substr($customer->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <p class="text-sm text-gray-900">{{ $customer->email }}</p>
                                            <p class="text-xs text-gray-500">{{ $customer->phone ??  'No phone' }}</p>
                                        </td>
                                        <td class="py-4">
                                            <span class="text-sm text-gray-900">{{ $customer->collections_count }}</span>
                                        </td>
                                        <td class="py-4">
                                            <span class="text-sm text-gray-900">{{ $customer->subscriptions_count }}</span>
                                        </td>
                                        <td class="py-4">
                                            @if($customer->status === 'active')
                                                <x-badge color="green">Active</x-badge>
                                            @elseif($customer->status === 'inactive')
                                                <x-badge color="gray">Inactive</x-badge>
                                            @else
                                                <x-badge color="red">Suspended</x-badge>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            <span class="text-sm text-gray-500">{{ $customer->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td class="py-4 text-right">
                                            <a href="{{ route('admin.customers.show', $customer) }}" 
                                               class="text-eco-600 hover:text-eco-700 font-medium text-sm">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $customers->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No customers found</h3>
                        <p class="text-gray-500">Try adjusting your search criteria. </p>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>