<x-layouts.admin header="COHS — JSON: {{ str_replace('_', ' ', $section) }}">
    <form method="post" action="{{ route('admin.cohs.json.update', $section) }}" class="admin-page-wide">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <p class="mb-4 text-sm text-thc-text/80">
                Stored override merges with <span class="admin-code">config('tenwek.cohs_landing.{{ $section }}')</span>.
            </p>
            <x-admin.ui.group label="JSON payload" for="json" name="json">
                <textarea name="json" id="json" rows="28" class="admin-textarea font-mono text-xs" required>{{ $json }}</textarea>
            </x-admin.ui.group>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save</button>
                    <a href="{{ route('admin.cohs.dashboard') }}" class="admin-btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
