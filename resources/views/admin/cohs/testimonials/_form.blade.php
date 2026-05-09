@php
    $t = $testimonial ?? null;
@endphp

<x-admin.ui.group label="Name" for="name" name="name">
    <input type="text" name="name" id="name" value="{{ old('name', $t?->name ?? '') }}" required class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Designation / title" for="designation" name="designation">
    <input type="text" name="designation" id="designation" value="{{ old('designation', $t?->designation ?? '') }}" class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Organization" for="organization" name="organization">
    <input type="text" name="organization" id="organization" value="{{ old('organization', $t?->organization ?? '') }}" class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Quote" for="quote" name="quote">
    <textarea name="quote" id="quote" rows="6" required class="admin-textarea">{{ old('quote', $t?->quote ?? '') }}</textarea>
</x-admin.ui.group>

<div class="admin-grid-2">
    <x-admin.ui.group label="Sort order" for="sort_order" name="sort_order">
        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $t?->sort_order ?? 0) }}" min="0" class="admin-input">
    </x-admin.ui.group>
    <div class="flex items-end pb-1">
        <label class="admin-check-row">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $t?->is_published ?? true)) class="admin-checkbox">
            <span>Published</span>
        </label>
    </div>
</div>

<x-admin.ui.group label="Photo" for="image" name="image" hint="Optional. JPG or PNG recommended.">
    <input type="file" name="image" id="image" accept="image/*" class="admin-file-input">
    @if ($t?->image_path)
        <p class="admin-hint">Current: <span class="admin-code">{{ $t->image_path }}</span></p>
    @endif
</x-admin.ui.group>
