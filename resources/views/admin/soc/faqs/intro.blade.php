<x-layouts.admin header="SOC — FAQ page intro" title="FAQ intro | SOC CMS | {{ config('tenwek.name') }}">
    <form method="post" action="{{ route('admin.soc.faqs.intro.update') }}" class="admin-page-wide">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <p class="text-sm leading-relaxed text-thc-text/85">
                This text appears above the accordions on
                <a href="{{ url('/soc/faqs') }}" class="admin-link" target="_blank" rel="noopener">/soc/faqs</a>.
                The large title is the CMS page title, not the kicker below.
            </p>
            @if ($faqsPage)
                <p class="mt-3 text-sm">
                    <a href="{{ route('admin.soc.pages.edit', $faqsPage) }}" class="admin-link font-medium">Edit page title, excerpt &amp; SEO →</a>
                </p>
            @endif

            <div class="admin-form-stack mt-8 max-w-3xl">
                <x-admin.ui.group label="Kicker" for="kicker" name="kicker" hint="Small label above the main page title.">
                    <input type="text" name="kicker" id="kicker" value="{{ old('kicker', $kicker) }}" class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Intro" for="intro" name="intro" hint="Paragraph under the page title.">
                    <textarea name="intro" id="intro" rows="5" class="admin-textarea">{{ old('intro', $intro) }}</textarea>
                </x-admin.ui.group>
            </div>

            <div class="admin-actions admin-actions-sticky mt-8 max-w-3xl">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save intro</button>
                    <a href="{{ route('admin.soc.faqs.index') }}" class="admin-btn-secondary">Back to FAQs</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
