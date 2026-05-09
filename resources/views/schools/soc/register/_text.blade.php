@php
    $required = $required ?? true;
    $type = $type ?? 'text';
    $autocomplete = $autocomplete ?? false;
    $ic = 'mt-1 w-full rounded-xl border border-thc-navy/15 bg-white px-3 py-2.5 text-sm text-thc-text shadow-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/25';
@endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-thc-navy">{{ $label }}@if($required)<span class="text-thc-maroon"> *</span>@endif</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name) }}"
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($type === 'number') min="0" max="40" @endif
        @if($required) required @endif
        class="{{ $ic }} @error($name) border-thc-maroon/60 @enderror"
    >
    @error($name)
        <p class="mt-1 text-sm text-thc-maroon">{{ $message }}</p>
    @enderror
</div>
