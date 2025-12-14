<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('collections.show', $collection) }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
                <p class="text-gray-500 mt-1">Complete your payment</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg: px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <form method="POST" action="{{ route('payments.process') }}" x-data="paymentForm()" @submit="processing = true">
                        @csrf
                        <input type="hidden" name="collection_id" value="{{ $collection->id }}">

                        <!-- Payment Method Selection -->
                        <x-card class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h2>
                            
                            <div class="space-y-3">
                                <!-- Credit Card -->
                                <label class="relative cursor-pointer block">
                                    <input type="radio" name="payment_method" value="credit_card" x-model="paymentMethod" class="peer sr-only" checked>
                                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-xl peer-checked:border-eco-500 peer-checked:bg-eco-50 hover:border-eco-300 transition-all">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4 peer-checked:bg-eco-100">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">Credit / Debit Card</p>
                                            <p class="text-sm text-gray-500">Visa, Mastercard, American Express</p>
                                        </div>
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-eco-500 peer-checked:bg-eco-500 flex items-center justify-center">
                                            <svg x-show="paymentMethod === 'credit_card'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>

                                <!-- Bank Transfer -->
                                <label class="relative cursor-pointer block">
                                    <input type="radio" name="payment_method" value="bank_transfer" x-model="paymentMethod" class="peer sr-only">
                                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-xl peer-checked:border-eco-500 peer-checked:bg-eco-50 hover:border-eco-300 transition-all">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">Bank Transfer</p>
                                            <p class="text-sm text-gray-500">Direct bank transfer</p>
                                        </div>
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-eco-500 peer-checked:bg-eco-500 flex items-center justify-center">
                                            <svg x-show="paymentMethod === 'bank_transfer'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>

                                <!-- E-Wallet -->
                                <label class="relative cursor-pointer block">
                                    <input type="radio" name="payment_method" value="e_wallet" x-model="paymentMethod" class="peer sr-only">
                                    <div class="flex items-center p-4 border-2 border-gray-200 rounded-xl peer-checked:border-eco-500 peer-checked: bg-eco-50 hover: border-eco-300 transition-all">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">E-Wallet</p>
                                            <p class="text-sm text-gray-500">PayPal, Apple Pay, Google Pay</p>
                                        </div>
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-eco-500 peer-checked:bg-eco-500 flex items-center justify-center">
                                            <svg x-show="paymentMethod === 'e_wallet'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </x-card>

                        <!-- Card Details (shown for credit_card) -->
                        <div x-show="paymentMethod === 'credit_card'" x-transition>
                            <x-card class="mb-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Card Details</h2>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Name on Card</label>
                                        <input type="text" name="card_name" x-model="cardName"
                                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200 transition-colors"
                                               placeholder="John Doe">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                                        <div class="relative">
                                            <input type="text" name="card_number" x-model="cardNumber"
                                                   @input="formatCardNumber()"
                                                   maxlength="19"
                                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200 transition-colors"
                                                   placeholder="1234 5678 9012 3456">
                                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex space-x-1">
                                                <svg class="h-6 w-auto" viewBox="0 0 36 24" fill="none">
                                                    <rect width="36" height="24" rx="4" fill="#1A1F71"/>
                                                    <path d="M15. 5 16.5L13 7. 5H10.5L13 16.5H15.5Z" fill="white"/>
                                                    <path d="M23 7.5L20.5 13. 5L20 10.5C20 10.5 19.5 7.5 16 7.5H12L12 8C12 8 15.5 8. 5 18 11L20 16.5H22.5L26 7.5H23Z" fill="white"/>
                                                </svg>
                                                <svg class="h-6 w-auto" viewBox="0 0 36 24" fill="none">
                                                    <rect width="36" height="24" rx="4" fill="#EB001B" fill-opacity="0.1"/>
                                                    <circle cx="14" cy="12" r="7" fill="#EB001B"/>
                                                    <circle cx="22" cy="12" r="7" fill="#F79E1B"/>
                                                    <path d="M18 6. 8C19.6 8 20. 6 9.9 20.6 12C20.6 14.1 19.6 16 18 17.2C16.4 16 15.4 14.1 15.4 12C15.4 9.9 16.4 8 18 6.8Z" fill="#FF5F00"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                            <input type="text" name="card_expiry" x-model="cardExpiry"
                                                   @input="formatExpiry()"
                                                   maxlength="5"
                                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200 transition-colors"
                                                   placeholder="MM/YY">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">CVC</label>
                                            <input type="text" name="card_cvc" x-model="cardCvc"
                                                   maxlength="4"
                                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200 transition-colors"
                                                   placeholder="123">
                                        </div>
                                    </div>
                                </div>

                                <!-- Demo Notice -->
                                <div class="mt-4 p-3 bg-yellow-50 rounded-xl border border-yellow-100">
                                    <div class="flex items-start space-x-2">
                                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-sm text-yellow-800">
                                            <strong>Demo Mode:</strong> Use any card details for testing. No real charges will be made.
                                        </p>
                                    </div>
                                </div>
                            </x-card>
                        </div>

                        <!-- Bank Transfer Instructions -->
                        <div x-show="paymentMethod === 'bank_transfer'" x-transition>
                            <x-card class="mb-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Bank Transfer Details</h2>
                                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Bank</span>
                                        <span class="font-medium text-gray-900">EcoBank</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Account Name</span>
                                        <span class="font-medium text-gray-900">EcoCollect Services</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Account Number</span>
                                        <span class="font-medium text-gray-900">1234567890</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Reference</span>
                                        <span class="font-medium text-eco-600">ORD-{{ str_pad($collection->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-4">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Please use the reference number when making the transfer.  Your order will be confirmed once payment is received.
                                </p>
                            </x-card>
                        </div>

                        <!-- E-Wallet Selection -->
                        <div x-show="paymentMethod === 'e_wallet'" x-transition>
                            <x-card class="mb-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Choose E-Wallet</h2>
                                <div class="grid grid-cols-3 gap-4">
                                    <button type="button" @click="selectedWallet = 'paypal'"
                                            class="p-4 border-2 rounded-xl transition-colors"
                                            :class="selectedWallet === 'paypal' ? 'border-eco-500 bg-eco-50' :  'border-gray-200 hover:border-eco-300'">
                                        <div class="text-center">
                                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                                <span class="text-blue-600 font-bold text-lg">P</span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">PayPal</span>
                                        </div>
                                    </button>
                                    <button type="button" @click="selectedWallet = 'apple'"
                                            class="p-4 border-2 rounded-xl transition-colors"
                                            :class="selectedWallet === 'apple' ? 'border-eco-500 bg-eco-50' : 'border-gray-200 hover:border-eco-300'">
                                        <div class="text-center">
                                            <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center mx-auto mb-2">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 . 77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2. 94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-. 02 2.5. 87 3.29.87. 78 0 2.26-1.07 3.81-. 91. 65.03 2.47. 26 3.64 1.98-. 09. 06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-. 42 1.44-1.38 2.83M13 3.5c. 73-. 83 1.94-1.46 2.94-1.5.13 1.17-. 34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15. 41-2.35 1.05-3.11z"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">Apple Pay</span>
                                        </div>
                                    </button>
                                    <button type="button" @click="selectedWallet = 'google'"
                                            class="p-4 border-2 rounded-xl transition-colors"
                                            :class="selectedWallet === 'google' ?  'border-eco-500 bg-eco-50' : 'border-gray-200 hover:border-eco-300'">
                                        <div class="text-center">
                                            <div class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center mx-auto mb-2">
                                                <span class="text-2xl">G</span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">Google Pay</span>
                                        </div>
                                    </button>
                                </div>
                            </x-card>
                        </div>

                        <!-- Submit Button -->
                        <x-button type="submit" size="lg" class="w-full" x-bind:disabled="processing">
                            <template x-if="! processing">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Pay ${{ number_format($collection->total_amount * 1.1, 2) }}
                                </span>
                            </template>
                            <template x-if="processing">
                                <span class="flex items-center justify-center">
                                    <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </template>
                        </x-button>

                        <p class="text-center text-sm text-gray-500 mt-4">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Secured by 256-bit SSL encryption
                        </p>
                    </form>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <x-card>
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>

                            <!-- Service Info -->
                            <div class="flex items-center space-x-4 pb-4 border-b border-gray-100">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                     style="background-color: {{ $collection->serviceType->color }}20">
                                    <svg class="w-6 h-6" style="color: {{ $collection->serviceType->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $collection->serviceType->name }}</p>
                                    <p class="text-sm text-gray-500">One-time pickup</p>
                                </div>
                            </div>

                            <!-- Schedule Info -->
                            <div class="py-4 border-b border-gray-100 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Date</span>
                                    <span class="text-gray-900">{{ $collection->scheduled_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Time</span>
                                    <span class="text-gray-900">
                                        {{ \Carbon\Carbon::parse($collection->scheduled_time_start)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($collection->scheduled_time_end)->format('g:i A') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="py-4 border-b border-gray-100">
                                <p class="text-sm text-gray-500 mb-1">Pickup Address</p>
                                <p class="text-sm text-gray-900">{{ $collection->service_address }}</p>
                            </div>

                            <!-- Pricing -->
                            <div class="py-4 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Service Fee</span>
                                    <span class="text-gray-900">${{ number_format($collection->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Tax (10%)</span>
                                    <span class="text-gray-900">${{ number_format($collection->total_amount * 0.1, 2) }}</span>
                                </div>
                                <hr class="border-gray-100">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-900">Total</span>
                                    <span class="text-xl font-bold text-eco-600">${{ number_format($collection->total_amount * 1.1, 2) }}</span>
                                </div>
                            </div>
                        </x-card>

                        <!-- Guarantee Badge -->
                        <div class="mt-4 p-4 bg-eco-50 rounded-xl">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-eco-900 text-sm">100% Satisfaction Guarantee</p>
                                    <p class="text-xs text-eco-700 mt-1">Not satisfied? Get a full refund within 7 days. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function paymentForm() {
            return {
                paymentMethod: 'credit_card',
                selectedWallet: 'paypal',
                cardName: '',
                cardNumber: '',
                cardExpiry: '',
                cardCvc: '',
                processing: false,

                formatCardNumber() {
                    let value = this.cardNumber.replace(/\s/g, '').replace(/\D/g, '');
                    let formatted = '';
                    for (let i = 0; i < value.length; i++) {
                        if (i > 0 && i % 4 === 0) {
                            formatted += ' ';
                        }
                        formatted += value[i];
                    }
                    this.cardNumber = formatted;
                },

                formatExpiry() {
                    let value = this.cardExpiry.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value. substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    this.cardExpiry = value;
                }
            }
        }
    </script>
</x-app-layout>s