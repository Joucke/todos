@props([
    'href' => null,
    'variant' => 'indigo',
    'size' => 'md',
    'title' => null,
])

@php
$variants = [
    'indigo' => 'text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 focus:ring-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 dark:hover:bg-indigo-900/50 dark:focus:ring-indigo-400',
    'red' => 'text-red-600 hover:text-red-700 hover:bg-red-50 focus:ring-red-500 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/50 dark:focus:ring-red-400',
    'gray' => 'text-gray-600 hover:text-gray-700 hover:bg-gray-50 focus:ring-gray-500 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700/50 dark:focus:ring-gray-400',
    'green' => 'text-green-600 hover:text-green-700 hover:bg-green-50 focus:ring-green-500 dark:text-green-400 dark:hover:text-green-300 dark:hover:bg-green-900/50 dark:focus:ring-green-400',
];

$sizes = [
    'sm' => 'p-1.5',
    'md' => 'p-2',
    'lg' => 'p-3',
];

$iconSizes = [
    'sm' => 'w-3 h-3',
    'md' => 'w-4 h-4',
    'lg' => 'w-5 h-5',
];

$classes = implode(' ', [
    'rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-offset-2',
    $variants[$variant] ?? $variants['indigo'],
    $sizes[$size] ?? $sizes['md'],
]);

$iconClass = $iconSizes[$size] ?? $iconSizes['md'];
@endphp

@if($href)
    <a href="{{ $href }}"
       class="{{ $classes }}"
       @if($title) title="{{ $title }}" @endif
       {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button class="{{ $classes }}"
            @if($title) title="{{ $title }}" @endif
            {{ $attributes }}>
        {{ $slot }}
    </button>
@endif
