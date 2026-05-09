<x-layouts.admin header="COHS — Welcome section">
    <form method="post" action="{{ route('admin.cohs.welcome.update') }}" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="Kicker" for="kicker" name="kicker">
                    <input type="text" name="kicker" id="kicker" value="{{ old('kicker', $welcome['kicker'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Title" for="title" name="title">
                    <input type="text" name="title" id="title" value="{{ old('title', $welcome['title'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Lead" for="lead" name="lead">
                    <textarea name="lead" id="lead" rows="2" required class="admin-textarea">{{ old('lead', $welcome['lead'] ?? '') }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Paragraphs" for="paragraphs" name="paragraphs" hint="One paragraph per line (or blank line between).">
                    <textarea name="paragraphs" id="paragraphs" rows="10" required class="admin-textarea font-mono text-sm">{{ old('paragraphs', implode("\n", $welcome['paragraphs'] ?? [])) }}</textarea>
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
