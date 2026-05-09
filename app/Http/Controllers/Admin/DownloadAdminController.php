<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\DownloadCategory;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadAdminController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Download::class);

        $user = $request->user();
        $q = Download::query()->with(['school', 'category'])->orderByDesc('updated_at');

        if (! $user->isSuperAdmin()) {
            $q->where('school_id', $user->school_id);
        } elseif ($request->filled('school')) {
            $q->whereHas('school', fn ($s) => $s->where('slug', $request->string('school')));
        }

        $downloads = $q->paginate(20)->withQueryString();
        $schools = School::query()->where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.downloads.index', compact('downloads', 'schools'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Download::class);

        $user = $request->user();
        $categories = DownloadCategory::query()
            ->when(! $user->isSuperAdmin(), fn ($q) => $q->where('school_id', $user->school_id))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $schools = School::query()->where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.downloads.create', compact('categories', 'schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Download::class);

        $user = $request->user();
        $validated = $request->validate([
            'school_id' => ['required', 'exists:schools,id'],
            'category_id' => ['nullable', 'exists:download_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:192', 'unique:downloads,slug'],
            'description' => ['nullable', 'string', 'max:2000'],
            'seo_title' => ['nullable', 'string', 'max:192'],
            'seo_description' => ['nullable', 'string', 'max:512'],
            'published_at' => ['nullable', 'date'],
            'is_active' => ['sometimes', 'boolean'],
            'file' => ['required', 'file', 'max:20480', 'mimes:pdf,doc,docx,xls,xlsx,zip'],
            'preview' => ['nullable', 'image', 'max:5120'],
        ]);

        $school = School::query()->findOrFail($validated['school_id']);
        if (! $user->isSuperAdmin() && (int) $user->school_id !== (int) $school->id) {
            abort(403);
        }

        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $slug = $this->uniqueSlug($slug);

        $path = $request->file('file')->store($school->slug, 'downloads');
        $original = $request->file('file')->getClientOriginalName();
        $mime = $request->file('file')->getMimeType();
        $size = $request->file('file')->getSize();
        $extension = strtolower($request->file('file')->getClientOriginalExtension());

        $previewPath = null;
        if ($request->hasFile('preview')) {
            $previewPath = $request->file('preview')->store("{$school->slug}/previews", 'public');
        }

        $download = Download::query()->create([
            'school_id' => $school->id,
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
            'original_filename' => $original,
            'mime' => $mime,
            'size_bytes' => $size,
            'extension' => $extension,
            'preview_image_path' => $previewPath ? 'storage/'.$previewPath : null,
            'is_active' => $request->boolean('is_active', true),
            'published_at' => $validated['published_at'] ?? now(),
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
        ]);

        return redirect()->route('admin.downloads.edit', $download)->with('status', __('Download created.'));
    }

    public function file(Request $request, Download $download): BinaryFileResponse|StreamedResponse
    {
        $this->authorize('view', $download);

        if (! $download->hasFile()) {
            abort(404);
        }

        $path = $download->file_path;

        if (! Storage::disk('downloads')->exists($path)) {
            abort(404);
        }

        $filename = $download->original_filename ?? basename($path);
        $mime = $download->mime ?? (Storage::disk('downloads')->mimeType($path) ?: 'application/octet-stream');
        $forceDownload = $request->boolean('download');

        if (! $forceDownload && $this->mimeSupportsInlineDisplay($mime)) {
            return response()->file(
                Storage::disk('downloads')->path($path),
                [
                    'Content-Type' => $mime,
                    'Content-Disposition' => 'inline; filename="'.$this->asciiFilename($filename).'"',
                ]
            );
        }

        return Storage::disk('downloads')->download($path, $filename);
    }

    public function edit(Request $request, Download $download): View
    {
        $this->authorize('update', $download);

        $user = $request->user();
        $categories = DownloadCategory::query()
            ->when(! $user->isSuperAdmin(), fn ($q) => $q->where('school_id', $user->school_id))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $schools = School::query()->where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.downloads.edit', compact('download', 'categories', 'schools'));
    }

    public function update(Request $request, Download $download): RedirectResponse
    {
        $this->authorize('update', $download);

        $user = $request->user();
        $validated = $request->validate([
            'school_id' => ['required', 'exists:schools,id'],
            'category_id' => ['nullable', 'exists:download_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:192', 'unique:downloads,slug,'.$download->id],
            'description' => ['nullable', 'string', 'max:2000'],
            'seo_title' => ['nullable', 'string', 'max:192'],
            'seo_description' => ['nullable', 'string', 'max:512'],
            'published_at' => ['nullable', 'date'],
            'is_active' => ['sometimes', 'boolean'],
            'file' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,xls,xlsx,zip'],
            'preview' => ['nullable', 'image', 'max:5120'],
        ]);

        $school = School::query()->findOrFail($validated['school_id']);
        if (! $user->isSuperAdmin() && (int) $user->school_id !== (int) $school->id) {
            abort(403);
        }

        if ($request->hasFile('file')) {
            if ($download->file_path) {
                Storage::disk('downloads')->delete($download->file_path);
            }
            $path = $request->file('file')->store($school->slug, 'downloads');
            $download->file_path = $path;
            $download->original_filename = $request->file('file')->getClientOriginalName();
            $download->mime = $request->file('file')->getMimeType();
            $download->size_bytes = $request->file('file')->getSize();
            $download->extension = strtolower($request->file('file')->getClientOriginalExtension());
        }

        if ($request->hasFile('preview')) {
            if ($download->preview_image_path && Str::startsWith($download->preview_image_path, 'storage/')) {
                Storage::disk('public')->delete(Str::after($download->preview_image_path, 'storage/'));
            }
            $previewPath = $request->file('preview')->store("{$school->slug}/previews", 'public');
            $download->preview_image_path = 'storage/'.$previewPath;
        }

        $download->fill([
            'school_id' => $school->id,
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'published_at' => $validated['published_at'] ?? $download->published_at,
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
        ]);
        $download->save();

        return back()->with('status', __('Download updated.'));
    }

    public function destroy(Request $request, Download $download): RedirectResponse
    {
        $this->authorize('delete', $download);

        if ($download->file_path) {
            Storage::disk('downloads')->delete($download->file_path);
        }
        if ($download->preview_image_path && Str::startsWith($download->preview_image_path, 'storage/')) {
            Storage::disk('public')->delete(Str::after($download->preview_image_path, 'storage/'));
        }
        $download->delete();

        return redirect()->route('admin.downloads.index')->with('status', __('Download removed.'));
    }

    protected function uniqueSlug(string $base): string
    {
        $slug = $base;
        $i = 1;
        while (Download::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    protected function mimeSupportsInlineDisplay(string $mime): bool
    {
        return str_starts_with($mime, 'application/pdf')
            || str_starts_with($mime, 'image/');
    }

    /**
     * Strip characters that break Content-Disposition filename="...".
     */
    protected function asciiFilename(string $filename): string
    {
        $filename = str_replace(['"', "\r", "\n"], '', $filename);

        return $filename !== '' ? $filename : 'download';
    }
}
