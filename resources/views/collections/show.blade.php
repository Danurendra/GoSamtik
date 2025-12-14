<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('collections.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Collection Details</h1>
                <p class="text-gray-500 mt-1">Order #{{ str_pad($collection->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg: px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Status Card -->
                    <x-card>
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-900">Status</h2>
                            <x-badge : color="$collection->status_color" class="text-sm px-3 py-1">
                                {{ ucfirst(str_replace('_', ' ', $collection->status)) }}
                            </x-badge>
                        </div>

                        <!-- Status Timeline -->
                        <div class="relative">
                            @php
                                $statuses = ['pending', 'confirmed', 'in_progress', 'completed'];
                                $currentIndex = array_search($collection->status, $statuses);
                                if ($collection->status === 'cancelled') $currentIndex = -1;
                            @endphp
                            
                            <div class="flex items-center justify-between">
                                @foreach($statuses as $index => $status)
                                    <div class="flex flex-col items-center flex-1">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                            {{ $index <= $currentIndex ? 'bg-eco-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                            @if($index < $currentIndex)
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <span class="text-sm font-medium">{{ $index + 1 }}</span>
                                            @endif
                                        </div>
                                        <span class="text-xs mt-2 text-center {{ $index <= $currentIndex ? 'text-eco-600 font-medium' : 'text-gray-400' }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </div>
                                    @if($index < count($statuses) - 1)
                                        <div class="flex-1 h-1 mx-2 {{ $index < $currentIndex ? 'bg-eco-600' : 'bg-gray-200' }}"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </x-card>

                    <!-- Collection Info -->
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Collection Information</h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: {{ $collection->serviceType->color }}20">
                                    <svg class="w-6 h-6" style="color: {{ $collection->serviceType->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Service Type</p>
                                    <p class="font-semibold text-gray-900">{{ $collection->serviceType->name }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $collection->serviceType->description }}</p>
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Scheduled Date</p>
                                    <p class="font-semibold text-gray-900">{{ $collection->scheduled_date->format('l, M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Time Window</p>
                                    <p class="font-semibold text-gray-900">{{ $collection->time_window }}</p>
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <div>
                                <p class="text-sm text-gray-500">Pickup Address</p>
                                <p class="font-semibold text-gray-900">{{ $collection->service_address }}</p>
                            </div>

                            @if($collection->notes)
                                <hr class="border-gray-100">
                                <div>
                                    <p class="text-sm text-gray-500">Special Instructions</p>
                                    <p class="text-gray-900">{{ $collection->notes }}</p>
                                </div>
                            @endif

                            @if($collection->isCompleted() && $collection->completed_at)
                                <hr class="border-gray-100">
                                <div>
                                    <p class="text-sm text-gray-500">Completed At</p>
                                    <p class="font-semibold text-gray-900">{{ $collection->completed_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Driver Info (if assigned) -->
                    @if($collection->driver)
                        <x-card>
                            <h2 class="text-lg font-semibold text-gray-900 mb-6">Driver Information</h2>
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-eco-100 rounded-full flex items-center justify-center">
                                    <span class="text-eco-700 font-bold text-xl">{{ substr($collection->driver->user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $collection->driver->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $collection->driver->vehicle_type }} • {{ $collection->driver->vehicle_plate }}</p>
                                    <div class="flex items-center mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $collection->driver->average_rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-. 784.57-1.838-. 197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-. 38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        <span class="text-sm text-gray-500 ml-2">({{ number_format($collection->driver->average_rating, 1) }})</span>
                                    </div>
                                </div>
                                @if($collection->status === 'in_progress')
                                    <a href="#" class="text-eco-600 hover:text-eco-700 font-medium text-sm">
                                        Track Live →
                                    </a>
                                @endif
                            </div>
                        </x-card>
                    @endif

                    <!-- Rating Section -->
                    @if($collection->canBeRated())
                        <x-card x-data="{ rating: 0, comment: '', submitting: false }">
                            <h2 class="text-lg font-semibold text-gray-900 mb-6">Rate This Collection</h2>
                            <form method="POST" action="{{ route('ratings.store', $collection) }}" @submit="submitting = true">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                                        <div class="flex space-x-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" @click="rating = {{ $i }}" class="focus:outline-none">
                                                    <svg class="w-10 h-10 transition-colors" : class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200 hover:text-yellow-200'" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c. 3-.921 1.603-. 921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-. 364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                </button>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="overall_rating" x-model="rating">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Comment (Optional)</label>
                                        <textarea name="comment" x-model="comment" rows="3" 
                                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200"
                                                  placeholder="Share your experience... "></textarea>
                                    </div>
                                    <x-button type="submit" : disabled="rating === 0" x-bind:disabled="rating === 0 || submitting">
                                        <span x-show="! submitting">Submit Rating</span>
                                        <span x-show="submitting">Submitting...</span>
                                    </x-button>
                                </div>
                            </form>
                        </x-card>
                    @elseif($collection->rating)
                        <x-card>
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Rating</h2>
                            <div class="flex items-center space-x-2 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= $collection->rating->overall_rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c. 3-.921 1.603-. 921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-. 364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            @if($collection->rating->comment)
                                <p class="text-gray-600 italic">"{{ $collection->rating->comment }}"</p>
                            @endif
                        </x-card>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Payment Summary -->
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Summary</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Service Fee</span>
                                <span class="text-gray-900">${{ number_format($collection->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Tax</span>
                                <span class="text-gray-900">$0. 00</span>
                            </div>
                            <hr class="border-gray-100">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-900">Total</span>
                                <span class="font-bold text-eco-600 text-lg">${{ number_format($collection->total_amount, 2) }}</span>
                            </div>
                        </div>

                        @if($collection->payment)
                            <div class="mt-4 p-3 rounded-lg {{ $collection->payment->isCompleted() ? 'bg-eco-50' : 'bg-yellow-50' }}">
                                <div class="flex items-center space-x-2">
                                    @if($collection->payment->isCompleted())
                                        <svg class="w-5 h-5 text-eco-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm text-eco-700 font-medium">Payment Completed</span>
                                    @else
                                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8. 257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-. 213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm text-yellow-700 font-medium">Payment Pending</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </x-card>

                    <!-- Actions -->
                    @if($collection->canBeCancelled())
                        <x-card>
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                            <div class="space-y-3">
                                <button onclick="document.getElementById('rescheduleModal').classList.remove('hidden')" 
                                        class="w-full flex items-center justify-center px-4 py-2 border border-eco-600 text-eco-600 rounded-xl hover:bg-eco-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Reschedule
                                </button>
                                <button onclick="document. getElementById('cancelModal').classList.remove('hidden')" 
                                        class="w-full flex items-center justify-center px-4 py-2 border border-red-300 text-red-600 rounded-xl hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel Collection
                                </button>
                            </div>
                        </x-card>
                    @endif

                    <!-- Need Help?  -->
                    <x-card class="bg-gray-50 border-0">
                        <h3 class="font-semibold text-gray-900 mb-2">Need Help?</h3>
                        <p class="text-sm text-gray-600 mb-4">Having issues with this collection? We're here to help. </p>
                        <a href="{{ route('complaints.create', ['collection_id' => $collection->id]) }}" 
                           class="text-eco-600 hover: text-eco-700 font-medium text-sm">
                            Report an Issue →
                        </a>
                    </x-card>
                </div>
            </div>
        </div>
    </div>

    <!-- Reschedule Modal -->
    <div id="rescheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Reschedule Collection</h3>
            <form method="POST" action="{{ route('collections. reschedule', $collection) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Date</label>
                        <input type="date" name="scheduled_date" min="{{ now()->addDay()->format('Y-m-d') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                            <select name="scheduled_time_start" required class="w-full rounded-xl border-gray-200">
                                <option value="08:00">8:00 AM</option>
                                <option value="09:00">9:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="14:00">2:00 PM</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                            <select name="scheduled_time_end" required class="w-full rounded-xl border-gray-200">
                                <option value="12:00">12:00 PM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="17:00">5:00 PM</option>
                                <option value="18:00">6:00 PM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3 mt-6">
                    <button type="button" onclick="document.getElementById('rescheduleModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <x-button type="submit" class="flex-1">Reschedule</x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cancel Collection</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to cancel this collection?  This action cannot be undone.</p>
            <form method="POST" action="{{ route('collections.cancel', $collection) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for cancellation</label>
                    <textarea name="cancellation_reason" rows="3" required
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200"
                              placeholder="Please tell us why you're cancelling... "></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50">
                        Keep Collection
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">
                        Yes, Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>