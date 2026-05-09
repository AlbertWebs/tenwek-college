<x-layouts.admin header="Edit page: {{ $page->title }}">
    <form method="post" action="{{ route('admin.soc.pages.update', $page) }}" class="admin-page-wide">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack max-w-3xl">
                <x-admin.ui.group label="Title" for="title" name="title">
                    <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Excerpt" for="excerpt" name="excerpt">
                    <textarea name="excerpt" id="excerpt" rows="3" class="admin-textarea">{{ old('excerpt', $page->excerpt) }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Body (HTML allowed)" for="body" name="body">
                    <textarea name="body" id="body" rows="16" class="admin-textarea font-mono text-xs">{{ old('body', $page->body) }}</textarea>
                </x-admin.ui.group>
                <fieldset class="admin-field-inset space-y-4">
                    <legend class="admin-field-inset-title px-1">SEO</legend>
                    <x-admin.ui.group label="SEO title" for="seo_title" name="seo_title">
                        <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $page->seo_title) }}" class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Meta description" for="seo_description" name="seo_description">
                        <textarea name="seo_description" id="seo_description" rows="2" class="admin-textarea">{{ old('seo_description', $page->seo_description) }}</textarea>
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Keywords (comma-separated)" for="seo_keywords" name="seo_keywords">
                        <input type="text" name="seo_keywords" id="seo_keywords" value="{{ old('seo_keywords', $page->seo_keywords) }}" class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Open Graph title (optional)" for="og_title" name="og_title">
                        <input type="text" name="og_title" id="og_title" value="{{ old('og_title', $page->og_title) }}" class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Canonical path (optional)" for="canonical_path" name="canonical_path">
                        <input type="text" name="canonical_path" id="canonical_path" value="{{ old('canonical_path', $page->canonical_path) }}" class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="OG image path" for="og_image_path" name="og_image_path" hint="Public/ or storage path.">
                        <input type="text" name="og_image_path" id="og_image_path" value="{{ old('og_image_path', $page->og_image_path) }}" class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Robots" for="robots" name="robots">
                        <input type="text" name="robots" id="robots" value="{{ old('robots', $page->robots) }}" class="admin-input">
                    </x-admin.ui.group>
                </fieldset>
                <x-admin.ui.group label="Published at" for="published_at" name="published_at">
                    <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $page->published_at?->format('Y-m-d\TH:i')) }}" class="admin-input max-w-md">
                </x-admin.ui.group>
            </div>
            <div class="admin-actions admin-actions-sticky mt-8 max-w-3xl">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save changes</button>
                    <a href="{{ route('admin.soc.pages.index') }}" class="admin-btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
