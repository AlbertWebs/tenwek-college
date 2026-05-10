<x-layouts.admin header="SOC — Media library">
    <div class="admin-card mb-10 max-w-3xl p-6" x-data="socMediaDropzone()">
        <form method="post" action="{{ route('admin.soc.media.store') }}" enctype="multipart/form-data" class="admin-form-stack-tight">
            @csrf
            <p class="text-sm font-semibold text-thc-navy">Upload</p>
            <p class="admin-hint -mt-2">
                Drop many files at once or click to browse. JPEG, PNG, GIF, WebP, SVG, and PDF — up to 12MB each, 40 files per batch.
                Large JPEG/PNG/WebP may be downscaled (max width 2400px) when PHP GD is available.
                Image files (not PDF) are listed first on the public <a href="{{ url('/soc/gallery') }}" class="admin-link" target="_blank" rel="noopener">/soc/gallery</a> page automatically.
            </p>

            <input
                type="file"
                name="files[]"
                id="soc-media-files"
                multiple
                x-ref="fileInput"
                class="sr-only"
                @change="onNativePick($event)"
            >

            <label
                for="soc-media-files"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="dragging = false; addFiles($event.dataTransfer.files)"
                class="mt-4 flex min-h-[11rem] cursor-pointer flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed px-6 py-10 text-center transition"
                :class="dragging
                    ? 'border-thc-royal bg-thc-royal/[0.07] ring-2 ring-thc-royal/25'
                    : 'border-thc-navy/20 bg-thc-navy/[0.02] hover:border-thc-royal/35 hover:bg-thc-royal/[0.04]'"
            >
                <span class="pointer-events-none rounded-full bg-white p-3 shadow-sm ring-1 ring-thc-navy/10" aria-hidden="true">
                    <svg class="h-8 w-8 text-thc-royal" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                </span>
                <span class="pointer-events-none text-sm font-medium text-thc-navy">
                    Drop files here or <span class="text-thc-royal underline decoration-thc-royal/30 underline-offset-2">choose files</span>
                </span>
                <span class="pointer-events-none text-xs text-thc-text/60">Your selection appears below before upload.</span>
            </label>

            <p x-show="hint" x-cloak class="text-sm text-amber-800" x-text="hint"></p>

            <div x-show="items.length > 0" x-cloak class="mt-4 rounded-lg border border-thc-navy/10 bg-white">
                <div class="flex items-center justify-between border-b border-thc-navy/10 px-4 py-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-thc-text/70">
                        Ready to upload · <span x-text="items.length"></span> file(s)
                    </p>
                    <button type="button" class="admin-btn-ghost admin-btn-sm" @click="clearAll">Clear all</button>
                </div>
                <ul class="max-h-72 divide-y divide-thc-navy/10 overflow-y-auto text-sm">
                    <template x-for="item in items" :key="item.id">
                        <li class="flex items-center gap-3 px-4 py-2">
                            <div class="relative h-14 w-14 shrink-0 overflow-hidden rounded-lg ring-1 ring-thc-navy/10">
                                <img
                                    x-show="item.previewUrl"
                                    :src="item.previewUrl || ''"
                                    alt=""
                                    class="absolute inset-0 h-full w-full object-cover"
                                    width="56"
                                    height="56"
                                >
                                <div
                                    x-show="!item.previewUrl"
                                    class="absolute inset-0 flex items-center justify-center bg-thc-navy/[0.07] text-[10px] font-bold uppercase tracking-wide text-thc-text/55"
                                    x-cloak
                                >
                                    PDF
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-mono text-xs text-thc-navy" x-text="item.file.name"></p>
                                <p class="mt-0.5 text-xs text-thc-text/55" x-text="formatSize(item.file.size)"></p>
                            </div>
                            <button type="button" class="shrink-0 text-xs font-medium text-red-700 hover:underline" @click="remove(item.id)">Remove</button>
                        </li>
                    </template>
                </ul>
            </div>

            <x-admin.ui.group label="Alt text (optional)" for="alt_text" name="alt_text" hint="Applied to every file in this batch when provided.">
                <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text') }}" class="admin-input">
            </x-admin.ui.group>

            <button
                type="submit"
                class="admin-btn-primary admin-btn-sm w-fit"
                :disabled="items.length === 0"
                :class="items.length === 0 ? 'pointer-events-none opacity-50' : ''"
            >
                Upload
            </button>
        </form>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($assets as $asset)
            @php($url = $asset->publicUrl())
            <div class="admin-card overflow-hidden p-0 text-sm shadow-sm ring-1 ring-thc-navy/[0.06]">
                <a
                    href="{{ $url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="relative flex aspect-[4/3] w-full items-center justify-center bg-gradient-to-b from-thc-navy/[0.06] to-thc-navy/[0.02] outline-none ring-inset ring-thc-royal/0 transition hover:ring-2 focus-visible:ring-2 focus-visible:ring-thc-royal/40"
                >
                    @if ($asset->isPreviewableImage())
                        <img
                            src="{{ $url }}"
                            alt="{{ $asset->alt_text ?: $asset->original_filename }}"
                            class="max-h-full max-w-full object-contain"
                            loading="lazy"
                            decoding="async"
                            width="400"
                            height="300"
                        >
                    @else
                        <span class="flex flex-col items-center gap-2 px-4 text-center">
                            <svg class="h-12 w-12 text-thc-text/35" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5m0 0V9.375m0 0V8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v3.659m0 0A3.375 3.375 0 0 0 5.625 15h12.75a3.375 3.375 0 0 0 3.375-3.375v-3.659m0 0V8.25A2.25 2.25 0 0 0 18 6H6a2.25 2.25 0 0 0-2.25 2.25v7.5A2.25 2.25 0 0 0 6 18h12a2.25 2.25 0 0 0 2.25-2.25v-3.659" />
                            </svg>
                            <span class="text-xs font-semibold text-thc-text/55">PDF · open</span>
                        </span>
                    @endif
                </a>
                <div class="border-t border-thc-navy/10 p-4">
                    <p class="truncate font-mono text-xs text-thc-text/80" title="{{ $asset->path }}">{{ $asset->path }}</p>
                    <p class="mt-1 text-xs text-thc-text/65">{{ $asset->original_filename }} · {{ number_format(($asset->size_bytes ?? 0) / 1024, 1) }} KB</p>
                    <p class="mt-2 break-all text-xs text-thc-royal">{{ $url }}</p>
                    <form method="post" action="{{ route('admin.soc.media.destroy', $asset) }}" class="mt-3" onsubmit="return confirm('Delete file?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn-danger admin-btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="admin-table-empty col-span-full rounded-[var(--admin-radius-card)] border border-dashed border-thc-navy/15 bg-white py-12">No media uploaded yet.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $assets->links() }}</div>
    <p class="mt-6"><a href="{{ route('admin.soc.dashboard') }}" class="admin-link text-sm">← SOC CMS</a></p>
</x-layouts.admin>
