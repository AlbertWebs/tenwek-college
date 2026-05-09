@php
    $highlightsJson = old('highlights_json', json_encode($socialLife['highlights'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
@endphp
<x-layouts.admin header="COHS — Social life">
    <form method="post" action="{{ route('admin.cohs.social-life.update') }}" enctype="multipart/form-data" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="Kicker" for="kicker" name="kicker">
                    <input type="text" name="kicker" id="kicker" value="{{ old('kicker', $socialLife['kicker'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <div class="admin-grid-2">
                    <x-admin.ui.group label="Headline (before)" for="headline_before" name="headline_before">
                        <input type="text" name="headline_before" id="headline_before" value="{{ old('headline_before', $socialLife['headline_before'] ?? '') }}" required class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Headline (emphasis)" for="headline_emphasis" name="headline_emphasis">
                        <input type="text" name="headline_emphasis" id="headline_emphasis" value="{{ old('headline_emphasis', $socialLife['headline_emphasis'] ?? '') }}" required class="admin-input">
                    </x-admin.ui.group>
                </div>
                <x-admin.ui.group label="Pull quote" for="pull_quote" name="pull_quote">
                    <input type="text" name="pull_quote" id="pull_quote" value="{{ old('pull_quote', $socialLife['pull_quote'] ?? '') }}" class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Paragraphs" for="paragraphs" name="paragraphs">
                    <textarea name="paragraphs" id="paragraphs" rows="8" required class="admin-textarea">{{ old('paragraphs', implode("\n", $socialLife['paragraphs'] ?? [])) }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Highlights (JSON array)" for="highlights_json" name="highlights_json" hint='[ { "title": "", "description": "" }, ... ]'>
                    <textarea name="highlights_json" id="highlights_json" rows="16" required class="admin-textarea font-mono text-xs">{{ $highlightsJson }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Replace hero image" for="hero_image" name="hero_image">
                    <input type="file" name="hero_image" id="hero_image" accept="image/*" class="admin-file-input">
                    @if(! empty($socialLife['hero_image']))
                        <p class="admin-hint">Current: <span class="admin-code">{{ $socialLife['hero_image'] }}</span></p>
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
