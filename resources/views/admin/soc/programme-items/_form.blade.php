<x-admin.ui.group label="URL slug" for="slug" name="slug" hint="Lowercase, hyphens only. Shown in programme URLs.">
    <input type="text" name="slug" id="slug" value="{{ old('slug', $item->slug ?? '') }}" required pattern="[a-z0-9]+(?:-[a-z0-9]+)*" class="admin-input" placeholder="e.g. diploma-in-chaplaincy">
</x-admin.ui.group>

<x-admin.ui.group label="Title" for="title" name="title">
    <input type="text" name="title" id="title" value="{{ old('title', $item->title ?? '') }}" required class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Badge" for="badge" name="badge" hint="Optional label, e.g. Level 6.">
    <input type="text" name="badge" id="badge" value="{{ old('badge', $item->badge ?? '') }}" class="admin-input" placeholder="e.g. Level 6">
</x-admin.ui.group>

<x-admin.ui.group label="Summary" for="summary" name="summary">
    <textarea name="summary" id="summary" rows="4" required class="admin-textarea">{{ old('summary', $item->summary ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Detail page body (HTML)" for="body" name="body" hint="If empty, the public page uses a short default layout with apply/fees links.">
    <textarea name="body" id="body" rows="10" class="admin-textarea font-mono text-xs">{{ old('body', $item->body ?? '') }}</textarea>
</x-admin.ui.group>

<fieldset class="admin-field-inset space-y-4">
    <legend class="admin-field-inset-title px-1">SEO (detail page)</legend>
    <x-admin.ui.group label="SEO title" for="seo_title" name="seo_title">
        <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $item->seo_title ?? '') }}" class="admin-input">
    </x-admin.ui.group>
    <x-admin.ui.group label="Meta description" for="seo_description" name="seo_description">
        <textarea name="seo_description" id="seo_description" rows="2" class="admin-textarea">{{ old('seo_description', $item->seo_description ?? '') }}</textarea>
    </x-admin.ui.group>
    <x-admin.ui.group label="Keywords (comma-separated)" for="seo_keywords" name="seo_keywords">
        <input type="text" name="seo_keywords" id="seo_keywords" value="{{ old('seo_keywords', $item->seo_keywords ?? '') }}" class="admin-input">
    </x-admin.ui.group>
    <x-admin.ui.group label="Open Graph title" for="og_title" name="og_title">
        <input type="text" name="og_title" id="og_title" value="{{ old('og_title', $item->og_title ?? '') }}" class="admin-input">
    </x-admin.ui.group>
    <x-admin.ui.group label="OG image path" for="og_image_path" name="og_image_path" hint="Public path or soc/… storage reference.">
        <input type="text" name="og_image_path" id="og_image_path" value="{{ old('og_image_path', $item->og_image_path ?? '') }}" class="admin-input">
    </x-admin.ui.group>
</fieldset>

<x-admin.ui.group label="Sort order" for="sort_order" name="sort_order">
    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0" class="admin-input">
</x-admin.ui.group>

<label class="admin-check-row">
    <input type="hidden" name="is_published" value="0">
    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item->is_published ?? true)) class="admin-checkbox">
    <span>Published</span>
</label>
