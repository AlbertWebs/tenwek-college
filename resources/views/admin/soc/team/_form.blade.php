<x-admin.ui.group label="Team" for="team" name="team">
    <select name="team" id="team" required class="admin-select">
        @foreach ([\App\Models\SocTeamMember::TEAM_BOARD => 'Board', \App\Models\SocTeamMember::TEAM_MANAGEMENT => 'Management', \App\Models\SocTeamMember::TEAM_FACULTY => 'Faculty / staff'] as $val => $label)
            <option value="{{ $val }}" @selected(old('team', $team->team ?? '') === $val)>{{ $label }}</option>
        @endforeach
    </select>
</x-admin.ui.group>

<x-admin.ui.group label="Name" for="name" name="name">
    <input type="text" name="name" id="name" value="{{ old('name', $team->name ?? '') }}" required class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Role title" for="role_title" name="role_title">
    <input type="text" name="role_title" id="role_title" value="{{ old('role_title', $team->role_title ?? '') }}" required class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Bio" for="bio" name="bio" hint="Optional short biography.">
    <textarea name="bio" id="bio" rows="4" class="admin-textarea">{{ old('bio', $team->bio ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Sort order" for="sort_order" name="sort_order">
    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $team->sort_order ?? 0) }}" min="0" class="admin-input">
</x-admin.ui.group>

<div class="flex flex-wrap gap-6">
    <label class="admin-check-row">
        <input type="hidden" name="highlight" value="0">
        <input type="checkbox" name="highlight" value="1" @checked(old('highlight', $team->highlight ?? false)) class="admin-checkbox">
        <span>Highlight card</span>
    </label>
    <label class="admin-check-row">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $team->is_published ?? true)) class="admin-checkbox">
        <span>Published</span>
    </label>
</div>

<x-admin.ui.group label="Photo" for="image" name="image" hint="Shown on /soc/board-and-management-team/ (and anywhere else this person appears). Stored under public disk; path like soc/{id}/team/….">
    <input type="file" name="image" id="image" accept="image/*" class="admin-file-input">
    @if ($team->exists && $team->image_path)
        @php($imgUrl = \App\Support\Soc\SocLandingRepository::publicMediaUrl($team->image_path) ?? asset($team->image_path))
        <div class="mt-3 flex flex-wrap items-end gap-4">
            <img src="{{ $imgUrl }}" alt="" class="h-32 w-24 rounded-lg border border-thc-navy/10 object-cover shadow-sm" width="96" height="128" loading="lazy" decoding="async">
            <p class="admin-hint">Current file: <span class="admin-code">{{ $team->image_path }}</span> — choose a new file above to replace it.</p>
        </div>
    @endif
</x-admin.ui.group>
