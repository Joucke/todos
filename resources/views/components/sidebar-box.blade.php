@props([
    'type' => 'info',
    'icon' => null,
    'title' => null,
])

@php
$types = [
    'info' => [
        'classes' => 'bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 border-blue-200 dark:border-blue-800',
        'textColor' => 'text-blue-900 dark:text-blue-100',
        'contentColor' => 'text-blue-800 dark:text-blue-200',
        'iconColor' => 'text-blue-600 dark:text-blue-400'
    ],
    'neutral' => [
        'classes' => 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 shadow-sm',
        'textColor' => 'text-gray-900 dark:text-white',
        'contentColor' => 'text-gray-700 dark:text-gray-400',
        'iconColor' => 'text-neutral-800 dark:text-gray-300'
    ],
    'success' => [
        'classes' => 'bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/20 border-green-200 dark:border-green-800',
        'textColor' => 'text-green-900 dark:text-green-100',
        'contentColor' => 'text-green-800 dark:text-green-200',
        'iconColor' => 'text-green-600 dark:text-green-400'
    ],
    'warning' => [
        'classes' => 'bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border-orange-200 dark:border-orange-800',
        'textColor' => 'text-orange-900 dark:text-orange-100',
        'contentColor' => 'text-orange-800 dark:text-orange-200',
        'iconColor' => 'text-orange-600 dark:text-orange-400'
    ],
    'danger' => [
        'classes' => 'bg-gradient-to-br from-red-50 to-pink-100 dark:from-red-900/20 dark:to-pink-900/20 border-red-200 dark:border-red-800',
        'textColor' => 'text-red-900 dark:text-red-100',
        'contentColor' => 'text-red-800 dark:text-red-200',
        'iconColor' => 'text-red-600 dark:text-red-400'
    ],
    'purple' => [
        'classes' => 'bg-gradient-to-br from-purple-50 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 border-purple-200 dark:border-purple-800',
        'textColor' => 'text-purple-900 dark:text-purple-100',
        'contentColor' => 'text-purple-800 dark:text-purple-200',
        'iconColor' => 'text-purple-600 dark:text-purple-400'
    ],
];

$iconTypes = [
    'info' => 'info',
    'neutral' => 'info',
    'success' => 'check-circle',
    'warning' => 'warning',
    'danger' => 'warning',
    'purple' => 'info',
];

$typeConfig = $types[$type] ?? $types['info'];
$classes = $typeConfig['classes'];
$textColor = $typeConfig['textColor'];
$contentColor = $typeConfig['contentColor'];
$iconColor = $typeConfig['iconColor'];
$iconType = $icon ?? $iconTypes[$type] ?? 'info';
@endphp

<div class="{{ $classes }} {{ $textColor }} border rounded-xl p-6">
    @if($icon !== false && $title)
        <div class="flex items-center space-x-2 mb-3">
            <x-icon type="{{ $iconType }}" size="xl" style="thin" class="{{ $iconColor }}" />
            <h4 class="text-sm font-medium {{ $iconColor }}">{{ $title }}</h4>
        </div>
        <div class="text-xs {{ $contentColor }}">
            {{ $slot }}
        </div>
    @elseif($title)
        <h4 class="text-sm font-medium mb-3 {{ $iconColor }}">{{ $title }}</h4>
        <div class="text-xs {{ $contentColor }}">
            {{ $slot }}
        </div>
    @else
        <div class="text-xs {{ $contentColor }}">
            {{ $slot }}
        </div>
    @endif
</div>
