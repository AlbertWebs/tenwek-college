<x-layouts.admin header="SOC — Academic programme groups">
    @if (session('status'))
        <div class="admin-alert-success mb-4" role="status">{{ session('status') }}</div>
    @endif
    <div class="admin-toolbar">
        <p class="admin-toolbar-note">When groups exist here, they replace the default programme list from config for the public site.</p>
        <div class="admin-toolbar-actions">
            <a href="{{ route('admin.soc.programme-groups.create') }}" class="admin-btn-primary admin-btn-sm">New group</a>
        </div>
    </div>
    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th>Heading</th>
                        <th>Programmes</th>
                        <th>Order</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($groups as $g)
                        <tr>
                            <td class="font-medium text-thc-navy">{{ $g->heading }}</td>
                            <td>{{ number_format($g->items_count) }}</td>
                            <td>{{ $g->sort_order }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.soc.programme-groups.items.index', $g) }}" class="admin-link">Programmes</a>
                                    <a href="{{ route('admin.soc.programme-groups.edit', $g) }}" class="admin-link">Edit</a>
                                    <form method="post" action="{{ route('admin.soc.programme-groups.destroy', $g) }}" class="inline" onsubmit="return confirm('Delete this group and all its programmes?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table-empty">
                                No groups yet. Create one, or run <span class="admin-code">php artisan soc:import-programmes</span> to seed from config.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $groups->links() }}
    <p class="mt-6 text-sm"><a href="{{ route('admin.soc.dashboard') }}" class="admin-link">← SOC CMS</a></p>
</x-layouts.admin>
