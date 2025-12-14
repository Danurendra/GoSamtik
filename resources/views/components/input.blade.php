@props([
    'label' => null,
    'error' => null,
    'type' => 'text',
])

<div class="space-y-1">
    @if($label)
        <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    
    <input 
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-eco-500 focus:ring-2 focus:ring-eco-200 transition-colors ' .
                       ($error ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : '')
        ]) }}
    >
    
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>