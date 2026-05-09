@props([
    'label' => null,
    'for' => null,
    'name' => null,
    'hint' => null,
])

@php
    $hasError = $name !== null && $errors->has($name);
@endphp

<div
    {{ $attributes->class([
        'admin-form-group',
        'admin-field-invalid' => $hasError,
    ]) }}
>
    @if ($label !== null && $for !== null)
        <label class="admin-label" for="{{ $for }}">{{ $label }}</label>
    @elseif ($label !== null)
        <span class="admin-label">{{ $label }}</span>
    @endif

    @if ($hint)
        <p class="admin-hint">{{ $hint }}</p>
    @endif

    {{ $slot }}

    @if ($name !== null)
        @error($name)
            <p class="admin-field-error" role="alert">{{ $message }}</p>
        @enderror
    @endif
</div>
