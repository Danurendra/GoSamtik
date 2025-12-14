@props([
    'padding' => true,
    'hover' => false,
])

<div {{ $attributes->merge([
    'class' => 'bg-white rounded-2xl border border-gray-100 shadow-sm ' . 
               ($hover ? 'hover:shadow-lg hover:border-eco-100 transition-all duration-300' : '') . 
               ($padding ? ' p-6' : '')
]) }}>
    {{ $slot }}
</div>