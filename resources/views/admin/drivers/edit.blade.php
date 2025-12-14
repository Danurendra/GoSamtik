<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.drivers. show', $driver) }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Driver</h1>
                <p class="text-gray-500 mt-1">{{ $driver->user->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.drivers.update', $driver) }}">
                @csrf
                @method('PUT')

                <!-- Personal Information -->
                <x-card class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Personal Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', $driver->user->name) }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', $driver->user->email) }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200">
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" value="{{ old('phone', $driver->user->phone) }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200">
                            @error('phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-card>

                <!-- License Information -->
                <x-card class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">License Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">License Number *</label>
                            <input type="text" name="license_number" value="{{ old('license_number', $driver->license_number) }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200">
                            @error('license_number')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">License Expiry Date *</label>
                            <input type="date" name="license_expiry" value="{{ old('license_expiry', $driver->license_expiry->format('Y-m-d')) }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus: border-eco-500 focus: ring-2 focus:ring-eco-200">
                            @error('license_expiry')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            @if($driver->license_expiry->isPast())
                                <p class="text-sm text-red-600 mt-1">⚠️ This license has expired! </p>
                            @elseif($driver->license_expiry->diffInDays(now()) <= 30)
                                <p class="text-sm text-yellow-600 mt-1">⚠️ This license is expiring soon</p>
                            @endif
                        </div>
                    </div>
                </x-card>

                <!-- Vehicle Information -->
                <x-card class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Vehicle Information</h2>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type *</label>
                                <select name="vehicle_type" required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                    <option value="Truck" {{ old('vehicle_type', $driver->vehicle_type) == 'Truck' ? 'selected' : '' }}>Truck</option>
                                    <option value="Van" {{ old('vehicle_type', $driver->vehicle_type) == 'Van' ? 'selected' : '' }}>Van</option>
                                    <option value="Pickup" {{ old('vehicle_type', $driver->vehicle_type) == 'Pickup' ? 'selected' : '' }}>Pickup</option>
                                </select>
                                @error('vehicle_type')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">License Plate *</label>
                                <input type="text" name="vehicle_plate" value="{{ old('vehicle_plate', $driver->vehicle_plate) }}" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus: ring-eco-200">
                                @error('vehicle_plate')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Capacity</label>
                            <input type="text" name="vehicle_capacity" value="{{ old('vehicle_capacity', $driver->vehicle_capacity) }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                   placeholder="e. g., 500kg or 2 cubic meters">
                            @error('vehicle_capacity')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-card>

                <!-- Status -->
                <x-card class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Availability Status</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['available' => 'Available', 'on_route' => 'On Route', 'offline' => 'Offline', 'inactive' => 'Inactive'] as $value => $label)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="availability_status" value="{{ $value }}" 
                                       class="peer sr-only" {{ old('availability_status', $driver->availability_status) == $value ? 'checked' : '' }}>
                                <div class="p-4 text-center border-2 border-gray-200 rounded-xl peer-checked:border-eco-500 peer-checked:bg-eco-50 hover:border-eco-300 transition-all">
                                    <div class="w-3 h-3 rounded-full mx-auto mb-2
                                                {{ $value === 'available' ? 'bg-green-500' : '' }}
                                                {{ $value === 'on_route' ?  'bg-blue-500' : '' }}
                                                {{ $value === 'offline' ? 'bg-gray-400' : '' }}
                                                {{ $value === 'inactive' ? 'bg-red-500' : '' }}"></div>
                                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('availability_status')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </x-card>

                <!-- Actions -->
                <div class="flex justify-between">
                    <button type="button" onclick="document.getElementById('deleteModal').classList.remove('hidden')"
                            class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                        Delete Driver
                    </button>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.drivers.show', $driver) }}">
                            <x-button type="button" variant="secondary">Cancel</x-button>
                        </a>
                        <x-button type="submit">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Changes
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h. 01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Delete Driver</h3>
            </div>
            <p class="text-gray-600 mb-4">Are you sure you want to delete <strong>{{ $driver->user->name }}</strong>?  This action cannot be undone.</p>
            <div class="flex space-x-3">
                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <form method="POST" action="{{ route('admin.drivers.destroy', $driver) }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">
                        Delete Driver
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>