<x-layouts.admin header="SOC — Events">
    <div class="admin-toolbar">
        <div class="admin-toolbar-actions">
            <a href="{{ route('admin.soc.events.create') }}" class="admin-btn-primary admin-btn-sm">New event</a>
        </div>
        <a href="{{ route('schools.events.index', $soc) }}" class="admin-btn-ghost admin-btn-sm" target="_blank" rel="noopener">View public</a>
        <a href="{{ route('admin.soc.dashboard') }}" class="admin-btn-ghost admin-btn-sm">← SOC CMS</a>
    </div>
    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Starts</th>
                        <th>Published</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $ev)
                        <tr>
                            <td class="font-medium text-thc-navy">{{ $ev->title }}</td>
                            <td class="text-sm text-thc-text/80">{{ $ev->starts_at?->format('M j, Y g:i a') }}</td>
                            <td class="text-sm text-thc-text/80">
                                @if ($ev->published_at === null)
                                    <span class="text-thc-text/50">Draft</span>
                                @elseif ($ev->published_at->isFuture())
                                    <span class="text-amber-800">Scheduled</span>
                                @else
                                    <span class="text-emerald-700">Live</span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.soc.events.edit', $ev) }}" class="admin-link">Edit</a>
                                    <form method="post" action="{{ route('admin.soc.events.destroy', $ev) }}" class="inline" onsubmit="return confirm('Delete this event?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table-empty">No events yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $events->links() }}
</x-layouts.admin>
