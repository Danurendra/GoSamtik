<! DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GO SAMTIK - Professional Waste Collection Services</title>
    <meta name="description" content="Professional waste collection services for your home and business.  Schedule pickups, track collections, and help save the environment.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-eco-500 to-eco-600 rounded-xl flex items-center justify-center shadow-eco">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900">GO<span class="text-eco-600">SAMTIK</span></span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#services" class="text-gray-600 hover:text-eco-600 transition-colors">Services</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-eco-600 transition-colors">How It Works</a>
                    <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-eco-600 transition-colors">Pricing</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-eco-600 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-eco-600 transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-eco-600 text-white font-medium rounded-xl hover:bg-eco-700 transition-colors shadow-eco">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-b from-eco-50 to-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm: px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-flex items-center px-4 py-2 bg-eco-100 text-eco-700 rounded-full text-sm font-medium mb-6">
                        🌱 Eco-friendly waste management
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Professional <span class="text-eco-600">Waste Collection</span> Made Simple
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        Schedule pickups, track collections in real-time, and help save the environment.  Join thousands of satisfied customers choosing EcoCollect.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-eco-600 text-white font-semibold rounded-xl hover:bg-eco-700 transition-colors shadow-eco text-lg">
                            Schedule Your First Pickup
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:border-eco-300 hover:text-eco-600 transition-colors text-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14. 752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            How It Works
                        </a>
                    </div>
                    
                    <!-- Trust Badges -->
                    <div class="flex items-center space-x-8 mt-12 pt-8 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-gray-900">10K+</p>
                            <p class="text-sm text-gray-500">Happy Customers</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-gray-900">50K+</p>
                            <p class="text-sm text-gray-500">Collections Done</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-gray-900">4.9</p>
                            <p class="text-sm text-gray-500">Average Rating</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="relative z-10">
                        <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Waste Collection" 
                             class="rounded-2xl shadow-2xl">
                    </div>
                    <div class="absolute -top-4 -right-4 w-72 h-72 bg-eco-200 rounded-full blur-3xl opacity-60"></div>
                    <div class="absolute -bottom-4 -left-4 w-72 h-72 bg-eco-300 rounded-full blur-3xl opacity-40"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Comprehensive waste management solutions tailored to your needs</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $services = [
                        ['name' => 'General Waste', 'icon' => 'trash', 'color' => '#6b7280', 'description' => 'Regular household waste and non-recyclable materials'],
                        ['name' => 'Recyclables', 'icon' => 'recycle', 'color' => '#22c55e', 'description' => 'Paper, plastic, glass, and metal recycling'],
                        ['name' => 'Organic Waste', 'icon' => 'leaf', 'color' => '#84cc16', 'description' => 'Food scraps and compostable materials'],
                        ['name' => 'Hazardous Waste', 'icon' => 'warning', 'color' => '#ef4444', 'description' => 'Safe disposal of dangerous materials'],
                        ['name' => 'Bulk Items', 'icon' => 'box', 'color' => '#8b5cf6', 'description' => 'Furniture, appliances, and large items'],
                        ['name' => 'Construction Debris', 'icon' => 'building', 'color' => '#f59e0b', 'description' => 'Building materials and renovation waste'],
                    ];
                @endphp

                @foreach($services as $service)
                    <div class="group p-6 bg-white border border-gray-100 rounded-2xl hover:shadow-xl hover:border-eco-200 transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110"
                             style="background-color: {{ $service['color'] }}20">
                            <svg class="w-7 h-7" style="color: {{ $service['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $service['name'] }}</h3>
                        <p class="text-gray-600">{{ $service['description'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('services.index') }}" class="inline-flex items-center text-eco-600 hover:text-eco-700 font-semibold text-lg">
                    View All Services
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Get started with EcoCollect in just a few simple steps</p>
            </div>

            <div class="grid grid-cols-1 md: grid-cols-4 gap-8">
                @php
                    $steps = [
                        ['step' => 1, 'title' => 'Choose Service', 'description' => 'Select the type of waste you need collected'],
                        ['step' => 2, 'title' => 'Schedule Pickup', 'description' => 'Pick a convenient date and time slot'],
                        ['step' => 3, 'title' => 'We Collect', 'description' => 'Our professional team arrives on time'],
                        ['step' => 4, 'title' => 'Track & Rate', 'description' => 'Monitor in real-time and share your feedback'],
                    ];
                @endphp

                @foreach($steps as $index => $step)
                    <div class="relative text-center">
                        <div class="relative w-16 h-16 z-10 bg-eco-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold shadow-eco">
                            {{ $step['step'] }}
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600">{{ $step['description'] }}</p>
                        
                        @if($index < 3)
                            <div class="hidden md:block absolute z-0 top-8 left-full w-full h-0.5 bg-eco-200 -translate-x-1/2 "></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-eco-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg: px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Get Started?</h2>
            <p class="text-xl text-eco-100 mb-8">Join thousands of satisfied customers and make waste management effortless. </p>
            <div class="flex flex-col sm: flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-eco-600 font-semibold rounded-xl hover: bg-eco-50 transition-colors shadow-lg text-lg">
                    Create Free Account
                </a>
                <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white font-semibold rounded-xl hover:bg-eco-700 transition-colors text-lg">
                    View Pricing
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg: px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-eco-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">EcoCollect</span>
                    </div>
                    <p class="text-sm">Professional waste collection services for a cleaner, greener future.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Services</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-eco-400 transition-colors">General Waste</a></li>
                        <li><a href="#" class="hover:text-eco-400 transition-colors">Recyclables</a></li>
                        <li><a href="#" class="hover:text-eco-400 transition-colors">Organic Waste</a></li>
                        <li><a href="#" class="hover:text-eco-400 transition-colors">Bulk Items</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-eco-400 transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-eco-400 transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-eco-400 transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-eco-400 transition-colors">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li>support@ecocollect.com</li>
                        <li>+1 (555) 123-4567</li>
                        <li>123 Green Street, Eco City</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} EcoCollect. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>