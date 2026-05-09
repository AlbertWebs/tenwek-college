<x-layouts.admin header="COHS — News">
    <div class="admin-toolbar">
        <div class="admin-toolbar-actions">
            <a href="{{ route('admin.cohs.news.create') }}" class="admin-btn-primary admin-btn-sm">New post</a>
        </div>
        <a href="{{ route('admin.cohs.dashboard') }}" class="admin-btn-ghost admin-btn-sm">← COHS CMS</a>
    </div>
    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
            <table class="admin-table admin-table--zebra">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Published</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td class="font-medium text-thc-navy">{{ $post->title }}</td>
                            <td class="text-thc-text/75">{{ $post->published_at?->format('Y-m-d') ?? '—' }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="{{ route('admin.cohs.news.edit', $post) }}" class="admin-link">Edit</a>
                                    <form method="post" action="{{ route('admin.cohs.news.destroy', $post) }}" class="inline" onsubmit="return confirm('Delete post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="admin-table-empty">No news posts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $posts->links() }}
</x-layouts.admin>
