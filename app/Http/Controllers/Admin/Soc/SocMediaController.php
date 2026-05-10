<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\MediaAsset;
use App\Support\Media\GdImageDownscaler;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SocMediaController extends BaseSocAdminController
{
    public function index(Request $request): View
    {
        $soc = $this->socSchool($request);
        $assets = MediaAsset::query()
            ->where('school_id', $soc->id)
            ->orderByDesc('id')
            ->paginate(24);

        return view('admin.soc.media.index', compact('soc', 'assets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);

        if ($request->hasFile('files')) {
            $validated = $request->validate([
                'files' => ['required', 'array', 'min:1', 'max:40'],
                'files.*' => ['file', 'max:12288', 'mimes:jpg,jpeg,png,gif,webp,svg,pdf'],
                'alt_text' => ['nullable', 'string', 'max:500'],
            ]);
            $altText = $validated['alt_text'] ?? null;
            $paths = [];
            foreach ($request->file('files', []) as $file) {
                if (! $file->isValid()) {
                    continue;
                }
                $paths[] = $this->persistLibraryUpload($request, $soc->id, $file, $altText);
            }
            if ($paths === []) {
                return back()->withErrors(['files' => 'No valid files were uploaded.'])->withInput();
            }
            $count = count($paths);
            $message = $count === 1
                ? 'File uploaded. Path: '.$paths[0]
                : $count.' files uploaded.';
            SocLandingRepository::flushCache();

            return redirect()->route('admin.soc.media.index')->with('status', $message);
        }

        $request->validate([
            'file' => ['required', 'file', 'max:12288', 'mimes:jpg,jpeg,png,gif,webp,svg,pdf'],
            'alt_text' => ['nullable', 'string', 'max:500'],
        ]);
        $path = $this->persistLibraryUpload($request, $soc->id, $request->file('file'), $request->input('alt_text'));
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.media.index')->with('status', 'File uploaded. Path: '.$path);
    }

    public function destroy(Request $request, MediaAsset $medium): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $medium->school_id === (int) $soc->id, 404);
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.media.index')->with('status', 'Asset removed.');
    }

    /**
     * @return string Stored path relative to the public disk (e.g. soc/1/library/…).
     */
    private function persistLibraryUpload(Request $request, int $schoolId, UploadedFile $file, ?string $altText): string
    {
        $path = $file->store('soc/'.$schoolId.'/library', 'public');
        $medium = MediaAsset::query()->create([
            'user_id' => $request->user()->id,
            'school_id' => $schoolId,
            'disk' => 'public',
            'path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'alt_text' => $altText,
        ]);
        $fullPath = Storage::disk('public')->path($path);
        if (GdImageDownscaler::downscaleMaxWidth($fullPath)) {
            $medium->update(['size_bytes' => Storage::disk('public')->size($path)]);
        }

        return $path;
    }
}
