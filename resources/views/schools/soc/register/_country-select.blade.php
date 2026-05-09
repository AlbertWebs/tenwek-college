@php
    $required = $required ?? true;
    $ic = 'mt-1 w-full rounded-xl border border-thc-navy/15 bg-white px-3 py-2.5 text-sm text-thc-text shadow-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/25';
@endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-thc-navy">{{ $label }}@if($required)<span class="text-thc-maroon"> *</span>@endif</label>
    <select name="{{ $name }}" id="{{ $name }}" @if($required) required @endif class="{{ $ic }} @error($name) border-thc-maroon/60 @enderror">
        <option value="">{{ __('Select…') }}</option>
        @foreach($countries as $c)
            <option value="{{ $c }}" @selected(old($name) === $c)>{{ $c }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-sm text-thc-maroon">{{ $message }}</p>
    @enderror
</div>
