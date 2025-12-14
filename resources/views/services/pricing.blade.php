<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-eco py-16">
        <div class="max-w-7xl mx-auto px-4 sm: px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Simple, Transparent Pricing</h1>
            <p class="text-xl text-eco-100 max-w-2xl mx-auto">
                No hidden fees.  Choose the service that fits your needs and budget.
            </p>
        </div>
    </div>

    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm: px-6 lg:px-8">
            
            <!-- Service Type Tabs -->
            <div x-data="{ activeTab: '{{ $serviceTypes->first()?->slug }}' }" class="mb-12">
                <div class="flex flex-wrap justify-center gap-2 mb-10">
                    @foreach($serviceTypes as $type)
                        <button @click="activeTab = '{{ $type->slug }}'"
                                class="px-6 py-3 rounded-xl font-medium transition-all"
                                :class="activeTab === '{{ $type->slug }}' ?  'bg-eco-600 text-white shadow-eco' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'">
                            {{ $type->name }}
                        </button>
                    @endforeach
                </div>

                @foreach($serviceTypes as $type)
                    <div x-show="activeTab === '{{ $type->slug }}'" x-transition>
                        <!-- One-Time Pricing -->
                        <x-card class="mb-8">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">One-Time Pickup</h3>
                                    <p class="text-gray-500">Perfect for occasional cleanouts or extra waste</p>
                                </div>
                                <div class="mt-4 md:mt-0 flex items-center space-x-4">
                                    <div class="text-right">
                                        <span class="text-3xl font-bold" style="color: {{ $type->color }}">${{ number_format($type->base_price, 2) }}</span>
                                        <span class="text-gray-500">/ pickup</span>
                                    </div>
                                    <a href="{{ route('collections.create') }}?service={{ $type->id }}">
                                        <x-button>Book Now</x-button>
                                    </a>
                                </div>
                            </div>
                        </x-card>

                        <!-- Subscription Plans -->
                        @if($type->subscriptionPlans->count() > 0)
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Subscription Plans</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($type->subscriptionPlans->count(), 4) }} gap-6">
                                @foreach($type->subscriptionPlans as $plan)
                                    <x-card class="relative {{ $plan->is_popular ? 'border-2 border-eco-500 shadow-eco' : '' }}">
                                        @if($plan->is_popular)
                                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                                <span class="px-4 py-1 bg-eco-600 text-white text-xs font-bold rounded-full uppercase">
                                                    Best Value
                                                </span>
                                            </div>
                                        @endif

                                        <div class="{{ $plan->is_popular ? 'pt-4' : '' }}">
                                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $plan->name }}</h4>
                                            <p class="text-sm text-eco-600 font-medium mb-4">{{ $plan->frequency_label }}</p>

                                            <div class="mb-4">
                                                <span class="text-3xl font-bold text-gray-900">${{ number_format($plan->monthly_price, 0) }}</span>
                                                <span class="text-gray-500">/mo</span>
                                            </div>

                                            <p class="text-sm text-gray-500 mb-4">
                                                ${{ number_format($plan->per_pickup_price, 2) }} per pickup
                                            </p>

                                            @if($plan->discount_percentage > 0)
                                                <div class="mb-4">
                                                    <span class="inline-flex items-center px-2 py-1 bg-eco-100 text-eco-700 text-xs font-medium rounded-lg">
                                                        Save {{ number_format($plan->discount_percentage, 0) }}% vs one-time
                                                    </span>
                                                </div>
                                            @endif

                                            @if($plan->features)
                                                <ul class="space-y-2 mb-6 text-sm">
                                                    @foreach($plan->features as $feature)
                                                        <li class="flex items-center text-gray-600">
                                                            <svg class="w-4 h-4 mr-2 text-eco-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            {{ $feature }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                            <a href="{{ route('subscriptions.create') }}?plan={{ $plan->id }}"
                                               class="block w-full text-center px-4 py-2. 5 rounded-xl font-medium transition-colors
                                                      {{ $plan->is_popular ?  'bg-eco-600 text-white hover:bg-eco-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                                Get Started
                                            </a>
                                        </div>
                                    </x-card>
                                @endforeach
                            </div>
                        @else
                            <x-card class="text-center py-8">
                                <p class="text-gray-500">No subscription plans available for this service yet.</p>
                            </x-card>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- FAQ Section -->
            <div class="mt-20">
                <h2 class="text-2xl font-bold text-gray-900 text-center mb-10">Frequently Asked Questions</h2>
                
                <div class="max-w-3xl mx-auto space-y-4" x-data="{ openFaq: null }">
                    @php
                        $faqs = [
                            [
                                'question' => 'How does billing work for subscriptions?',
                                'answer' => 'Subscriptions are billed monthly on the same date you signed up. You can cancel or pause anytime before your next billing date.'
                            ],
                            [
                                'question' => 'Can I change my subscription plan?',
                                'answer' => 'Yes!  You can upgrade, downgrade, or change your collection days at any time. Changes take effect on your next billing cycle.'
                            ],
                            [
                                'question' => 'What happens if I need to skip a pickup?',
                                'answer' => 'You can pause your subscription for up to 3 months. During the pause period, you won\'t be charged and no collections will be scheduled.'
                            ],
                            [
                                'question' => 'Is there a cancellation fee?',
                                'answer' => 'No!  There are no cancellation fees. You can cancel your subscription at any time and your service will continue until the end of your current billing period.'
                            ],
                            [
                                'question' => 'Do you offer refunds?',
                                'answer' => 'If you\'re not satisfied with our service, contact us within 7 days of your pickup for a full refund.  Subscription refunds are prorated based on unused service.'
                            ],
                        ];
                    @endphp

                    @foreach($faqs as $index => $faq)
                        <x-card class="cursor-pointer" @click="openFaq = openFaq === {{ $index }} ?  null : {{ $index }}">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900">{{ $faq['question'] }}</h3>
                                <svg class="w-5 h-5 text-gray-400 transition-transform" : class="openFaq === {{ $index }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="openFaq === {{ $index }}" x-collapse class="mt-4">
                                <p class="text-gray-600">{{ $faq['answer'] }}</p>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>