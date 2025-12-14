<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.drivers. index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add New Driver</h1>
                <p class="text-gray-500 mt-1">Create a new driver account</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.drivers.store') }}">
                @csrf

                <!-- Personal Information -->
                <x-card class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Personal Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                   placeholder="John Doe">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                   placeholder="driver@example.com">
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus: ring-eco-200"
                                   placeholder="+1234567890">
                            @error('phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <input type="password" name="password" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                       placeholder="••••••••">
                                @error('password')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                                <input type="password" name="password_confirmation" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                       placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- License Information -->
                <x-card class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">License Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">License Number *</label>
                            <input type="text" name="license_number" value="{{ old('license_number') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus: ring-eco-200"
                                   placeholder="DL-123456">
                            @error('license_number')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">License Expiry Date *</label>
                            <input type="date" name="license_expiry" value="{{ old('license_expiry') }}" required
                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200">
                            @error('license_expiry')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
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
                                    <option value="">Select type...</option>
                                    <option value="Truck" {{ old('vehicle_type') == 'Truck' ? 'selected' : '' }}>Truck</option>
                                    <option value="Van" {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}>Van</option>
                                    <option value="Pickup" {{ old('vehicle_type') == 'Pickup' ? 'selected' : '' }}>Pickup</option>
                                </select>
                                @error('vehicle_type')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">License Plate *</label>
                                <input type="text" name="vehicle_plate" value="{{ old('vehicle_plate') }}" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                       placeholder="ABC-1234">
                                @error('vehicle_plate')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Capacity</label>
                            <input type="text" name="vehicle_capacity" value="{{ old('vehicle_capacity') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                   placeholder="e.g., 500kg or 2 cubic meters">
                            @error('vehicle_capacity')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-card>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.drivers.index') }}">
                        <x-button type="button" variant="secondary">Cancel</x-button>
                    </a>
                    <x-button type="submit">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Driver
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>