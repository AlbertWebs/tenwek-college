<x-layouts.admin header="SOC — Testimonials">
    <div class="admin-toolbar">
        <div class="admin-toolbar-actions">
            <a href="{{ route('admin.soc.testimonials.create') }}" class="admin-btn-primary admin-btn-sm">Add testimonial</a>
        </div>
        <a href="{{ route('admin.soc.dashboard') }}" class="admin-btn-ghost admin-btn-sm">← SOC CMS</a>
    </div>
    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Order</th>
                        <th>Published</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonials as $t)
                        <tr>
                            <td class="font-medium text-thc-navy">{{ $t->name }}</td>
                            <td class="text-thc-text/80">{{ $t->designation }}</td>
                            <td>{{ $t->sort_order }}</td>
                            <td>
                                @if ($t->is_published)
                                    <x-admin.ui.badge variant="success">Live</x-admin.ui.badge>
                                @else
                                    <x-admin.ui.badge variant="muted">Draft</x-admin.ui.badge>
                                @endif
                            </td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.soc.testimonials.edit', $t) }}" class="admin-link">Edit</a>
                                    <form method="post" action="{{ route('admin.soc.testimonials.destroy', $t) }}" class="inline" onsubmit="return confirm('Delete this testimonial?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="admin-table-empty">No testimonials — site will use config defaults.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $testimonials->links() }}
</x-layouts.admin>
