<x-layouts.admin header="COHS — Programmes band">
    <form method="post" action="{{ route('admin.cohs.programmes-band.update') }}" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="Kicker" for="kicker" name="kicker">
                    <input type="text" name="kicker" id="kicker" value="{{ old('kicker', $programmesBand['kicker'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Title" for="title" name="title">
                    <input type="text" name="title" id="title" value="{{ old('title', $programmesBand['title'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Intro" for="intro" name="intro">
                    <textarea name="intro" id="intro" rows="3" required class="admin-textarea">{{ old('intro', $programmesBand['intro'] ?? '') }}</textarea>
                </x-admin.ui.group>
                <p class="admin-hint text-sm">Programme cards (titles, summaries, links) are defined in <span class="admin-code">config/tenwek.php</span> or the Programmes JSON module on the dashboard.</p>
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
