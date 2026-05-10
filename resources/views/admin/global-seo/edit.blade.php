<x-layouts.admin
    header="Global SEO"
    :breadcrumbs="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Global SEO']]"
>
    @php
        $ogPath = $globalSeo['default_og_image'] ?? '';
        $ogPreviewUrl = $ogPath !== '' && ! \Illuminate\Support\Str::startsWith($ogPath, ['http://', 'https://'])
            ? asset($ogPath)
            : ($ogPath !== '' ? $ogPath : null);
        $isUploadedOg = $ogPath !== '' && str_starts_with($ogPath, 'storage/global-seo/');
    @endphp

    <form method="post" action="{{ route('admin.global-seo.update') }}" class="admin-page-narrow" enctype="multipart/form-data">
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

                <div class="admin-field">
                    <label class="admin-label" for="og_image_upload">Default OG image</label>
                    <p class="mt-1 text-xs text-thc-text/65">Upload a file (JPEG, PNG, WebP, or GIF, max 8&nbsp;MB), or set a path under <code class="admin-code">public/</code> / full URL below.</p>

                    @if($ogPreviewUrl)
                        <div class="mt-3 overflow-hidden rounded-xl border border-thc-navy/10 bg-thc-navy/[0.02] p-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-thc-text/55">{{ __('Current image') }}</p>
                            <img src="{{ $ogPreviewUrl }}" alt="" class="mt-2 max-h-40 w-auto max-w-full rounded-lg object-contain object-left shadow-sm" width="1200" height="630" loading="lazy">
                            @if($isUploadedOg)
                                <label class="mt-3 flex cursor-pointer items-center gap-2 text-sm text-thc-text/80">
                                    <input type="checkbox" name="clear_og_image" value="1" class="rounded border-thc-navy/20 text-thc-royal focus:ring-thc-royal/30">
                                    {{ __('Remove uploaded image and fall back to config default') }}
                                </label>
                            @endif
                        </div>
                    @endif

                    <input
                        type="file"
                        name="og_image_upload"
                        id="og_image_upload"
                        accept="image/jpeg,image/png,image/webp,image/gif"
                        class="admin-input mt-3 file:mr-4 file:rounded-lg file:border-0 file:bg-thc-navy file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-thc-royal"
                    >
                    @error('og_image_upload')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror

                    <x-admin.ui.group label="Image path or URL (optional)" for="default_og_image" name="default_og_image" hint="e.g. ctc.jpg under public/, storage/global-seo/… after upload, or https://… . Leave empty to use the default from config when no file is uploaded." class="mt-5">
                        <input type="text" name="default_og_image" id="default_og_image" value="{{ old('default_og_image', $globalSeo['default_og_image']) }}" class="admin-input" placeholder="{{ config('tenwek.default_og_image') }}">
                    </x-admin.ui.group>
                </div>

                <x-admin.ui.group label="Default robots" for="default_robots" name="default_robots" hint="e.g. index,follow. Leave empty for the built-in default.">
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
