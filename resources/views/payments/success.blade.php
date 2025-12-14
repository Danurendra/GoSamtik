<x-app-layout>
    <div class="py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Animation -->
            <div class="text-center mb-8">
                <div class="w-24 h-24 bg-eco-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <svg class="w-12 h-12 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
                <p class="text-gray-500">Thank you for your order. Your collection has been confirmed.</p>
            </div>

            <!-- Order Details Card -->
            <x-card class="mb-6">
                <div class="text-center pb-6 border-b border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Order Number</p>
                    <p class="text-2xl font-bold text-gray-900">#{{ str_pad($collection->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>

                <div class="py-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Service</span>
                        <span class="font-medium text-gray-900">{{ $collection->serviceType->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Scheduled Date</span>
                        <span class="font-medium text-gray-900">{{ $collection->scheduled_date->format('l, M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Time Window</span>
                        <span class="font-medium text-gray-900">
                            {{ \Carbon\Carbon:: parse($collection->scheduled_time_start)->format('g:i A') }} - 
                            {{ \Carbon\Carbon::parse($collection->scheduled_time_end)->format('g:i A') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Address</span>
                        <span class="font-medium text-gray-900 text-right max-w-xs">{{ $collection->service_address }}</span>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Total Paid</span>
                        <span class="text-2xl font-bold text-eco-600">${{ number_format($collection->payment->total_amount, 2) }}</span>
                    </div>
                    @if($collection->payment)
                        <div class="flex justify-between mt-2 text-sm">
                            <span class="text-gray-500">Transaction ID</span>
                            <span class="text-gray-700">{{ $collection->payment->transaction_id }}</span>
                        </div>
                    @endif
                </div>
            </x-card>

            <!-- What's Next -->
            <x-card class="mb-6">
                <h2 class="font-semibold text-gray-900 mb-4">What's Next? </h2>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-eco-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-eco-600 font-bold text-sm">1</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Confirmation Email</p>
                            <p class="text-sm text-gray-500">We've sent a confirmation email with your order details.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-eco-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-eco-600 font-bold text-sm">2</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Reminder Notification</p>
                            <p class="text-sm text-gray-500">You'll receive a reminder 24 hours before your scheduled pickup.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-eco-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-eco-600 font-bold text-sm">3</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Collection Day</p>
                            <p class="text-sm text-gray-500">Place your waste at the designated spot before the scheduled time.</p>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('collections.show', $collection) }}" class="flex-1">
                    <x-button variant="outline" class="w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Order Details
                    </x-button>
                </a>
                <a href="{{ route('dashboard') }}" class="flex-1">
                    <x-button class="w-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Go to Dashboard
                    </x-button>
                </a>
            </div>

            <!-- Download Invoice -->
            @if($collection->payment && $collection->payment->invoice)
                <div class="text-center mt-6">
                    <a href="{{ route('billing.invoice. download', $collection->payment->invoice) }}" 
                       class="inline-flex items-center text-eco-600 hover:text-eco-700 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Invoice (PDF)
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>