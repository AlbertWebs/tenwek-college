<x-layouts.admin
    header="New download"
    :breadcrumbs="[['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => 'Downloads', 'href' => route('admin.downloads.index')], ['label' => 'New']]"
>
    <form method="post" action="{{ route('admin.downloads.store') }}" enctype="multipart/form-data" class="admin-page-narrow">
        @csrf
        <div class="admin-card p-6 sm:p-8">
            <div class="admin-form-stack">
                <x-admin.ui.group label="School" for="school_id" name="school_id">
                    <select name="school_id" id="school_id" required class="admin-select">
                        @foreach ($schools as $s)
                            <option value="{{ $s->id }}" @selected(old('school_id', auth()->user()->school_id) == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </x-admin.ui.group>

                <x-admin.ui.group label="Category" for="category_id" name="category_id" hint="Optional.">
                    <select name="category_id" id="category_id" class="admin-select">
                        <option value="">—</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </x-admin.ui.group>

                <x-admin.ui.group label="Title" for="title" name="title">
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required class="admin-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Slug" for="slug" name="slug" hint="Optional; generated when left blank.">
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="admin-input" placeholder="auto-generated">
                </x-admin.ui.group>

                <x-admin.ui.group label="Description" for="description" name="description">
                    <textarea name="description" id="description" rows="4" class="admin-textarea">{{ old('description') }}</textarea>
                </x-admin.ui.group>

                <div class="admin-grid-2">
                    <x-admin.ui.group label="SEO title" for="seo_title" name="seo_title">
                        <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title') }}" class="admin-input">
                    </x-admin.ui.group>
                    <x-admin.ui.group label="Publish at" for="published_at" name="published_at">
                        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}" class="admin-input">
                    </x-admin.ui.group>
                </div>

                <x-admin.ui.group label="SEO description" for="seo_description" name="seo_description">
                    <textarea name="seo_description" id="seo_description" rows="2" class="admin-textarea">{{ old('seo_description') }}</textarea>
                </x-admin.ui.group>

                <x-admin.ui.group label="File" for="file" name="file" hint="PDF, DOC/DOCX, XLS/XLSX, or ZIP.">
                    <input type="file" name="file" id="file" required class="admin-file-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Preview image" for="preview" name="preview" hint="Optional thumbnail for listings.">
                    <input type="file" name="preview" id="preview" accept="image/*" class="admin-file-input">
                </x-admin.ui.group>

                <x-admin.ui.group label="Visibility" name="is_active">
                    <label class="admin-check-row">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="admin-checkbox">
                        <span>Active on site</span>
                    </label>
                </x-admin.ui.group>
            </div>

            <div class="admin-actions admin-actions-sticky mt-8">
                <div class="admin-actions-primary">
                    <button type="submit" class="admin-btn-primary">Save</button>
                    <a href="{{ route('admin.downloads.index') }}" class="admin-btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
