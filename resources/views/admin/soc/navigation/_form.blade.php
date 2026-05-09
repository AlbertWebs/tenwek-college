<x-admin.ui.group label="Parent (for submenu link)" for="parent_id" name="parent_id" hint="Leave empty for a top-level item.">
    <select name="parent_id" id="parent_id" class="admin-select">
        <option value="">— Top level (dropdown header or single link) —</option>
        @foreach ($parents as $p)
            <option value="{{ $p->id }}" @selected(old('parent_id', $navigation->parent_id ?? null) == $p->id)>{{ $p->label }}</option>
        @endforeach
    </select>
</x-admin.ui.group>

<x-admin.ui.group label="Label" for="label" name="label">
    <input type="text" name="label" id="label" value="{{ old('label', $navigation->label ?? '') }}" required class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Internal page slug" for="page_slug" name="page_slug" hint="e.g. admissions — used when no external URL or special route.">
    <input type="text" name="page_slug" id="page_slug" value="{{ old('page_slug', $navigation->page_slug ?? '') }}" placeholder="e.g. admissions" class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Special route" for="route_name" name="route_name">
    <select name="route_name" id="route_name" class="admin-select">
        <option value="">—</option>
        <option value="soc.register" @selected(old('route_name', $navigation->route_name ?? '') === 'soc.register')>Register online (soc.register)</option>
    </select>
</x-admin.ui.group>

<x-admin.ui.group label="External URL" for="external_url" name="external_url">
    <input type="url" name="external_url" id="external_url" value="{{ old('external_url', $navigation->external_url ?? '') }}" class="admin-input" placeholder="https://">
</x-admin.ui.group>

<x-admin.ui.group label="Sort order" for="sort_order" name="sort_order">
    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $navigation->sort_order ?? 0) }}" min="0" class="admin-input">
</x-admin.ui.group>

<div class="flex flex-wrap gap-6">
    <label class="admin-check-row">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $navigation->is_active ?? true)) class="admin-checkbox">
        <span>Active</span>
    </label>
    <label class="admin-check-row">
        <input type="hidden" name="is_highlight" value="0">
        <input type="checkbox" name="is_highlight" value="1" @checked(old('is_highlight', $navigation->is_highlight ?? false)) class="admin-checkbox">
        <span>Highlight style</span>
    </label>
    <label class="admin-check-row">
        <input type="hidden" name="open_new_tab" value="0">
        <input type="checkbox" name="open_new_tab" value="1" @checked(old('open_new_tab', $navigation->open_new_tab ?? false)) class="admin-checkbox">
        <span>Open in new tab</span>
    </label>
</div>
