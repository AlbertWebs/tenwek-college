<x-admin.ui.group label="Title" for="title" name="title">
    <input type="text" name="title" id="title" value="{{ old('title', $post->title ?? '') }}" required class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Slug" for="slug" name="slug" hint="Optional; generated from title when left blank.">
    <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug ?? '') }}" class="admin-input" placeholder="auto-generated">
</x-admin.ui.group>

<x-admin.ui.group label="Excerpt" for="excerpt" name="excerpt">
    <textarea name="excerpt" id="excerpt" rows="3" class="admin-textarea">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Body" for="news-body" name="body" hint="Rich text; headings and lists appear on the public news page.">
    <textarea name="body" id="news-body" rows="14" class="admin-textarea">{{ old('body', $post->body ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Featured image" for="featured_image" name="featured_image" hint="Optional. JPEG, PNG, WebP, or GIF (max 8MB). Shown on listings and at the top of the article.">
    <input type="file" name="featured_image" id="featured_image" accept="image/jpeg,image/png,image/webp,image/gif" class="admin-file-input">
    @if ($post->exists && $post->featured_image_path)
        <div class="mt-3 flex flex-wrap items-end gap-4">
            <img src="{{ $post->featuredImagePublicUrl() }}" alt="" class="h-28 max-w-[12rem] rounded-lg border border-thc-navy/10 object-cover shadow-sm" loading="lazy" decoding="async" width="192" height="112">
            <p class="admin-hint">Current image — choose a new file to replace it.</p>
        </div>
    @endif
</x-admin.ui.group>

<div class="admin-grid-2">
    <x-admin.ui.group label="SEO title" for="seo_title" name="seo_title">
        <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $post->seo_title ?? '') }}" class="admin-input">
    </x-admin.ui.group>
    <x-admin.ui.group label="Published at" for="published_at" name="published_at">
        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
    </x-admin.ui.group>
</div>

<x-admin.ui.group label="SEO description" for="seo_description" name="seo_description">
    <textarea name="seo_description" id="seo_description" rows="2" class="admin-textarea">{{ old('seo_description', $post->seo_description ?? '') }}</textarea>
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
                    selector: '#news-body',
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
