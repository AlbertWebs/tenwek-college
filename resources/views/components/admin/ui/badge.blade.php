@props([
    'variant' => 'neutral',
])

@php
    $variantClass = match ($variant) {
        'success' => 'admin-badge-success',
        'warning' => 'admin-badge-warning',
        'danger' => 'admin-badge-danger',
        'muted' => 'admin-badge-muted',
        default => 'admin-badge-neutral',
    };
@endphp

<span {{ $attributes->class(['admin-badge', $variantClass]) }}>
    {{ $slot }}
</span>
