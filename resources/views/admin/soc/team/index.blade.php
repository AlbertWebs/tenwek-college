<x-layouts.admin header="SOC — Board &amp; management">
    <div class="admin-toolbar">
        <div class="admin-toolbar-actions">
            <a href="{{ route('admin.soc.team.create') }}" class="admin-btn-primary admin-btn-sm">Add person</a>
        </div>
        <a href="{{ route('admin.soc.dashboard') }}" class="admin-btn-ghost admin-btn-sm">← SOC CMS</a>
    </div>
    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Team</th>
                        <th>Role</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $m)
                        <tr>
                            <td class="font-medium text-thc-navy">{{ $m->name }}</td>
                            <td>{{ $m->team }}</td>
                            <td class="text-thc-text/80">{{ \Illuminate\Support\Str::limit($m->role_title, 48) }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.soc.team.edit', $m) }}" class="admin-link">Edit</a>
                                    <form method="post" action="{{ route('admin.soc.team.destroy', $m) }}" class="inline" onsubmit="return confirm('Delete?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table-empty">No team members yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $members->links() }}
</x-layouts.admin>
