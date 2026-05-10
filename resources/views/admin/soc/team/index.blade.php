<x-layouts.admin header="SOC — Board &amp; management">
    <p class="mb-4 max-w-3xl text-sm leading-relaxed text-thc-text/85">
        People listed here (when published) drive the
        <a href="{{ url('/soc/board-and-management-team') }}" class="admin-link" target="_blank" rel="noopener">board &amp; management</a>
        page. Edit a row to upload or change a portrait; optional “Faculty / staff” entries use the same photo field if you show them elsewhere.
    </p>
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
                        <th class="w-16">Photo</th>
                        <th>Name</th>
                        <th>Team</th>
                        <th>Role</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $m)
                        <tr>
                            <td>
                                @if (filled($m->image_path))
                                    @php($thumb = \App\Support\Soc\SocLandingRepository::publicMediaUrl($m->image_path) ?? asset($m->image_path))
                                    <img src="{{ $thumb }}" alt="" class="h-10 w-8 rounded object-cover ring-1 ring-thc-navy/10" width="32" height="40" loading="lazy" decoding="async">
                                @else
                                    <span class="text-thc-text/40">—</span>
                                @endif
                            </td>
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
                            <td colspan="5" class="admin-table-empty">No team members yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $members->links() }}
</x-layouts.admin>
