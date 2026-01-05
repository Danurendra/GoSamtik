<x-app-layout>
    <div class="relative overflow-hidden">
        <!-- Hero Section -->
        <div class="bg-gradient-eco py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Our Services
                </h1>
                <p class="text-xl text-eco-100 max-w-2xl mx-auto">
                    Professional waste collection services tailored to your needs.  Choose from a variety of waste types and flexible scheduling options.
                </p>
            </div>
            
            <!-- Decorative Wave -->
            {{-- <div class="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F9FAFB"/>
                </svg>
            </div> --}}
        </div>

        <!-- Services Grid -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md: grid-cols-2 lg: grid-cols-3 gap-8">
                    @foreach($serviceTypes as $service)
                        <x-card hover class="relative overflow-hidden group">
                            <!-- Service Icon -->
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110"
                                 style="background-color: {{ $service->color }}20">
                                <svg class="w-8 h-8" style="color: {{ $service->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($service->slug === 'general-waste')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    @elseif($service->slug === 'recyclables')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    @elseif($service->slug === 'organic-waste')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3. 055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @elseif($service->slug === 'hazardous-waste')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    @elseif($service->slug === 'bulk-items')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    @endif
                                </svg>
                            </div>

                            <!-- Service Name -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $service->name }}</h3>

                            <!-- Description -->
                            <p class="text-gray-500 mb-6">{{ $service->description }}</p>

                            <!-- Price -->
                            <div class="mb-6">
                                <span class="text-sm text-gray-500">Starting from</span>
                                <div class="flex items-baseline">
                                    <span class="text-3xl font-bold" style="color: {{ $service->color }}">${{ number_format($service->base_price, 2) }}</span>
                                    <span class="text-gray-500 ml-2">/ pickup</span>
                                </div>
                            </div>

                            <!-- Requirements -->
                            @if($service->requirements)
                                <div class="mb-6">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Requirements: </p>
                                    <ul class="space-y-1">
                                        @foreach($service->requirements as $requirement)
                                            <li class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-2 text-eco-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $requirement }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Subscription Plans Count -->
                            @if($service->subscriptionPlans->count() > 0)
                                <p class="text-sm text-gray-500 mb-4">
                                    {{ $service->subscriptionPlans->count() }} subscription plan{{ $service->subscriptionPlans->count() > 1 ? 's' : '' }} available
                                </p>
                            @endif

                            <!-- CTA Button -->
                            <a href="{{ route('services.show', $service->slug) }}" 
                               class="inline-flex items-center font-medium transition-colors"
                               style="color: {{ $service->color }}">
                                View Details
                                <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>

                            <!-- Decorative Element -->
                            <div class="absolute -right-8 -bottom-8 w-32 h-32 rounded-full opacity-10"
                                 style="background-color: {{ $service->color }}"></div>
                        </x-card>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
                    <p class="text-gray-500 max-w-2xl mx-auto">Getting started with Gosamtik is easy. Follow these simple steps to schedule your first pickup.</p>
                </div>

                <div class="grid grid-cols-1 md: grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-eco-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-eco-600">1</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Choose Service</h3>
                        <p class="text-sm text-gray-500">Select the type of waste you need collected from our range of services.</p>
                    </div>

                    <!-- Arrow -->
                    <div class="hidden md:flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-eco-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-eco-600">2</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Schedule Pickup</h3>
                        <p class="text-sm text-gray-500">Pick a convenient date and time for your waste collection.</p>
                    </div>

                    <!-- Arrow -->
                    <div class="hidden md:flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-eco-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-eco-600">3</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">We Collect</h3>
                        <p class="text-sm text-gray-500">Our professional team arrives on time to collect your waste.</p>
                    </div>

                    <!-- Arrow -->
                    <div class="hidden md:flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>

                    <!-- Step 4 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-eco-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-eco-600">4</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Track & Relax</h3>
                        <p class="text-sm text-gray-500">Track your collection in real-time and receive confirmation when done.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-eco-600 py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg: px-8 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Ready to Get Started?</h2>
                <p class="text-eco-100 mb-8 text-lg">Join thousands of satisfied customers who trust Gosamtik for their waste management needs.</p>
                <div class="flex flex-col sm: flex-row gap-4 justify-center">
                    <a href="{{ route('collections.create') }}" class="inline-flex items-center justify-center px-8 py-3 bg-white text-eco-600 font-semibold rounded-xl hover:bg-eco-50 transition-colors shadow-lg">
                        Schedule a Pickup
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-white font-semibold rounded-xl hover:bg-eco-700 transition-colors">
                        View Pricing
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>