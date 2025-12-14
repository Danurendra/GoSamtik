<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.collections') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Collection #{{ str_pad($collection->id, 5, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-gray-500 mt-1">{{ $collection->serviceType->name }} - {{ $collection->scheduled_date->format('M d, Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Status & Quick Actions -->
                    <x-card>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Status</h2>
                            <x-badge :color="$collection->status_color" class="text-sm px-3 py-1">
                                {{ ucfirst(str_replace('_', ' ', $collection->status)) }}
                            </x-badge>
                        </div>

                        <!-- Quick Status Update -->
                        <form method="POST" action="{{ route('admin.collections.show', $collection) }}/status" class="flex items-center space-x-3">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="flex-1 rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                <option value="pending" {{ $collection->status == 'pending' ?  'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $collection->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ $collection->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $collection->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $collection->status == 'cancelled' ?  'selected' : '' }}>Cancelled</option>
                                <option value="missed" {{ $collection->status == 'missed' ? 'selected' : '' }}>Missed</option>
                            </select>
                            <x-button type="submit" variant="secondary" size="sm">Update</x-button>
                        </form>
                    </x-card>

                    <!-- Customer Info -->
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 bg-eco-100 rounded-full flex items-center justify-center">
                                <span class="text-eco-700 font-bold text-xl">{{ substr($collection->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $collection->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $collection->user->email }}</p>
                                @if($collection->user->phone)
                                    <p class="text-sm text-gray-500">{{ $collection->user->phone }}</p>
                                @endif
                                <a href="{{ route('admin.customers.show', $collection->user) }}" class="text-sm text-eco-600 hover: text-eco-700 font-medium mt-2 inline-block">
                                    View Customer Profile →
                                </a>
                            </div>
                        </div>
                    </x-card>

                    <!-- Collection Details -->
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Collection Details</h2>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Service Type</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $collection->serviceType->color }}"></div>
                                        <span class="font-medium text-gray-900">{{ $collection->serviceType->name }}</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Collection Type</p>
                                    <p class="font-medium text-gray-900 mt-1">{{ ucfirst($collection->collection_type) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Scheduled Date</p>
                                    <p class="font-medium text-gray-900 mt-1">{{ $collection->scheduled_date->format('l, M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Time Window</p>
                                    <p class="font-medium text-gray-900 mt-1">
                                        {{ \Carbon\Carbon::parse($collection->scheduled_time_start)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($collection->scheduled_time_end)->format('g:i A') }}
                                    </p>
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <div>
                                <p class="text-sm text-gray-500">Service Address</p>
                                <p class="font-medium text-gray-900 mt-1">{{ $collection->service_address }}</p>
                            </div>

                            @if($collection->notes || $collection->special_instructions)
                                <hr class="border-gray-100">
                                <div>
                                    <p class="text-sm text-gray-500">Special Instructions</p>
                                    <p class="text-gray-900 mt-1">{{ $collection->notes ??  $collection->special_instructions }}</p>
                                </div>
                            @endif

                            @if($collection->completed_at)
                                <hr class="border-gray-100">
                                <div>
                                    <p class="text-sm text-gray-500">Completed At</p>
                                    <p class="font-medium text-gray-900 mt-1">{{ $collection->completed_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            @endif

                            @if($collection->cancellation_reason)
                                <hr class="border-gray-100">
                                <div class="p-3 bg-red-50 rounded-lg">
                                    <p class="text-sm text-red-600 font-medium">Cancellation Reason</p>
                                    <p class="text-red-700 mt-1">{{ $collection->cancellation_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Rating (if exists) -->
                    @if($collection->rating)
                        <x-card>
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Rating</h2>
                            <div class="flex items-center space-x-2 mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= $collection->rating->overall_rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-. 921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                                <span class="text-lg font-semibold text-gray-900 ml-2">{{ $collection->rating->overall_rating }}/5</span>
                            </div>
                            @if($collection->rating->comment)
                                <p class="text-gray-600 italic">"{{ $collection->rating->comment }}"</p>
                            @endif
                        </x-card>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Driver Assignment -->
                    <x-card>
                        <h3 class="font-semibold text-gray-900 mb-4">Driver Assignment</h3>
                        
                        @if($collection->driver)
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-eco-100 rounded-full flex items-center justify-center">
                                    <span class="text-eco-700 font-bold">{{ substr($collection->driver->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $collection->driver->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $collection->driver->vehicle_type }} • {{ $collection->driver->vehicle_plate }}</p>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.collections.assign', $collection) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $collection->driver ? 'Reassign Driver' : 'Assign Driver' }}
                                </label>
                                <select name="driver_id" required class="w-full rounded-xl border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                    <option value="">Select driver...</option>
                                    @foreach($availableDrivers as $driver)
                                        <option value="{{ $driver->id }}" {{ $collection->driver_id == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->user->name }} ({{ $driver->vehicle_plate }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-button type="submit" class="w-full">
                                {{ $collection->driver ? 'Reassign' : 'Assign Driver' }}
                            </x-button>
                        </form>
                    </x-card>

                    <!-- Payment Info -->
                    <x-card>
                        <h3 class="font-semibold text-gray-900 mb-4">Payment</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Amount</span>
                                <span class="font-bold text-gray-900">${{ number_format($collection->total_amount, 2) }}</span>
                            </div>
                            @if($collection->payment)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Status</span>
                                    @if($collection->payment->status === 'completed')
                                        <x-badge color="green">Paid</x-badge>
                                    @else
                                        <x-badge color="yellow">{{ ucfirst($collection->payment->status) }}</x-badge>
                                    @endif
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Method</span>
                                    <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $collection->payment->payment_method)) }}</span>
                                </div>
                                @if($collection->payment->transaction_id)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Transaction</span>
                                        <span class="text-gray-900 text-xs font-mono">{{ $collection->payment->transaction_id }}</span>
                                    </div>
                                @endif
                            @else
                                <div class="p-3 bg-yellow-50 rounded-lg">
                                    <p class="text-sm text-yellow-700">No payment recorded</p>
                                </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Subscription Info (if linked) -->
                    @if($collection->subscription)
                        <x-card>
                            <h3 class="font-semibold text-gray-900 mb-4">Subscription</h3>
                            <p class="text-gray-900 font-medium">{{ $collection->subscription->subscriptionPlan->name }}</p>
                            <p class="text-sm text-gray-500">{{ $collection->subscription->subscriptionPlan->frequency_label }}</p>
                            <x-badge color="{{ $collection->subscription->status === 'active' ? 'green' : 'gray' }}" class="mt-2">
                                {{ ucfirst($collection->subscription->status) }}
                            </x-badge>
                        </x-card>
                    @endif

                    <!-- Timestamps -->
                    <x-card class="bg-gray-50 border-0">
                        <h3 class="font-semibold text-gray-900 mb-3">Activity</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Created</span>
                                <span class="text-gray-900">{{ $collection->created_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Updated</span>
                                <span class="text-gray-900">{{ $collection->updated_at->format('M d, Y g:i A') }}</span>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>