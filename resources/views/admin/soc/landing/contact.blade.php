@php
    $socialRows = 8;
    if (is_array(old('social_label'))) {
        $socialPairs = [];
        for ($i = 0; $i < $socialRows; $i++) {
            $socialPairs[] = [
                'label' => old('social_label.'.$i, ''),
                'url' => old('social_url.'.$i, ''),
            ];
        }
    } else {
        $fromContact = $contact['social_links'] ?? [];
        $socialPairs = array_values($fromContact);
        while (count($socialPairs) < $socialRows) {
            $socialPairs[] = ['label' => '', 'url' => ''];
        }
        $socialPairs = array_slice($socialPairs, 0, $socialRows);
    }
@endphp
<x-layouts.admin header="SOC — Contact block">
    <form method="post" action="{{ route('admin.soc.contact.update') }}" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="Kicker" for="kicker" name="kicker">
                    <input type="text" name="kicker" id="kicker" value="{{ old('kicker', $contact['kicker'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Email" for="email" name="email">
                    <input type="email" name="email" id="email" value="{{ old('email', $contact['email'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Address lines" for="location_lines" name="location_lines" hint="One line per row; saved as structured address.">
                    <textarea name="location_lines" id="location_lines" rows="5" required class="admin-textarea">{{ old('location_lines', implode("\n", $contact['location_lines'] ?? [])) }}</textarea>
                </x-admin.ui.group>

                <x-admin.ui.group label="Phone lines" for="phones" name="phones" hint="One line per number.">
                    <textarea name="phones" id="phones" rows="4" required class="admin-textarea">{{ old('phones', implode("\n", $contact['phones'] ?? [])) }}</textarea>
                </x-admin.ui.group>

                <x-admin.ui.group label="Office hours" for="office_hours_lines" name="office_hours_lines" hint="Optional; one line per row.">
                    <textarea name="office_hours_lines" id="office_hours_lines" rows="4" class="admin-textarea">{{ old('office_hours_lines', implode("\n", $contact['office_hours_lines'] ?? [])) }}</textarea>
                </x-admin.ui.group>

                <x-admin.ui.group label="Map embed URL" for="map_embed_url" name="map_embed_url" hint="Saved to site assets and shown on the landing contact section. Leave blank to remove.">
                    <input type="url" name="map_embed_url" id="map_embed_url" value="{{ old('map_embed_url', $mapEmbed) }}" class="admin-input" placeholder="https://…">
                </x-admin.ui.group>

                <div class="admin-field-inset">
                    <p class="admin-field-inset-title">Social links</p>
                    <p class="admin-hint !mt-0">Optional. Up to eight pairs; both label and URL are required for each link you want to show.</p>
                    @foreach ($socialPairs as $idx => $pair)
                        <div class="admin-grid-2 !gap-3">
                            <div>
                                <label class="sr-only" for="social_label_{{ $idx }}">Social label {{ $idx + 1 }}</label>
                                <input type="text" name="social_label[]" id="social_label_{{ $idx }}" value="{{ $pair['label'] ?? '' }}" class="admin-input" placeholder="Label (e.g. Facebook)" autocomplete="off">
                            </div>
                            <div>
                                <label class="sr-only" for="social_url_{{ $idx }}">Social URL {{ $idx + 1 }}</label>
                                <input type="url" name="social_url[]" id="social_url_{{ $idx }}" value="{{ $pair['url'] ?? '' }}" class="admin-input" placeholder="https://…" autocomplete="off">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save</button>
                    <a href="{{ route('admin.soc.dashboard') }}" class="admin-btn-secondary">Back to CMS</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
