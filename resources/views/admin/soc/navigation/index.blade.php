<x-layouts.admin header="SOC — Navigation">
    <div class="admin-toolbar">
        <p class="admin-toolbar-note">If the list is empty, the site uses the default menu from config.</p>
        <div class="admin-toolbar-actions">
            <a href="{{ route('admin.soc.navigation.create') }}" class="admin-btn-primary admin-btn-sm">Add item</a>
        </div>
    </div>
    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Parent</th>
                        <th>Target</th>
                        <th>Ord</th>
                        <th>On</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td class="font-medium text-thc-navy">{{ $item->label }}</td>
                            <td class="text-thc-text/75">{{ $item->parent?->label ?? '—' }}</td>
                            <td class="text-xs text-thc-text/80">
                                @if ($item->external_url)
                                    {{ \Illuminate\Support\Str::limit($item->external_url, 40) }}
                                @elseif ($item->route_name)
                                    {{ $item->route_name }}
                                @else
                                    {{ $item->page_slug ?? '—' }}
                                @endif
                            </td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                @if ($item->is_active)
                                    <x-admin.ui.badge variant="success">Y</x-admin.ui.badge>
                                @else
                                    <x-admin.ui.badge variant="muted">N</x-admin.ui.badge>
                                @endif
                            </td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.soc.navigation.edit', $item) }}" class="admin-link">Edit</a>
                                    <form method="post" action="{{ route('admin.soc.navigation.destroy', $item) }}" class="inline" onsubmit="return confirm('Delete?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-table-empty">No custom navigation items.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $items->links() }}
    <p class="mt-6"><a href="{{ route('admin.soc.dashboard') }}" class="admin-link text-sm">← SOC CMS</a></p>
</x-layouts.admin>
