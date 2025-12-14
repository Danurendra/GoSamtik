<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('subscriptions.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Choose a Subscription Plan</h1>
                <p class="text-gray-500 mt-1">Select a plan that fits your needs</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="subscriptionForm()">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Step Indicator -->
            <div class="flex items-center justify-center mb-10">
                <template x-for="(stepName, index) in ['Select Plan', 'Choose Days', 'Set Address', 'Confirm']" :key="index">
                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full transition-colors"
                                 :class="step > index + 1 ? 'bg-eco-600 text-white' : (step === index + 1 ?  'bg-eco-600 text-white' : 'bg-gray-200 text-gray-500')">
                                <template x-if="step > index + 1">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </template>
                                <template x-if="step <= index + 1">
                                    <span class="font-semibold" x-text="index + 1"></span>
                                </template>
                            </div>
                            <span class="text-xs mt-2 font-medium hidden sm:block"
                                  : class="step >= index + 1 ? 'text-eco-600' : 'text-gray-400'"
                                  x-text="stepName"></span>
                        </div>
                        <div x-show="index < 3" class="w-12 sm:w-20 h-1 mx-2"
                             :class="step > index + 1 ? 'bg-eco-600' : 'bg-gray-200'"></div>
                    </div>
                </template>
            </div>

            <form method="POST" action="{{ route('subscriptions.store') }}" @submit="submitting = true">
                @csrf

                <!-- Step 1: Select Plan -->
                <div x-show="step === 1" x-transition: enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    
                    <!-- Service Type Tabs -->
                    <div class="flex flex-wrap justify-center gap-2 mb-8">
                        @foreach($serviceTypes as $index => $type)
                            <button type="button"
                                    @click="selectedServiceType = {{ $index }}"
                                    class="px-5 py-2. 5 rounded-xl font-medium transition-all"
                                    :class="selectedServiceType === {{ $index }} ?  'text-white shadow-eco' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                                    :style="selectedServiceType === {{ $index }} ? 'background-color: {{ $type->color }}' : ''">
                                {{ $type->name }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Plans Grid -->
                    @foreach($serviceTypes as $typeIndex => $type)
                        <div x-show="selectedServiceType === {{ $typeIndex }}" x-transition>
                            @if($type->subscriptionPlans->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($type->subscriptionPlans as $plan)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="subscription_plan_id" value="{{ $plan->id }}"
                                                   x-model="selectedPlanId"
                                                   @change="selectPlan({{ $plan->toJson() }})"
                                                   class="peer sr-only">
                                            
                                            <div class="h-full p-6 bg-white border-2 rounded-2xl transition-all
                                                        peer-checked:border-eco-500 peer-checked:shadow-eco
                                                        hover:border-eco-300 hover:shadow-md
                                                        {{ $plan->is_popular ? 'border-eco-200' : 'border-gray-200' }}">
                                                
                                                <!-- Popular Badge -->
                                                @if($plan->is_popular)
                                                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                                        <span class="px-3 py-1 bg-eco-600 text-white text-xs font-bold rounded-full shadow-eco">
                                                            MOST POPULAR
                                                        </span>
                                                    </div>
                                                @endif

                                                <!-- Selected Check -->
                                                <div class="absolute top-4 right-4 hidden peer-checked:block">
                                                    <div class="w-6 h-6 bg-eco-600 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- Plan Name -->
                                                <h3 class="text-xl font-bold text-gray-900 mb-2 {{ $plan->is_popular ? 'mt-2' : '' }}">
                                                    {{ $plan->name }}
                                                </h3>

                                                <!-- Frequency -->
                                                <p class="text-eco-600 font-medium mb-4">
                                                    {{ $plan->frequency_label }}
                                                </p>

                                                <!-- Price -->
                                                <div class="mb-6">
                                                    <span class="text-4xl font-bold text-gray-900">${{ number_format($plan->monthly_price, 0) }}</span>
                                                    <span class="text-gray-500">/month</span>
                                                    @if($plan->discount_percentage > 0)
                                                        <div class="mt-1">
                                                            <span class="text-sm text-eco-600 font-medium">
                                                                Save {{ number_format($plan->discount_percentage, 0) }}%
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Per Pickup Price -->
                                                <p class="text-sm text-gray-500 mb-4">
                                                    ${{ number_format($plan->per_pickup_price, 2) }} per pickup × ~{{ $plan->monthly_pickups }} pickups/month
                                                </p>

                                                <!-- Features -->
                                                @if($plan->features)
                                                    <ul class="space-y-2">
                                                        @foreach($plan->features as $feature)
                                                            <li class="flex items-center text-sm text-gray-600">
                                                                <svg class="w-4 h-4 mr-2 text-eco-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                {{ $feature }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                                <!-- Description -->
                                                @if($plan->description)
                                                    <p class="text-sm text-gray-500 mt-4">{{ $plan->description }}</p>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <x-card class="text-center py-12">
                                    <p class="text-gray-500">No subscription plans available for this service type.</p>
                                </x-card>
                            @endif
                        </div>
                    @endforeach

                    @error('subscription_plan_id')
                        <p class="text-sm text-red-600 mt-4 text-center">{{ $message }}</p>
                    @enderror

                    <div class="flex justify-end mt-8">
                        <x-button type="button" @click="nextStep()" x-bind:disabled="! selectedPlanId">
                            Continue
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </x-button>
                    </div>
                </div>

                <!-- Step 2: Choose Days -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <x-card class="max-w-2xl mx-auto">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">Select Collection Days</h2>
                        <p class="text-gray-500 mb-6">
                            Choose <span class="font-semibold text-eco-600" x-text="selectedPlan?. frequency_per_week || 0"></span> day(s) for your weekly collection. 
                        </p>

                        <div class="grid grid-cols-7 gap-2 mb-6">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="selected_days[]" value="{{ $day }}"
                                           x-model="selectedDays"
                                           @change="validateDays()"
                                           : disabled="!selectedDays.includes('{{ $day }}') && selectedDays.length >= (selectedPlan?.frequency_per_week || 0)"
                                           class="peer sr-only">
                                    <div class="flex flex-col items-center p-3 rounded-xl border-2 border-gray-200 
                                                peer-checked:border-eco-500 peer-checked:bg-eco-50
                                                peer-disabled:opacity-50 peer-disabled:cursor-not-allowed
                                                hover:border-eco-300 transition-all">
                                        <span class="text-xs text-gray-500 mb-1">{{ substr(ucfirst($day), 0, 3) }}</span>
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                    peer-checked:bg-eco-600 peer-checked:text-white bg-gray-100">
                                            <span class="text-sm font-medium">{{ substr(ucfirst($day), 0, 1) }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="text-center mb-6">
                            <span class="text-sm" : class="selectedDays.length === (selectedPlan?.frequency_per_week || 0) ? 'text-eco-600' : 'text-gray-500'">
                                <span x-text="selectedDays.length"></span> of <span x-text="selectedPlan?.frequency_per_week || 0"></span> days selected
                            </span>
                        </div>

                        @error('selected_days')
                            <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
                        @enderror

                        <!-- Time Window -->
                        <div class="border-t border-gray-100 pt-6">
                            <h3 class="font-medium text-gray-900 mb-4">Preferred Time Window</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">From</label>
                                    <select name="time_start" x-model="timeStart"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                        <option value="06:00">6:00 AM</option>
                                        <option value="07:00">7:00 AM</option>
                                        <option value="08:00" selected>8:00 AM</option>
                                        <option value="09:00">9:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="13:00">1:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                                    <select name="time_end" x-model="timeEnd"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-eco-200">
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00" selected>12:00 PM</option>
                                        <option value="13:00">1:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="15:00">3:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                        <option value="17:00">5:00 PM</option>
                                        <option value="18:00">6:00 PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <x-button type="button" variant="secondary" @click="prevStep()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </x-button>
                            <x-button type="button" @click="nextStep()" 
                                      x-bind:disabled="selectedDays.length !== (selectedPlan?.frequency_per_week || 0)">
                                Continue
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </x-button>
                        </div>
                    </x-card>
                </div>

                <!-- Step 3: Set Address -->
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition: enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <x-card class="max-w-2xl mx-auto">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Service Address</h2>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Address *</label>
                                <textarea name="service_address" x-model="serviceAddress" rows="3"
                                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                          placeholder="Enter your full address including street, city, state, and postal code"
                                          required></textarea>
                                @error('service_address')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Special Instructions (Optional)</label>
                                <textarea name="special_instructions" x-model="specialInstructions" rows="2"
                                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200"
                                          placeholder="Gate code, specific location details, etc. "></textarea>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <x-button type="button" variant="secondary" @click="prevStep()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </x-button>
                            <x-button type="button" @click="nextStep()" x-bind:disabled="!serviceAddress">
                                Continue
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </x-button>
                        </div>
                    </x-card>
                </div>

                <!-- Step 4: Confirm -->
                <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="max-w-2xl mx-auto">
                        <x-card>
                            <h2 class="text-lg font-semibold text-gray-900 mb-6">Confirm Your Subscription</h2>

                            <!-- Summary -->
                            <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                    <span class="text-gray-600">Plan</span>
                                    <span class="font-semibold text-gray-900" x-text="selectedPlan?. name || '-'"></span>
                                </div>
                                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                    <span class="text-gray-600">Frequency</span>
                                    <span class="font-semibold text-gray-900" x-text="selectedPlan?.frequency_label || '-'"></span>
                                </div>
                                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                    <span class="text-gray-600">Collection Days</span>
                                    <span class="font-semibold text-gray-900" x-text="selectedDays.map(d => d. charAt(0).toUpperCase() + d.slice(1, 3)).join(', ') || '-'"></span>
                                </div>
                                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                    <span class="text-gray-600">Time Window</span>
                                    <span class="font-semibold text-gray-900" x-text="formatTimeWindow()"></span>
                                </div>
                                <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                                    <span class="text-gray-600">Address</span>
                                    <span class="font-semibold text-gray-900 text-right max-w-xs" x-text="serviceAddress || '-'"></span>
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <span class="text-lg font-semibold text-gray-900">Monthly Total</span>
                                    <span class="text-2xl font-bold text-eco-600">
                                        $<span x-text="selectedPlan?.monthly_price?. toFixed(2) || '0.00'"></span>/mo
                                    </span>
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="mt-6 p-4 bg-eco-50 rounded-xl">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-eco-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="text-sm text-eco-800">
                                        <p class="font-medium mb-1">What happens next?</p>
                                        <ul class="list-disc list-inside space-y-1 text-eco-700">
                                            <li>Your subscription will start immediately</li>
                                            <li>Collections will be automatically scheduled</li>
                                            <li>You'll be billed monthly on the same date</li>
                                            <li>You can pause or cancel anytime</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <x-button type="button" variant="secondary" @click="prevStep()">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Back
                                </x-button>
                                <x-button type="submit" x-bind:disabled="submitting">
                                    <template x-if="! submitting">
                                        <span class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Confirm Subscription
                                        </span>
                                    </template>
                                    <template x-if="submitting">
                                        <span class="flex items-center">
                                            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Processing...
                                        </span>
                                    </template>
                                </x-button>
                            </div>
                        </x-card>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function subscriptionForm() {
            return {
                step: 1,
                selectedServiceType: 0,
                selectedPlanId: '',
                selectedPlan: null,
                selectedDays: [],
                timeStart:  '08:00',
                timeEnd:  '12:00',
                serviceAddress:  '{{ old("service_address", auth()->user()->address ??  "") }}',
                specialInstructions: '',
                submitting: false,

                selectPlan(plan) {
                    this.selectedPlan = plan;
                    // Reset selected days if frequency changes
                    if (this.selectedDays.length > plan.frequency_per_week) {
                        this.selectedDays = [];
                    }
                },

                validateDays() {
                    // Limit selection to frequency_per_week
                    if (this.selectedDays.length > this.selectedPlan?. frequency_per_week) {
                        this.selectedDays = this.selectedDays.slice(0, this.selectedPlan. frequency_per_week);
                    }
                },

                nextStep() {
                    if (this.step === 1 && !this.selectedPlanId) return;
                    if (this. step === 2 && this.selectedDays.length !== this.selectedPlan?.frequency_per_week) return;
                    if (this.step === 3 && !this.serviceAddress) return;
                    this.step++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                prevStep() {
                    this.step--;
                    window. scrollTo({ top: 0, behavior: 'smooth' });
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