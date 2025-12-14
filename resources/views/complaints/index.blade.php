<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Support Tickets</h1>
                <p class="text-gray-500 mt-1">View and manage your support requests</p>
            </div>
            <a href="{{ route('complaints.create') }}">
                <x-button>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Ticket
                </x-button>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($complaints->count() > 0)
                <div class="space-y-4">
                    @foreach($complaints as $complaint)
                        <a href="{{ route('complaints.show', $complaint) }}" class="block">
                            <x-card hover>
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                                    {{ $complaint->status === 'open' ? 'bg-yellow-100' : ($complaint->status === 'resolved' ? 'bg-green-100' : 'bg-blue-100') }}">
                                            @if($complaint->status === 'open')
                                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h. 01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @elseif($complaint->status === 'resolved' || $complaint->status === 'closed')
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm font-mono text-gray-500">{{ $complaint->ticket_number }}</span>
                                                <x-badge : color="$complaint->priority_color">
                                                    {{ ucfirst($complaint->priority) }}
                                                </x-badge>
                                            </div>
                                            <h3 class="font-semibold text-gray-900 mt-1">{{ $complaint->subject }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ ucfirst(str_replace('_', ' ', $complaint->category)) }}
                                                @if($complaint->collection)
                                                    • {{ $complaint->collection->serviceType->name }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($complaint->status === 'open')
                                            <x-badge color="yellow">Open</x-badge>
                                        @elseif($complaint->status === 'in_progress')
                                            <x-badge color="blue">In Progress</x-badge>
                                        @elseif($complaint->status === 'resolved')
                                            <x-badge color="green">Resolved</x-badge>
                                        @else
                                            <x-badge color="gray">Closed</x-badge>
                                        @endif
                                        <p class="text-sm text-gray-500 mt-2">{{ $complaint->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </x-card>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $complaints->links() }}
                </div>
            @else
                <x-card>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No support tickets</h3>
                        <p class="text-gray-500 mb-6">You haven't submitted any support requests yet.</p>
                        <a href="{{ route('complaints.create') }}">
                            <x-button variant="outline">Submit a Ticket</x-button>
                        </a>
                    </div>
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>