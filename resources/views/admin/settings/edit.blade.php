<x-layouts.admin
    header="Institution settings"
    :breadcrumbs="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Settings']]"
>
    <form method="post" action="{{ route('admin.settings.update') }}" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <p class="text-sm leading-relaxed text-thc-text/85">These values override the defaults from configuration and <code class="admin-code">.env</code> for public-facing copy (site name, tagline, contact line). Leave a field empty to keep using the file default for that key.</p>

            <div class="admin-form-stack mt-6">
                <x-admin.ui.group label="Site display name" for="site_name" name="site_name" hint="Shown in titles and branding when set.">
                    <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $general['site_name']) }}" class="admin-input" placeholder="{{ config('tenwek.name') }}">
                </x-admin.ui.group>

                <x-admin.ui.group label="Tagline" for="tagline" name="tagline" hint="Short institutional line; feeds default meta description when Global SEO description is empty.">
                    <textarea name="tagline" id="tagline" rows="3" class="admin-textarea" placeholder="{{ \Illuminate\Support\Str::limit(config('tenwek.tagline'), 80) }}">{{ old('tagline', $general['tagline']) }}</textarea>
                </x-admin.ui.group>

                <x-admin.ui.group label="Legal / formal name" for="institution_legal" name="institution_legal" hint="Used in title patterns and structured data.">
                    <input type="text" name="institution_legal" id="institution_legal" value="{{ old('institution_legal', $general['institution_legal']) }}" class="admin-input" placeholder="{{ config('tenwek.institution_legal') }}">
                </x-admin.ui.group>

                <x-admin.ui.group label="Public email" for="email_public" name="email_public">
                    <input type="email" name="email_public" id="email_public" value="{{ old('email_public', $general['email_public']) }}" class="admin-input" placeholder="{{ config('tenwek.email_public') }}">
                </x-admin.ui.group>

                <x-admin.ui.group label="Public phone" for="phone" name="phone">
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $general['phone']) }}" class="admin-input" placeholder="{{ config('tenwek.phone') }}">
                </x-admin.ui.group>
            </div>

            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save settings</button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
