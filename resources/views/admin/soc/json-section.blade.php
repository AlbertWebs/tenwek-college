<x-layouts.admin header="SOC — JSON: {{ str_replace('_', ' ', $section) }}">
    <form method="post" action="{{ route('admin.soc.json.update', $section) }}" class="admin-page-wide">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <p class="text-sm leading-relaxed text-thc-text/85">
                Stored override merges with <span class="admin-code">config('tenwek.soc_landing.{{ $section }}')</span>.
                For <strong>testimonials</strong>, use the Testimonials CRUD for quotes; JSON here can adjust kicker/title only.
            </p>
            @if ($section === 'faqs')
                <p class="mt-4 rounded-lg border border-thc-royal/20 bg-thc-royal/[0.06] px-4 py-3 text-sm leading-relaxed text-thc-text/90">
                    <strong class="text-thc-navy">FAQs content:</strong>
                    prefer the structured
                    <a href="{{ route('admin.soc.faqs.index') }}" class="admin-link font-medium">FAQs admin</a>
                    (kicker, intro, accordion items). Edit raw JSON here only if you need the full object in one place.
                </p>
            @endif
            @if ($section === 'board_and_management')
                <p class="mt-4 rounded-lg border border-thc-royal/20 bg-thc-royal/[0.06] px-4 py-3 text-sm leading-relaxed text-thc-text/90">
                    <strong class="text-thc-navy">Board &amp; management portraits:</strong>
                    upload each person’s photo under
                    <a href="{{ route('admin.soc.team.index') }}" class="admin-link font-medium">Board &amp; management team</a>
                    (edit the row → Photo). When any board or management members are published there, those lists replace the
                    <span class="admin-code">board</span> / <span class="admin-code">management</span> arrays from this JSON on the public page.
                </p>
            @endif
            <div class="admin-form-stack mt-6 max-w-4xl">
                <x-admin.ui.group label="JSON object" for="json" name="json">
                    <textarea name="json" id="json" rows="24" class="admin-textarea font-mono text-xs leading-relaxed">{{ $json }}</textarea>
                </x-admin.ui.group>
            </div>
            <div class="admin-actions admin-actions-sticky mt-8 max-w-4xl">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save override</button>
                    <a href="{{ route('admin.soc.dashboard') }}" class="admin-btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
