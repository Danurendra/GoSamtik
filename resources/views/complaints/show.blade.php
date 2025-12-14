<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('complaints.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $complaint->ticket_number }}</h1>
                    @if($complaint->status === 'open')
                        <x-badge color="yellow">Open</x-badge>
                    @elseif($complaint->status === 'in_progress')
                        <x-badge color="blue">In Progress</x-badge>
                    @elseif($complaint->status === 'resolved')
                        <x-badge color="green">Resolved</x-badge>
                    @else
                        <x-badge color="gray">Closed</x-badge>
                    @endif
                </div>
                <p class="text-gray-500 mt-1">Submitted {{ $complaint->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg: px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Issue Details -->
                    <x-card>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $complaint->subject }}</h2>
                        
                        <div class="prose prose-sm max-w-none text-gray-600">
                            {!! nl2br(e($complaint->description)) !!}
                        </div>

                        @if($complaint->collection)
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <p class="text-sm text-gray-500 mb-2">Related Collection</p>
                                <a href="{{ route('collections.show', $complaint->collection) }}" 
                                   class="inline-flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3"
                                         style="background-color: {{ $complaint->collection->serviceType->color }}20">
                                        <svg class="w-5 h-5" style="color: {{ $complaint->collection->serviceType->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $complaint->collection->serviceType->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $complaint->collection->scheduled_date->format('M d, Y') }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </x-card>

                    <!-- Resolution (if resolved) -->
                    @if($complaint->resolution)
                        <x-card class="border-l-4 border-eco-500">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Resolution</h3>
                                    <p class="text-gray-600 mt-2">{!! nl2br(e($complaint->resolution)) !!}</p>
                                    @if($complaint->resolved_at)
                                        <p class="text-sm text-gray-500 mt-2">
                                            Resolved on {{ $complaint->resolved_at->format('M d, Y \a\t g:i A') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </x-card>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Ticket Info -->
                    <x-card>
                        <h3 class="font-semibold text-gray-900 mb-4">Ticket Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Category</p>
                                <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $complaint->category)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Priority</p>
                                <x-badge :color="$complaint->priority_color">
                                    {{ ucfirst($complaint->priority) }}
                                </x-badge>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</p>
                            </div>
                            @if($complaint->assignee)
                                <div>
                                    <p class="text-sm text-gray-500">Assigned To</p>
                                    <p class="font-medium text-gray-900">{{ $complaint->assignee->name }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-500">Submitted</p>
                                <p class="font-medium text-gray-900">{{ $complaint->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </x-card>

                    <!-- Help Card -->
                    <x-card class="bg-gray-50 border-0">
                        <h3 class="font-semibold text-gray-900 mb-2">Need More Help?</h3>
                        <p class="text-sm text-gray-600 mb-4">If you have additional information about this issue, please contact our support team.</p>
                        <a href="mailto:support@ecocollect.com" class="text-eco-600 hover:text-eco-700 font-medium text-sm">
                            support@ecocollect.com
                        </a>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>