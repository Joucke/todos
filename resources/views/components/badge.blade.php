@props([
    'variant' => 'gray',
    'size' => 'sm'
])

@php
$variants = [
    'blue' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
    'green' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    'red' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
    'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
];

$sizes = [
    'xs' => 'px-2 py-0.5 text-xs',
    'sm' => 'px-2.5 py-0.5 text-xs',
    'md' => 'px-3 py-1 text-sm',
];

$classes = implode(' ', [
    'inline-flex items-center rounded-full font-medium',
    $variants[$variant] ?? $variants['gray'],
    $sizes[$size] ?? $sizes['sm'],
]);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
