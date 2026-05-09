<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\MediaAsset;
use App\Support\Media\GdImageDownscaler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $request->validate([
            'file' => ['required', 'file', 'max:12288', 'mimes:jpg,jpeg,png,gif,webp,svg,pdf'],
            'alt_text' => ['nullable', 'string', 'max:500'],
        ]);
        $file = $request->file('file');
        $path = $file->store('soc/'.$soc->id.'/library', 'public');
        $medium = MediaAsset::query()->create([
            'user_id' => $request->user()->id,
            'school_id' => $soc->id,
            'disk' => 'public',
            'path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'alt_text' => $request->input('alt_text'),
        ]);
        $fullPath = Storage::disk('public')->path($path);
        if (GdImageDownscaler::downscaleMaxWidth($fullPath)) {
            $medium->update(['size_bytes' => Storage::disk('public')->size($path)]);
        }

        return redirect()->route('admin.soc.media.index')->with('status', 'File uploaded. Path: '.$path);
    }

    public function destroy(Request $request, MediaAsset $medium): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $medium->school_id === (int) $soc->id, 404);
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        return redirect()->route('admin.soc.media.index')->with('status', 'Asset removed.');
    }
}
