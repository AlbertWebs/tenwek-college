<x-layouts.admin header="SOC — Mission &amp; vision">
    <form method="post" action="{{ route('admin.soc.mission-vision.update') }}" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card space-y-6 p-6 sm:p-8">
            <fieldset class="admin-field-inset space-y-4">
                <legend class="admin-field-inset-title px-1">Vision</legend>
                <x-admin.ui.group label="Title" for="vision_title" name="vision_title">
                    <input type="text" name="vision_title" id="vision_title" value="{{ old('vision_title', $vision['title'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Text" for="vision_text" name="vision_text">
                    <textarea name="vision_text" id="vision_text" rows="4" required class="admin-textarea">{{ old('vision_text', $vision['text'] ?? '') }}</textarea>
                </x-admin.ui.group>
            </fieldset>
            <fieldset class="admin-field-inset space-y-4">
                <legend class="admin-field-inset-title px-1">Mission</legend>
                <x-admin.ui.group label="Title" for="mission_title" name="mission_title">
                    <input type="text" name="mission_title" id="mission_title" value="{{ old('mission_title', $mission['title'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Text" for="mission_text" name="mission_text">
                    <textarea name="mission_text" id="mission_text" rows="4" required class="admin-textarea">{{ old('mission_text', $mission['text'] ?? '') }}</textarea>
                </x-admin.ui.group>
            </fieldset>
            <div class="admin-actions">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save</button>
                    <a href="{{ route('admin.soc.dashboard') }}" class="admin-btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
