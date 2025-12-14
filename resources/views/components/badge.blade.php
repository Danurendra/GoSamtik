@props([
    'color' => 'gray', // gray, green, yellow, red, blue
])

@php
$colors = [
    'gray' => 'bg-gray-100 text-gray-700',
    'green' => 'bg-eco-100 text-eco-700',
    'yellow' => 'bg-yellow-100 text-yellow-700',
    'red' => 'bg-red-100 text-red-700',
    'blue' => 'bg-blue-100 text-blue-700',
    'indigo' => 'bg-indigo-100 text-indigo-700',
    'orange' => 'bg-orange-100 text-orange-700',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $colors[$color]]) }}>
    {{ $slot }}
</span>