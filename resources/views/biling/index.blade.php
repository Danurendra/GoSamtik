<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Billing History</h1>
            <p class="text-gray-500 mt-1">View your payments and download invoices</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <x-card>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Spent</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">${{ number_format($totalSpent, 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-eco-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 . 895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-. 402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">This Month</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">${{ number_format($thisMonthSpent, 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Payments Table -->
            <x-card>
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Payment History</h2>

                @if($payments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-sm text-gray-500 border-b border-gray-100">
                                    <th class="pb-3 font-medium">Date</th>
                                    <th class="pb-3 font-medium">Description</th>
                                    <th class="pb-3 font-medium">Type</th>
                                    <th class="pb-3 font-medium">Status</th>
                                    <th class="pb-3 font-medium text-right">Amount</th>
                                    <th class="pb-3 font-medium text-right">Invoice</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 text-sm text-gray-900">
                                            {{ $payment->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="py-4">
                                            @if($payment->collection)
                                                <p class="text-sm font-medium text-gray-900">{{ $payment->collection->serviceType->name }}</p>
                                                <p class="text-xs text-gray-500">Order #{{ str_pad($payment->collection->id, 6, '0', STR_PAD_LEFT) }}</p>
                                            @elseif($payment->subscription)
                                                <p class="text-sm font-medium text-gray-900">{{ $payment->subscription->subscriptionPlan->name }}</p>
                                                <p class="text-xs text-gray-500">Subscription</p>
                                            @else
                                                <p class="text-sm text-gray-500">-</p>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            <span class="text-sm text-gray-600">
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}
                                            </span>
                                        </td>
                                        <td class="py-4">
                                            @if($payment->status === 'completed')
                                                <x-badge color="green">Paid</x-badge>
                                            @elseif($payment->status === 'pending')
                                                <x-badge color="yellow">Pending</x-badge>
                                            @elseif($payment->status === 'failed')
                                                <x-badge color="red">Failed</x-badge>
                                            @elseif($payment->status === 'refunded')
                                                <x-badge color="gray">Refunded</x-badge>
                                            @else
                                                <x-badge color="gray">{{ ucfirst($payment->status) }}</x-badge>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            <span class="font-medium text-gray-900">${{ number_format($payment->total_amount, 2) }}</span>
                                        </td>
                                        <td class="py-4 text-right">
                                            @if($payment->invoice)
                                                <a href="{{ route('billing. invoice.download', $payment->invoice) }}" 
                                                   class="inline-flex items-center text-eco-600 hover:text-eco-700 text-sm font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    PDF
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $payments->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No payments yet</h3>
                        <p class="text-gray-500">Your payment history will appear here once you make a purchase.</p>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>