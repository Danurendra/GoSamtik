<x-app-layout>
<x-slot name="header">
    <div class="flex items-center space-x-4">
        <a href="{{ route('complaints.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Submit a Support Ticket</h1>
            <p class="text-gray-500 mt-1">Tell us about your issue and we'll help resolve it</p>
        </div>
    </div>
</x-slot>

<div class="py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg: px-8">
        <form method="POST" action="{{ route('complaints.store') }}">
            @csrf

            <x-card class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Issue Details</h2>

                <div class="space-y-6">
                    <!-- Related Collection (Optional) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Related Collection (Optional)</label>
                        <select name="collection_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                            <option value="">No specific collection</option>
                            @foreach($collections as $collection)
                                <option value="{{ $collection->id }}" {{ $selectedCollectionId == $collection->id ? 'selected' : '' }}>
                                    #{{ str_pad($collection->id, 6, '0', STR_PAD_LEFT) }} - 
                                    {{ $collection->serviceType->name }} - 
                                    {{ $collection->scheduled_date->format('M d, Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                            <option value="">Select a category</option>
                            <option value="missed_collection">Missed Collection</option>
                            <option value="damaged_property">Damaged Property</option>
                            <option value="driver_behavior">Driver Behavior</option>
                            <option value="billing">Billing Issue</option>
                            <option value="service_quality">Service Quality</option>
                            <option value="other">Other</option>
                        </select>
                        @error('category')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="priority" value="{{ $priority }}" 
                                            class="peer sr-only" {{ $priority === 'medium' ? 'checked' : '' }} required>
                                    <div class="p-3 text-center border-2 border-gray-200 rounded-xl peer-checked:border-eco-500 peer-checked:bg-eco-50 hover:border-eco-300 transition-all">
                                        <span class="text-sm font-medium text-gray-700 peer-checked:text-eco-700">{{ ucfirst($priority) }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('priority')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                placeholder="Brief description of your issue">
                        @error('subject')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" rows="5" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                    placeholder="Please provide as much detail as possible about your issue... ">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-card>

            <!-- Info Box -->
            <div class="p-4 bg-eco-50 rounded-xl mb-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-eco-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-eco-800">
                        <p class="font-medium mb-1">What happens next? </p>
                        <ul class="list-disc list-inside space-y-1 text-eco-700">
                            <li>You'll receive a confirmation with your ticket number</li>
                            <li>Our support team will review your issue within 24 hours</li>
                            <li>You'll be notified of any updates via email</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('complaints.index') }}">
                    <x-button type="button" variant="secondary">Cancel</x-button>
                </a>
                <x-button type="submit">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Submit Ticket
                </x-button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>