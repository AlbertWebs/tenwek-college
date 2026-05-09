@php
    $required = $required ?? true;
    $rows = $rows ?? 4;
    $ic = 'mt-1 w-full rounded-xl border border-thc-navy/15 bg-white px-3 py-2.5 text-sm text-thc-text shadow-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/25';
@endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-thc-navy">{{ $label }}@if($required)<span class="text-thc-maroon"> *</span>@endif</label>
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        class="{{ $ic }} @error($name) border-thc-maroon/60 @enderror"
    >{{ old($name) }}</textarea>
    @error($name)
        <p class="mt-1 text-sm text-thc-maroon">{{ $message }}</p>
    @enderror
</div>
