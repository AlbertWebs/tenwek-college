@php
    $h = $hero;
    $cta = $h['primary_cta'] ?? [];
    $sec = $h['secondary_cta'] ?? [];
@endphp
<x-layouts.admin header="COHS — Hero &amp; branding images">
    <form method="post" action="{{ route('admin.cohs.hero.update') }}" enctype="multipart/form-data" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="Badge" for="badge" name="badge">
                    <input type="text" name="badge" id="badge" value="{{ old('badge', $h['badge'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Eyebrow" for="eyebrow" name="eyebrow">
                    <input type="text" name="eyebrow" id="eyebrow" value="{{ old('eyebrow', $h['eyebrow'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Headline" for="headline" name="headline">
                    <input type="text" name="headline" id="headline" value="{{ old('headline', $h['headline'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Subhead" for="subhead" name="subhead">
                    <textarea name="subhead" id="subhead" rows="3" required class="admin-textarea">{{ old('subhead', $h['subhead'] ?? '') }}</textarea>
                </x-admin.ui.group>
                <div class="admin-grid-2">
                    <x-admin.ui.group label="Primary CTA label" for="primary_cta_label" name="primary_cta_label">
                        <input type="text" name="primary_cta_label" id="primary_cta_label" value="{{ old('primary_cta_label', $cta['label'] ?? '') }}" required class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Primary CTA page slug" for="primary_cta_page_slug" name="primary_cta_page_slug">
                        <input type="text" name="primary_cta_page_slug" id="primary_cta_page_slug" value="{{ old('primary_cta_page_slug', $cta['params']['pageSlug'] ?? 'diploma-in-nursing') }}" required class="admin-input">
                    </x-admin.ui.group>
                </div>
                <div class="admin-grid-2">
                    <x-admin.ui.group label="Secondary CTA label" for="secondary_cta_label" name="secondary_cta_label">
                        <input type="text" name="secondary_cta_label" id="secondary_cta_label" value="{{ old('secondary_cta_label', $sec['label'] ?? '') }}" required class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Secondary CTA anchor (hash)" for="secondary_cta_hash" name="secondary_cta_hash">
                        <input type="text" name="secondary_cta_hash" id="secondary_cta_hash" value="{{ old('secondary_cta_hash', $sec['hash'] ?? 'programmes') }}" required class="admin-input">
                    </x-admin.ui.group>
                </div>
                <div class="admin-field-inset space-y-4">
                    <p class="admin-field-inset-title">Images <span class="font-normal text-thc-text/70">(stored under <span class="admin-code">storage/cohs/{{ $cohs->id }}</span>)</span></p>
                    <p class="admin-hint -mt-2">Current hero: <span class="admin-code">{{ $assets['hero_image'] ?? config('tenwek.cohs_landing.hero_image') }}</span> · welcome: <span class="admin-code">{{ $assets['welcome_image'] ?? config('tenwek.cohs_landing.welcome_image') }}</span> · logo: <span class="admin-code">{{ $assets['logo'] ?? config('tenwek.cohs_landing.logo') }}</span></p>
                    <x-admin.ui.group label="Replace hero image" for="hero_image" name="hero_image">
                        <input type="file" name="hero_image" id="hero_image" accept="image/*" class="admin-file-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Replace welcome section image" for="welcome_image" name="welcome_image">
                        <input type="file" name="welcome_image" id="welcome_image" accept="image/*" class="admin-file-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Replace header logo" for="logo" name="logo" hint="Raster or SVG upload; overrides config path.">
                        <input type="file" name="logo" id="logo" accept="image/*,.svg" class="admin-file-input">
                    </x-admin.ui.group>
                </div>
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save changes</button>
                    <a href="{{ route('admin.cohs.dashboard') }}" class="admin-btn-secondary">Back to CMS</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
