<x-admin.ui.group label="Title" for="title" name="title">
    <input type="text" name="title" id="title" value="{{ old('title', $post->title ?? '') }}" required class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Slug" for="slug" name="slug" hint="Optional; generated from title when left blank.">
    <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug ?? '') }}" class="admin-input" placeholder="auto-generated">
</x-admin.ui.group>

<x-admin.ui.group label="Excerpt" for="excerpt" name="excerpt">
    <textarea name="excerpt" id="excerpt" rows="3" class="admin-textarea">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Body" for="body" name="body">
    <textarea name="body" id="body" rows="12" class="admin-textarea">{{ old('body', $post->body ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Featured image path" for="featured_image_path" name="featured_image_path" hint="Path under public/ or storage URL.">
    <input type="text" name="featured_image_path" id="featured_image_path" value="{{ old('featured_image_path', $post->featured_image_path ?? '') }}" class="admin-input" placeholder="path under public/ or storage URL">
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
