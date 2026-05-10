@php
    /** @var \App\Models\SchoolEvent $event */
    $e = $event;
@endphp

<x-admin.ui.group label="Title" for="title" name="title">
    <input type="text" name="title" id="title" value="{{ old('title', $e->title ?? '') }}" required class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Slug" for="slug" name="slug" hint="Optional; generated from title when left blank.">
    <input type="text" name="slug" id="slug" value="{{ old('slug', $e->slug ?? '') }}" class="admin-input" placeholder="auto-generated">
</x-admin.ui.group>

<x-admin.ui.group label="Excerpt" for="excerpt" name="excerpt">
    <textarea name="excerpt" id="excerpt" rows="3" class="admin-textarea">{{ old('excerpt', $e->excerpt ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Content" for="event-body" name="body" hint="Rich text; use headings and lists for readability.">
    <textarea name="body" id="event-body" rows="14" class="admin-textarea font-mono text-sm">{{ old('body', $e->body ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Featured image" for="image" name="image" hint="Optional. JPEG, PNG, WebP, or GIF (max 5MB).">
    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp,image/gif" class="admin-file-input">
    @if ($e->exists && $e->image_path)
        <div class="mt-3 flex flex-wrap items-end gap-4">
            <img src="{{ $e->imagePublicUrl() }}" alt="" class="h-28 max-w-[12rem] rounded-lg border border-thc-navy/10 object-cover shadow-sm" loading="lazy" decoding="async" width="192" height="112">
            <p class="admin-hint">Current: <span class="admin-code">{{ $e->image_path }}</span> — upload a new file to replace.</p>
        </div>
    @endif
</x-admin.ui.group>

<div class="admin-grid-2">
    <x-admin.ui.group label="Starts at" for="starts_at" name="starts_at">
        <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $e->starts_at?->format('Y-m-d\TH:i')) }}" required class="admin-input">
    </x-admin.ui.group>
    <x-admin.ui.group label="Ends at (optional)" for="ends_at" name="ends_at">
        <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $e->ends_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
    </x-admin.ui.group>
</div>

<x-admin.ui.group label="Location (optional)" for="location" name="location">
    <input type="text" name="location" id="location" value="{{ old('location', $e->location ?? '') }}" class="admin-input" placeholder="e.g. Main campus hall">
</x-admin.ui.group>

<x-admin.ui.group label="Registration / info URL (optional)" for="registration_url" name="registration_url">
    <input type="url" name="registration_url" id="registration_url" value="{{ old('registration_url', $e->registration_url ?? '') }}" class="admin-input" placeholder="https://">
</x-admin.ui.group>

<div class="admin-grid-2">
    <x-admin.ui.group label="Published at" for="published_at" name="published_at" hint="Leave empty to keep as draft.">
        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $e->published_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
    </x-admin.ui.group>
    <x-admin.ui.group label="SEO title (optional)" for="seo_title" name="seo_title">
        <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $e->seo_title ?? '') }}" class="admin-input">
    </x-admin.ui.group>
</div>

<x-admin.ui.group label="SEO description (optional)" for="seo_description" name="seo_description">
    <textarea name="seo_description" id="seo_description" rows="2" class="admin-textarea">{{ old('seo_description', $e->seo_description ?? '') }}</textarea>
</x-admin.ui.group>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tinymce@7.4.1/tinymce.min.js" referrerpolicy="origin"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof tinymce === 'undefined') {
                    return;
                }
                tinymce.init({
                    selector: '#event-body',
                    height: 420,
                    menubar: false,
                    plugins: 'link lists',
                    toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link removeformat',
                    license_key: 'gpl',
                    promotion: false,
                    branding: false,
                });
            });
        </script>
    @endpush
@endonce
