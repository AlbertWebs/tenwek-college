<x-layouts.admin header="Downloads" :breadcrumbs="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Downloads']]">
    <div class="admin-toolbar">
        <form method="get" class="flex flex-wrap items-center gap-2">
            @if(auth()->user()->isSuperAdmin())
                <select name="school" class="admin-select" onchange="this.form.submit()">
                    <option value="">All schools</option>
                    @foreach($schools as $s)
                        <option value="{{ $s->slug }}" @selected(request('school') === $s->slug)>{{ $s->name }}</option>
                    @endforeach
                </select>
            @endif
        </form>
        @can('create', App\Models\Download::class)
            <a href="{{ route('admin.downloads.create') }}" class="admin-btn-primary">New download</a>
        @endcan
    </div>

    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th class="hidden sm:table-cell">School</th>
                        <th class="hidden md:table-cell">Category</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-thc-navy/8">
                    @foreach($downloads as $dl)
                        <tr>
                            <td class="font-medium text-thc-navy">{{ $dl->title }}</td>
                            <td class="hidden text-thc-text/90 sm:table-cell">{{ $dl->school?->name ?? '—' }}</td>
                            <td class="hidden text-thc-text/90 md:table-cell">{{ $dl->category?->name ?? '—' }}</td>
                            <td>
                                @if($dl->is_active && $dl->published_at && $dl->published_at->isPast())
                                    <span class="rounded-full bg-thc-royal/12 px-2 py-0.5 text-xs font-medium text-thc-navy">Live</span>
                                @else
                                    <span class="rounded-full bg-thc-navy/[0.06] px-2 py-0.5 text-xs font-medium text-thc-text/80">Draft</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @can('update', $dl)
                                    <a href="{{ route('admin.downloads.edit', $dl) }}" class="font-medium text-thc-royal hover:underline">Edit</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $downloads->links() }}
    </div>
</x-layouts.admin>
