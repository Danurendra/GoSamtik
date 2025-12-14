<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('collections.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Schedule a Pickup</h1>
                <p class="text-gray-500 mt-1">Request a one-time waste collection</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg: px-8">
            <form method="POST" action="{{ route('collections.store') }}" x-data="collectionForm()">
                @csrf

                <!-- Step Indicator -->
                <div class="flex items-center justify-center mb-8">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full" : class="step >= 1 ? 'bg-eco-600 text-white' : 'bg-gray-200 text-gray-600'">
                            <span class="font-semibold">1</span>
                        </div>
                        <span class="ml-2 font-medium" :class="step >= 1 ? 'text-eco-600' : 'text-gray-400'">Service</span>
                    </div>
                    <div class="w-16 h-1 mx-4" :class="step >= 2 ? 'bg-eco-600' : 'bg-gray-200'"></div>
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full" :class="step >= 2 ? 'bg-eco-600 text-white' : 'bg-gray-200 text-gray-600'">
                            <span class="font-semibold">2</span>
                        </div>
                        <span class="ml-2 font-medium" :class="step >= 2 ? 'text-eco-600' : 'text-gray-400'">Schedule</span>
                    </div>
                    <div class="w-16 h-1 mx-4" :class="step >= 3 ? 'bg-eco-600' : 'bg-gray-200'"></div>
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full" : class="step >= 3 ?  'bg-eco-600 text-white' : 'bg-gray-200 text-gray-600'">
                            <span class="font-semibold">3</span>
                        </div>
                        <span class="ml-2 font-medium" :class="step >= 3 ? 'text-eco-600' : 'text-gray-400'">Confirm</span>
                    </div>
                </div>

                <!-- Step 1: Select Service Type -->
                <div x-show="step === 1" x-transition>
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Select Waste Type</h2>
                        
                        <div class="grid grid-cols-1 md: grid-cols-2 gap-4">
                            @foreach($serviceTypes as $type)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="service_type_id" value="{{ $type->id }}" 
                                           x-model="serviceTypeId" 
                                           @change="selectServiceType({{ $type->toJson() }})"
                                           class="peer sr-only" required>
                                    <div class="p-4 border-2 border-gray-200 rounded-xl peer-checked:border-eco-500 peer-checked:bg-eco-50 hover:border-eco-300 transition-all">
                                        <div class="flex items-start space-x-4">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: {{ $type->color }}20">
                                                <svg class="w-6 h-6" style="color: {{ $type->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900">{{ $type->name }}</h3>
                                                <p class="text-sm text-gray-500 mt-1">{{ $type->description }}</p>
                                                <p class="text-lg font-bold text-eco-600 mt-2">${{ number_format($type->base_price, 2) }}</p>
                                            </div>
                                        </div>
                                        <div class="absolute top-4 right-4 hidden peer-checked:block">
                                            <svg class="w-6 h-6 text-eco-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @error('service_type_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror

                        <div class="flex justify-end mt-6">
                            <x-button type="button" @click="nextStep()" :disabled="!serviceTypeId">
                                Continue
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </x-button>
                        </div>
                    </x-card>
                </div>

                <!-- Step 2: Schedule -->
                <div x-show="step === 2" x-transition>
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Schedule Your Pickup</h2>

                        <div class="space-y-6">
                            <!-- Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Date</label>
                                <input type="date" name="scheduled_date" x-model="scheduledDate"
                                       min="{{ now()->addDay()->format('Y-m-d') }}"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                       required>
                                @error('scheduled_date')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Time Window -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">From Time</label>
                                    <select name="scheduled_time_start" x-model="timeStart"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200" required>
                                        <option value="">Select time</option>
                                        <option value="06:00">6:00 AM</option>
                                        <option value="07:00">7:00 AM</option>
                                        <option value="08:00">8:00 AM</option>
                                        <option value="09:00">9:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="13:00">1:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="15:00">3:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">To Time</label>
                                    <select name="scheduled_time_end" x-model="timeEnd"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200" required>
                                        <option value="">Select time</option>
                                        <option value="08:00">8:00 AM</option>
                                        <option value="09:00">9:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="13:00">1:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="15:00">3:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                        <option value="17:00">5:00 PM</option>
                                        <option value="18:00">6:00 PM</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Address -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Address</label>
                                <textarea name="service_address" x-model="serviceAddress" rows="3"
                                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus: border-eco-500 focus: ring-2 focus:ring-eco-200"
                                          placeholder="Enter full address including street, city, and postal code"
                                          required>{{ old('service_address', auth()->user()->address) }}</textarea>
                                @error('service_address')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Special Instructions (Optional)</label>
                                <textarea name="notes" x-model="notes" rows="2"
                                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                          placeholder="Gate code, specific location, etc. "></textarea>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <x-button type="button" variant="secondary" @click="prevStep()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </x-button>
                            <x-button type="button" @click="nextStep()">
                                Continue
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </x-button>
                        </div>
                    </x-card>
                </div>

                <!-- Step 3: Confirm -->
                <div x-show="step === 3" x-transition>
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Confirm Your Booking</h2>

                        <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Service Type</span>
                                <span class="font-semibold text-gray-900" x-text="selectedServiceType?. name || '-'"></span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Pickup Date</span>
                                <span class="font-semibold text-gray-900" x-text="formatDate(scheduledDate)"></span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Time Window</span>
                                <span class="font-semibold text-gray-900" x-text="formatTimeWindow()"></span>
                            </div>
                            <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Address</span>
                                <span class="font-semibold text-gray-900 text-right max-w-xs" x-text="serviceAddress"></span>
                            </div>
                            <div x-show="notes" class="flex justify-between items-start pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Notes</span>
                                <span class="text-gray-900 text-right max-w-xs" x-text="notes"></span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                                <span class="text-2xl font-bold text-eco-600" x-text="'$' + (selectedServiceType?.base_price || 0).toFixed(2)"></span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-eco-50 rounded-xl">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-eco-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-eco-800">
                                    By proceeding, you agree to our terms of service.  You will be redirected to complete payment after confirmation.
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <x-button type="button" variant="secondary" @click="prevStep()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </x-button>
                            <x-button type="submit">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Confirm & Proceed to Payment
                            </x-button>
                        </div>
                    </x-card>
                </div>
            </form>
        </div>
    </div>

    <script>
        function collectionForm() {
            return {
                step: 1,
                serviceTypeId: '',
                selectedServiceType: null,
                scheduledDate: '',
                timeStart: '08:00',
                timeEnd:  '12:00',
                serviceAddress:  '{{ old("service_address", auth()->user()->address ??  "") }}',
                notes: '',

                selectServiceType(type) {
                    this.selectedServiceType = type;
                },

                nextStep() {
                    if (this.step === 1 && !this.serviceTypeId) return;
                    if (this.step === 2 && (! this.scheduledDate || !this.timeStart || !this.timeEnd || !this.serviceAddress)) return;
                    this.step++;
                },

                prevStep() {
                    this.step--;
                },

                formatDate(date) {
                    if (!date) return '-';
                    return new Date(date).toLocaleDateString('en-US', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day:  'numeric' 
                    });
                },

                formatTimeWindow() {
                    const formatTime = (time) => {
                        const [hours, minutes] = time.split(':');
                        const h = parseInt(hours);
                        const ampm = h >= 12 ?  'PM' : 'AM';
                        const h12 = h % 12 || 12;
                        return `${h12}:${minutes} ${ampm}`;
                    };
                    return `${formatTime(this.timeStart)} - ${formatTime(this. timeEnd)}`;
                }
            }
        }
    </script>
</x-app-layout>