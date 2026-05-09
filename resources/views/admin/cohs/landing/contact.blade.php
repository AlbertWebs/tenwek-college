@php
    $phoneJson = old('phone_rows_json', json_encode($contactPage['phone_rows'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
@endphp
<x-layouts.admin header="COHS — Contact page">
    <form method="post" action="{{ route('admin.cohs.contact.update') }}" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="Hero kicker" for="hero_kicker" name="hero_kicker">
                    <input type="text" name="hero_kicker" id="hero_kicker" value="{{ old('hero_kicker', $contactPage['hero_kicker'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <div class="admin-grid-2">
                    <x-admin.ui.group label="Headline" for="headline" name="headline">
                        <input type="text" name="headline" id="headline" value="{{ old('headline', $contactPage['headline'] ?? '') }}" required class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Headline accent (italic)" for="headline_accent" name="headline_accent">
                        <input type="text" name="headline_accent" id="headline_accent" value="{{ old('headline_accent', $contactPage['headline_accent'] ?? '') }}" class="admin-input">
                    </x-admin.ui.group>
                </div>
                <x-admin.ui.group label="Lead paragraph" for="lead" name="lead">
                    <textarea name="lead" id="lead" rows="2" required class="admin-textarea">{{ old('lead', $contactPage['lead'] ?? '') }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Intro (use :email for mailto placeholder)" for="intro" name="intro">
                    <textarea name="intro" id="intro" rows="3" required class="admin-textarea">{{ old('intro', $contactPage['intro'] ?? '') }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Office email" for="email" name="email">
                    <input type="email" name="email" id="email" value="{{ old('email', $contactPage['email'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Office title" for="office_title" name="office_title">
                    <input type="text" name="office_title" id="office_title" value="{{ old('office_title', $contactPage['office_title'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Address lines" for="address_lines" name="address_lines" hint="One line per row.">
                    <textarea name="address_lines" id="address_lines" rows="4" required class="admin-textarea">{{ old('address_lines', implode("\n", $contactPage['address_lines'] ?? [])) }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Phone rows (JSON)" for="phone_rows_json" name="phone_rows_json" hint='Array of { "label": "Phone", "numbers": [ {"display": "...", "tel": "+254..."} ] }'>
                    <textarea name="phone_rows_json" id="phone_rows_json" rows="14" required class="admin-textarea font-mono text-xs">{{ $phoneJson }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Map embed URL" for="map_embed_url" name="map_embed_url">
                    <input type="url" name="map_embed_url" id="map_embed_url" value="{{ old('map_embed_url', $mapEmbed) }}" class="admin-input" placeholder="https://maps.google.com/...">
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
