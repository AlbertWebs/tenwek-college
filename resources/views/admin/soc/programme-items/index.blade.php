<x-layouts.admin header="Programmes: {{ $programme_group->heading }}">
    @if (session('status'))
        <div class="admin-alert-success mb-4" role="status">{{ session('status') }}</div>
    @endif
    <div class="admin-toolbar">
        <div class="admin-toolbar-actions">
            <a href="{{ route('admin.soc.programme-groups.items.create', $programme_group) }}" class="admin-btn-primary admin-btn-sm">Add programme</a>
            <a href="{{ route('admin.soc.programme-groups.index') }}" class="admin-btn-secondary admin-btn-sm">All groups</a>
        </div>
    </div>
    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Published</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $it)
                        <tr>
                            <td class="font-medium text-thc-navy">{{ $it->title }}</td>
                            <td class="font-mono text-xs text-thc-text/80">{{ $it->slug }}</td>
                            <td>
                                @if ($it->is_published)
                                    <x-admin.ui.badge variant="success">Live</x-admin.ui.badge>
                                @else
                                    <x-admin.ui.badge variant="muted">Draft</x-admin.ui.badge>
                                @endif
                            </td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.soc.programme-groups.items.edit', [$programme_group, $it]) }}" class="admin-link">Edit</a>
                                    <form method="post" action="{{ route('admin.soc.programme-groups.items.destroy', [$programme_group, $it]) }}" class="inline" onsubmit="return confirm('Delete this programme?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table-empty">No programmes in this group yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $items->links() }}
</x-layouts.admin>
