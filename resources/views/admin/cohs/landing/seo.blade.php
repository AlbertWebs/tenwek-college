<x-layouts.admin header="COHS — Landing SEO (/cohs)">
    <form method="post" action="{{ route('admin.cohs.seo.update') }}" enctype="multipart/form-data" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                @foreach (['title' => 'Page title', 'description' => 'Meta description', 'keywords' => 'Keywords', 'canonical' => 'Canonical URL', 'og_title' => 'Open Graph title', 'og_description' => 'Open Graph description', 'og_image' => 'OG image path or URL', 'robots' => 'Robots (e.g. index,follow)'] as $field => $label)
                    <x-admin.ui.group :label="$label" :for="$field" :name="$field">
                        <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $landingSeo[$field] ?? '') }}" class="admin-input">
                    </x-admin.ui.group>
                @endforeach
                <x-admin.ui.group label="Upload OG image" for="og_image_upload" name="og_image_upload" hint="Saves to storage and sets OG image path. You can still paste a full URL in the field above.">
                    <input type="file" name="og_image_upload" id="og_image_upload" accept="image/*" class="admin-file-input">
                    @php $ogPreview = $landingSeo['og_image'] ?? null; @endphp
                    @if (filled($ogPreview) && ! str_starts_with($ogPreview, 'http'))
                        <p class="admin-hint">Current file path: <span class="admin-code">{{ $ogPreview }}</span></p>
                    @endif
                </x-admin.ui.group>
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save</button>
                    <a href="{{ route('admin.cohs.dashboard') }}" class="admin-btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
