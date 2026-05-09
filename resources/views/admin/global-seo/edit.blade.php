<x-layouts.admin
    header="Global SEO"
    :breadcrumbs="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Global SEO']]"
>
    <form method="post" action="{{ route('admin.global-seo.update') }}" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <p class="text-sm leading-relaxed text-thc-text/85">
                Defaults for pages that use <code class="admin-code">SeoPresenter::build()</code> without a custom description, keywords, Open Graph image, or robots tag.
                The School of Chaplaincy landing still has its own SEO screen for <code class="admin-code">/soc</code>.
            </p>

            <div class="admin-form-stack mt-6">
                <x-admin.ui.group label="Default meta description" for="default_meta_description" name="default_meta_description" hint="Falls back to the institution tagline when empty.">
                    <textarea name="default_meta_description" id="default_meta_description" rows="3" class="admin-textarea">{{ old('default_meta_description', $globalSeo['default_meta_description']) }}</textarea>
                </x-admin.ui.group>

                <x-admin.ui.group label="Default keywords" for="default_keywords" name="default_keywords" hint="Comma-separated (e.g. Tenwek, nursing college, Bomet).">
                    <input type="text" name="default_keywords" id="default_keywords" value="{{ old('default_keywords', $globalSeo['default_keywords']) }}" class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Default OG image" for="default_og_image" name="default_og_image" hint="Path under public/ (e.g. ctc.jpg) or a full URL.">
                    <input type="text" name="default_og_image" id="default_og_image" value="{{ old('default_og_image', $globalSeo['default_og_image']) }}" class="admin-input" placeholder="{{ config('tenwek.default_og_image') }}">
                </x-admin.ui.group>

                <x-admin.ui.group label="Default robots" for="default_robots" name="default_robots" hint="e.g. index,follow — leave empty for the built-in default.">
                    <input type="text" name="default_robots" id="default_robots" value="{{ old('default_robots', $globalSeo['default_robots']) }}" class="admin-input" placeholder="index,follow,max-image-preview:large,…">
                </x-admin.ui.group>
            </div>

            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save global SEO</button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
