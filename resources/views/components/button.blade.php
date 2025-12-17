@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, outline, danger
    'size' => 'md', // sm, md, lg
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';

$variants = [
    'primary' => 'bg-eco-600 text-white hover:bg-eco-700 focus:ring-eco-500 shadow-eco',
    'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-500',
    'outline' => 'border-2 border-eco-600 text-eco-600 hover: bg-eco-50 focus: ring-eco-500',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-5 py-2. 5 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<button type="{{ $type }}" 
    {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>