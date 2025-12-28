@props(['status'])

@php
    $baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
    $colorClasses = match ($status) {
        'PUBLISHED' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300',
        'PENDING' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300',
        'DRAFT' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        'REJECTED' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    };
@endphp

<span class="{{ $baseClasses }} {{ $colorClasses }}">
    {{ $status }}
</span>
