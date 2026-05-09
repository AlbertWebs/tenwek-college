<x-layouts.admin
    header="Edit download"
    :breadcrumbs="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Downloads', 'href' => route('admin.downloads.index')], ['label' => $download->title]]"
>
    <form method="post" action="{{ route('admin.downloads.update', $download) }}" enctype="multipart/form-data" class="admin-page-narrow">
        @csrf
        @method('PUT')
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="School" for="school_id" name="school_id">
                    <select name="school_id" id="school_id" required class="admin-select">
                        @foreach ($schools as $s)
                            <option value="{{ $s->id }}" @selected(old('school_id', $download->school_id) == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </x-admin.ui.group>

                <x-admin.ui.group label="Category" for="category_id" name="category_id" hint="Optional.">
                    <select name="category_id" id="category_id" class="admin-select">
                        <option value="">—</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id', $download->category_id) == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </x-admin.ui.group>

                <x-admin.ui.group label="Title" for="title" name="title">
                    <input type="text" name="title" id="title" value="{{ old('title', $download->title) }}" required class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Slug" for="slug" name="slug">
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $download->slug) }}" required class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Description" for="description" name="description">
                    <textarea name="description" id="description" rows="4" class="admin-textarea">{{ old('description', $download->description) }}</textarea>
                </x-admin.ui.group>

                <div class="admin-grid-2">
                    <x-admin.ui.group label="SEO title" for="seo_title" name="seo_title">
                        <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $download->seo_title) }}" class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Publish at" for="published_at" name="published_at">
                        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $download->published_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
                    </x-admin.ui.group>
                </div>

                <x-admin.ui.group label="SEO description" for="seo_description" name="seo_description">
                    <textarea name="seo_description" id="seo_description" rows="2" class="admin-textarea">{{ old('seo_description', $download->seo_description) }}</textarea>
                </x-admin.ui.group>

                <div class="rounded-[var(--admin-radius-field)] border border-thc-navy/10 bg-thc-navy/[0.02] px-4 py-3 text-sm text-thc-text">
                    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                        <div>
                            <span class="font-medium text-thc-navy">Current file:</span>
                            <span class="text-thc-text/90">{{ $download->original_filename ?? '—' }}</span>
                            @if ($download->hasFile() && $download->mime)
                                <span class="mt-1 block text-xs text-thc-text/60">{{ $download->mime }}</span>
                            @endif
                        </div>
                        @if ($download->hasFile())
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.downloads.file', $download) }}" target="_blank" rel="noopener noreferrer" class="admin-btn-secondary admin-btn-sm">View file</a>
                                <a href="{{ route('admin.downloads.file', $download) }}?download=1" class="admin-btn-secondary admin-btn-sm">Download</a>
                            </div>
                        @else
                            <p class="admin-hint !mt-0 sm:text-right">No file on disk yet. Upload below and save.</p>
                        @endif
                    </div>
                </div>

                <x-admin.ui.group label="Replace file" for="file" name="file" hint="Leave empty to keep the existing file.">
                    <input type="file" name="file" id="file" class="admin-file-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Replace preview image" for="preview" name="preview" hint="Optional.">
                    <input type="file" name="preview" id="preview" accept="image/*" class="admin-file-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Visibility" name="is_active">
                    <label class="admin-check-row">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $download->is_active)) class="admin-checkbox">
                        <span>Active on site</span>
                    </label>
                </x-admin.ui.group>
            </div>

            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Update</button>
                    <a href="{{ route('admin.downloads.index') }}" class="admin-btn-secondary">Back</a>
                </div>
                @can('delete', $download)
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" form="delete-download" class="admin-btn-danger">Delete</button>
                    </div>
                @endcan
            </div>
        </div>
    </form>

    @can('delete', $download)
        <form id="delete-download" method="post" action="{{ route('admin.downloads.destroy', $download) }}" class="hidden" onsubmit="return confirm('Delete this download permanently?');">
            @csrf
            @method('DELETE')
        </form>
    @endcan
</x-layouts.admin>
