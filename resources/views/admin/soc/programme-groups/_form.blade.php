<x-admin.ui.group label="Heading" for="heading" name="heading">
    <input type="text" name="heading" id="heading" value="{{ old('heading', $programme_group->heading ?? '') }}" required class="admin-input">
</x-admin.ui.group>

<x-admin.ui.group label="Description" for="description" name="description" hint="Optional supporting text for this group.">
    <textarea name="description" id="description" rows="3" class="admin-textarea">{{ old('description', $programme_group->description ?? '') }}</textarea>
</x-admin.ui.group>

<x-admin.ui.group label="Sort order" for="sort_order" name="sort_order">
    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $programme_group->sort_order ?? 0) }}" min="0" class="admin-input">
</x-admin.ui.group>
