<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Collections Management</h1>
                <p class="text-gray-500 mt-1">Manage all waste collection requests</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <x-card class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    <p class="text-sm text-gray-500">Total</p>
                </x-card>
                <x-card class="text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    <p class="text-sm text-gray-500">Pending</p>
                </x-card>
                <x-card class="text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['in_progress'] }}</p>
                    <p class="text-sm text-gray-500">In Progress</p>
                </x-card>
                <x-card class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['completed_today'] }}</p>
                    <p class="text-sm text-gray-500">Completed Today</p>
                </x-card>
                <x-card class="text-center">
                    <p class="text-2xl font-bold text-red-600">{{ $stats['unassigned'] }}</p>
                    <p class="text-sm text-gray-500">Unassigned</p>
                </x-card>
            </div>

            <!-- Filters -->
            <x-card class="mb-6">
                <form method="GET" action="{{ route('admin.collections') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md: grid-cols-2 lg:grid-cols-6 gap-4">
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Customer name, email, or address..."
                                   class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' :  '' }}>In Progress</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <!-- Service Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                            <select name="service_type" class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                <option value="">All Services</option>
                                @foreach($serviceTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('service_type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Assignment -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assignment</label>
                            <select name="assignment" class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                <option value="">All</option>
                                <option value="assigned" {{ request('assignment') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="unassigned" {{ request('assignment') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                            </select>
                        </div>

                        <!-- Filter Button -->
                        <div class="flex items-end">
                            <x-button type="submit" variant="secondary" class="w-full">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-. 293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-. 293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filter
                            </x-button>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="flex flex-wrap gap-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">From: </label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                   class="rounded-lg border-gray-200 text-sm focus:border-eco-500 focus:ring-eco-200">
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">To:</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                   class="rounded-lg border-gray-200 text-sm focus:border-eco-500 focus:ring-eco-200">
                        </div>
                        @if(request()->hasAny(['search', 'status', 'service_type', 'assignment', 'date_from', 'date_to']))
                            <a href="{{ route('admin.collections') }}" class="text-sm text-gray-500 hover:text-gray-700">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </form>
            </x-card>

            <!-- Collections Table -->
            <x-card>
                @if($collections->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-sm text-gray-500 border-b border-gray-100">
                                    <th class="pb-3 font-medium">ID</th>
                                    <th class="pb-3 font-medium">Customer</th>
                                    <th class="pb-3 font-medium">Service</th>
                                    <th class="pb-3 font-medium">Scheduled</th>
                                    <th class="pb-3 font-medium">Driver</th>
                                    <th class="pb-3 font-medium">Status</th>
                                    <th class="pb-3 font-medium text-right">Amount</th>
                                    <th class="pb-3 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($collections as $collection)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4">
                                            <span class="text-sm font-mono text-gray-500">#{{ str_pad($collection->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td class="py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-600 font-medium text-sm">{{ substr($collection->user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900 text-sm">{{ $collection->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $collection->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $collection->serviceType->color }}"></div>
                                                <span class="text-sm text-gray-900">{{ $collection->serviceType->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <p class="text-sm text-gray-900">{{ $collection->scheduled_date->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($collection->scheduled_time_start)->format('g:i A') }}
                                            </p>
                                        </td>
                                        <td class="py-4">
                                            @if($collection->driver)
                                                <span class="text-sm text-gray-900">{{ $collection->driver->user->name }}</span>
                                            @else
                                                <button type="button" 
                                                        onclick="openAssignModal({{ $collection->id }})"
                                                        class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">
                                                    + Assign
                                                </button>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            <x-badge : color="$collection->status_color">
                                                {{ ucfirst(str_replace('_', ' ', $collection->status)) }}
                                            </x-badge>
                                        </td>
                                        <td class="py-4 text-right">
                                            <span class="font-medium text-gray-900">${{ number_format($collection->total_amount, 2) }}</span>
                                        </td>
                                        <td class="py-4 text-right">
                                            <a href="{{ route('admin.collections. show', $collection) }}" 
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
                        {{ $collections->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No collections found</h3>
                        <p class="text-gray-500">Try adjusting your filters. </p>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    <!-- Assign Driver Modal -->
    <div id="assignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Driver</h3>
            <form id="assignForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Driver</label>
                    <select name="driver_id" required class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                        <option value="">Choose a driver... </option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">
                                {{ $driver->user->name }} - {{ $driver->vehicle_type }} ({{ $driver->vehicle_plate }})
                                @if($driver->availability_status === 'available')
                                    ✓ Available
                                @else
                                    - {{ ucfirst($driver->availability_status) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="closeAssignModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <x-button type="submit" class="flex-1">Assign Driver</x-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAssignModal(collectionId) {
            document.getElementById('assignForm').action = `/admin/collections/${collectionId}/assign`;
            document.getElementById('assignModal').classList.remove('hidden');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
        }
    </script>
</x-app-layout>