<x-layouts.admin header="COHS — Utility top bar">
    <form method="post" action="{{ route('admin.cohs.top-bar.update') }}" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="Email" for="email" name="email">
                    <input type="email" name="email" id="email" value="{{ old('email', $topBar['email'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Call label" for="call_prefix" name="call_prefix">
                    <input type="text" name="call_prefix" id="call_prefix" value="{{ old('call_prefix', $topBar['call_prefix'] ?? 'Call:') }}" class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Call display" for="call_display" name="call_display">
                    <input type="text" name="call_display" id="call_display" value="{{ old('call_display', $topBar['call_display'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Tel (dialable)" for="call_tel" name="call_tel">
                    <input type="text" name="call_tel" id="call_tel" value="{{ old('call_tel', $topBar['call_tel'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Portal button label" for="portal_label" name="portal_label">
                    <input type="text" name="portal_label" id="portal_label" value="{{ old('portal_label', $topBar['portal_label'] ?? '') }}" class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Portal URL" for="portal_url" name="portal_url">
                    <input type="url" name="portal_url" id="portal_url" value="{{ old('portal_url', $topBar['portal_url'] ?? '') }}" class="admin-input" placeholder="https://">
                </x-admin.ui.group>
                <x-admin.ui.group label="Off-campus application URL" for="off_campus_application_url" name="off_campus_application_url" hint="Used for Application → Off-campus link and redirects.">
                    <input type="url" name="off_campus_application_url" id="off_campus_application_url" value="{{ old('off_campus_application_url', $offCampus) }}" class="admin-input" placeholder="https://">
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
