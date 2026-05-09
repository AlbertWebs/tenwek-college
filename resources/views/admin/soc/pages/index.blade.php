<x-layouts.admin header="SOC — Pages">
    <div class="admin-toolbar">
        <a href="{{ route('admin.soc.dashboard') }}" class="admin-btn-ghost admin-btn-sm">← SOC CMS</a>
    </div>
    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pages as $page)
                        <tr>
                            <td class="font-medium text-thc-navy">{{ $page->title }}</td>
                            <td class="font-mono text-xs">{{ $page->slug }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.soc.pages.edit', $page) }}" class="admin-link">Edit content &amp; SEO</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="admin-table-empty">No pages.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $pages->links() }}
</x-layouts.admin>
