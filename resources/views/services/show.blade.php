<x-app-layout>
    <!-- Hero Section -->
    <div class="relative py-16" style="background:  linear-gradient(135deg, {{ $serviceType->color }}15 0%, {{ $serviceType->color }}05 100%)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('services.index') }}" class="hover:text-eco-600">Services</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span style="color: {{ $serviceType->color }}">{{ $serviceType->name }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-6"
                         style="background-color: {{ $serviceType->color }}20">
                        <svg class="w-10 h-10" style="color: {{ $serviceType->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $serviceType->name }}</h1>
                    <p class="text-xl text-gray-600 mb-6">{{ $serviceType->description }}</p>
                    
                    <div class="flex items-baseline mb-8">
                        <span class="text-sm text-gray-500 mr-2">Starting from</span>
                        <span class="text-4xl font-bold" style="color: {{ $serviceType->color }}">${{ number_format($serviceType->base_price, 2) }}</span>
                        <span class="text-gray-500 ml-2">/ pickup</span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('collections.create') }}?service={{ $serviceType->id }}">
                            <x-button size="lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Schedule One-Time Pickup
                            </x-button>
                        </a>
                        <a href="{{ route('subscriptions.create') }}?service={{ $serviceType->id }}">
                            <x-button variant="outline" size="lg">
                                Subscribe & Save
                            </x-button>
                        </a>
                    </div>
                </div>

                <!-- Requirements Card -->
                <div>
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Collection Requirements</h3>
                        
                        @if($serviceType->requirements && count($serviceType->requirements) > 0)
                            <ul class="space-y-3">
                                @foreach($serviceType->requirements as $requirement)
                                    <li class="flex items-start space-x-3">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                             style="background-color: {{ $serviceType->color }}20">
                                            <svg class="w-4 h-4" style="color: {{ $serviceType->color }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-600">{{ $requirement }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500">No special requirements for this service.</p>
                        @endif

                        <hr class="my-6 border-gray-100">

                        <h4 class="font-medium text-gray-900 mb-3">What We Accept</h4>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $acceptedItems = match($serviceType->slug) {
                                    'general-waste' => ['Household trash', 'Non-recyclables', 'Food packaging'],
                                    'recyclables' => ['Paper', 'Cardboard', 'Plastic bottles', 'Metal cans', 'Glass'],
                                    'organic-waste' => ['Food scraps', 'Yard waste', 'Compostable items'],
                                    'hazardous-waste' => ['Batteries', 'Paint', 'Chemicals', 'Electronics'],
                                    'bulk-items' => ['Furniture', 'Appliances', 'Mattresses', 'Large items'],
                                    default => ['Various items'],
                                };
                            @endphp
                            @foreach($acceptedItems as $item)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full">{{ $item }}</span>
                            @endforeach
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Plans Section -->
    @if($serviceType->subscriptionPlans->count() > 0)
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm: px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Subscription Plans</h2>
                    <p class="text-gray-500 max-w-2xl mx-auto">Save money with our subscription plans.  Choose the frequency that works best for you.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($serviceType->subscriptionPlans->count(), 3) }} gap-6">
                    @foreach($serviceType->subscriptionPlans as $plan)
                        <x-card hover class="relative {{ $plan->is_popular ? 'border-2 border-eco-500 shadow-eco' : '' }}">
                            @if($plan->is_popular)
                                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                    <span class="px-4 py-1 bg-eco-600 text-white text-xs font-bold rounded-full">
                                        MOST POPULAR
                                    </span>
                                </div>
                            @endif

                            <div class="{{ $plan->is_popular ? 'pt-4' : '' }}">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                                <p class="text-eco-600 font-medium mb-4">{{ $plan->frequency_label }}</p>

                                <div class="mb-6">
                                    <span class="text-4xl font-bold text-gray-900">${{ number_format($plan->monthly_price, 0) }}</span>
                                    <span class="text-gray-500">/month</span>
                                    
                                    @if($plan->discount_percentage > 0)
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 bg-eco-100 text-eco-700 text-sm font-medium rounded-lg">
                                                Save {{ number_format($plan->discount_percentage, 0) }}%
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-500 mb-6">
                                    ${{ number_format($plan->per_pickup_price, 2) }} per pickup × ~{{ $plan->monthly_pickups }} pickups/month
                                </p>

                                @if($plan->features)
                                    <ul class="space-y-2 mb-6">
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

                                <a href="{{ route('subscriptions.create') }}?plan={{ $plan->id }}"
                                   class="block w-full text-center px-6 py-3 rounded-xl font-medium transition-colors
                                          {{ $plan->is_popular ? 'bg-eco-600 text-white hover:bg-eco-700' :  'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Choose Plan
                                </a>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Related Services -->
    @if($relatedServices->count() > 0)
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Other Services You Might Need</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedServices as $service)
                        <a href="{{ route('services.show', $service->slug) }}" class="group">
                            <x-card hover>
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110"
                                         style="background-color: {{ $service->color }}20">
                                        <svg class="w-6 h-6" style="color: {{ $service->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 group-hover:text-eco-600 transition-colors">{{ $service->name }}</h3>
                                        <p class="text-sm text-gray-500">From ${{ number_format($service->base_price, 2) }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-eco-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </x-card>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</x-app-layout>