<x-layouts.admin header="SOC — JSON: {{ str_replace('_', ' ', $section) }}">
    <form method="post" action="{{ route('admin.soc.json.update', $section) }}" class="admin-page-wide">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <p class="text-sm leading-relaxed text-thc-text/85">
                Stored override merges with <span class="admin-code">config('tenwek.soc_landing.{{ $section }}')</span>.
                For <strong>testimonials</strong>, use the Testimonials CRUD for quotes; JSON here can adjust kicker/title only.
            </p>
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
