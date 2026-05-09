<x-layouts.admin header="SOC — About (Karibu)">
    <form method="post" action="{{ route('admin.soc.about.update') }}" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="Kicker" for="kicker" name="kicker">
                    <input type="text" name="kicker" id="kicker" value="{{ old('kicker', $about['kicker'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Title" for="title" name="title">
                    <input type="text" name="title" id="title" value="{{ old('title', $about['title'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Lead" for="lead" name="lead">
                    <textarea name="lead" id="lead" rows="2" required class="admin-textarea">{{ old('lead', $about['lead'] ?? '') }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Paragraphs" for="paragraphs" name="paragraphs" hint="Blank line between each paragraph.">
                    <textarea name="paragraphs" id="paragraphs" rows="10" required class="admin-textarea font-mono text-sm">{{ old('paragraphs', implode("\n\n", $about['paragraphs'] ?? [])) }}</textarea>
                </x-admin.ui.group>
            </div>
            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save</button>
                    <a href="{{ route('admin.soc.dashboard') }}" class="admin-btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
