<x-layouts.admin header="SOC — Media library">
    <div class="admin-card mb-10 max-w-xl p-6">
        <form method="post" action="{{ route('admin.soc.media.store') }}" enctype="multipart/form-data" class="admin-form-stack-tight">
            @csrf
            <p class="text-sm font-semibold text-thc-navy">Upload</p>
            <p class="admin-hint -mt-2">Large JPEG, PNG, and WebP files may be downscaled (max width 2400px) when PHP GD is available.</p>
            <x-admin.ui.group label="File" for="file" name="file">
                <input type="file" name="file" id="file" required class="admin-file-input">
            </x-admin.ui.group>
            <x-admin.ui.group label="Alt text" for="alt_text" name="alt_text">
                <input type="text" name="alt_text" id="alt_text" class="admin-input">
            </x-admin.ui.group>
            <button type="submit" class="admin-btn-primary admin-btn-sm w-fit">Upload</button>
        </form>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($assets as $asset)
            <div class="admin-card p-4 text-sm">
                <p class="truncate font-mono text-xs text-thc-text/80" title="{{ $asset->path }}">{{ $asset->path }}</p>
                <p class="mt-1 text-xs text-thc-text/65">{{ $asset->original_filename }} · {{ number_format(($asset->size_bytes ?? 0) / 1024, 1) }} KB</p>
                <p class="mt-2 break-all text-xs text-thc-royal">{{ asset('storage/'.$asset->path) }}</p>
                <form method="post" action="{{ route('admin.soc.media.destroy', $asset) }}" class="mt-3" onsubmit="return confirm('Delete file?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-danger admin-btn-sm">Delete</button>
                </form>
            </div>
        @empty
            <p class="admin-table-empty col-span-full rounded-[var(--admin-radius-card)] border border-dashed border-thc-navy/15 bg-white py-12">No media uploaded yet.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $assets->links() }}</div>
    <p class="mt-6"><a href="{{ route('admin.soc.dashboard') }}" class="admin-link text-sm">← SOC CMS</a></p>
</x-layouts.admin>
