@props([
    'type' => 'edit',
    'size' => 'w-4 h-4'
])

@php
$icons = [
    'edit' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
    'add' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
    'leave' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
    'invite' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
];

$path = $icons[$type] ?? $icons['edit'];
@endphp

<svg class="{{ $size }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" {{ $attributes }}>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
</svg>
