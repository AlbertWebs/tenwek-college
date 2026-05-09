@php
    $boardJson = old('board_json', json_encode($about['board'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
@endphp
<x-layouts.admin header="COHS — About us page">
    <form method="post" action="{{ route('admin.cohs.about-us.update') }}" enctype="multipart/form-data" class="admin-page-wide">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack max-w-3xl">
                <x-admin.ui.group label="Kicker" for="kicker" name="kicker">
                    <input type="text" name="kicker" id="kicker" value="{{ old('kicker', $about['kicker'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Hero headline" for="headline" name="headline">
                    <input type="text" name="headline" id="headline" value="{{ old('headline', $about['headline'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="History heading" for="history_heading" name="history_heading">
                    <input type="text" name="history_heading" id="history_heading" value="{{ old('history_heading', $about['history_heading'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="History paragraphs" for="history_paragraphs" name="history_paragraphs">
                    <textarea name="history_paragraphs" id="history_paragraphs" rows="8" required class="admin-textarea">{{ old('history_paragraphs', implode("\n", $about['history_paragraphs'] ?? [])) }}</textarea>
                </x-admin.ui.group>
                <div class="admin-grid-2">
                    <x-admin.ui.group label="History image path (storage or public)" for="history_image" name="history_image">
                        <input type="text" name="history_image" id="history_image" value="{{ old('history_image', $about['history_image'] ?? '') }}" class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="History image alt" for="history_image_alt" name="history_image_alt">
                        <input type="text" name="history_image_alt" id="history_image_alt" value="{{ old('history_image_alt', $about['history_image_alt'] ?? '') }}" class="admin-input">
                    </x-admin.ui.group>
                </div>
                <x-admin.ui.group label="Upload history image" for="history_image_upload" name="history_image_upload">
                    <input type="file" name="history_image_upload" id="history_image_upload" accept="image/*" class="admin-file-input">
                </x-admin.ui.group>
                <div class="admin-grid-2">
                    <x-admin.ui.group label="Vision title" for="vision_title" name="vision_title">
                        <input type="text" name="vision_title" id="vision_title" value="{{ old('vision_title', $about['vision']['title'] ?? '') }}" required class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Mission title" for="mission_title" name="mission_title">
                        <input type="text" name="mission_title" id="mission_title" value="{{ old('mission_title', $about['mission']['title'] ?? '') }}" required class="admin-input">
                    </x-admin.ui.group>
                </div>
                <x-admin.ui.group label="Vision text" for="vision_text" name="vision_text">
                    <textarea name="vision_text" id="vision_text" rows="3" required class="admin-textarea">{{ old('vision_text', $about['vision']['text'] ?? '') }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Mission text" for="mission_text" name="mission_text">
                    <textarea name="mission_text" id="mission_text" rows="3" required class="admin-textarea">{{ old('mission_text', $about['mission']['text'] ?? '') }}</textarea>
                </x-admin.ui.group>
                <div class="admin-grid-2">
                    <x-admin.ui.group label="Motto title" for="motto_title" name="motto_title">
                        <input type="text" name="motto_title" id="motto_title" value="{{ old('motto_title', $about['motto']['title'] ?? '') }}" required class="admin-input">
                    </x-admin.ui.group>
                </div>
                <x-admin.ui.group label="Motto text" for="motto_text" name="motto_text">
                    <textarea name="motto_text" id="motto_text" rows="2" required class="admin-textarea">{{ old('motto_text', $about['motto']['text'] ?? '') }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Board section heading" for="board_section_heading" name="board_section_heading">
                    <input type="text" name="board_section_heading" id="board_section_heading" value="{{ old('board_section_heading', $about['board_section_heading'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Board intro" for="board_intro" name="board_intro">
                    <textarea name="board_intro" id="board_intro" rows="3" required class="admin-textarea">{{ old('board_intro', $about['board_intro'] ?? '') }}</textarea>
                </x-admin.ui.group>
                <x-admin.ui.group label="Board list heading" for="board_heading" name="board_heading">
                    <input type="text" name="board_heading" id="board_heading" value="{{ old('board_heading', $about['board_heading'] ?? '') }}" required class="admin-input">
                </x-admin.ui.group>
                <x-admin.ui.group label="Board members (JSON)" for="board_json" name="board_json" hint='[ { "name": "", "role": "", "highlight": true }, ... ]'>
                    <textarea name="board_json" id="board_json" rows="18" required class="admin-textarea font-mono text-xs">{{ $boardJson }}</textarea>
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
