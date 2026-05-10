<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteAdminSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GlobalSeoController extends Controller
{
    public function edit(): View
    {
        $defaults = [
            'default_meta_description' => '',
            'default_keywords' => '',
            'default_og_image' => '',
            'default_robots' => '',
        ];
        $globalSeo = array_merge($defaults, SiteAdminSetting::instance()->global_seo ?? []);

        return view('admin.global-seo.edit', compact('globalSeo'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'default_meta_description' => ['nullable', 'string', 'max:512'],
            'default_keywords' => ['nullable', 'string', 'max:512'],
            'default_og_image' => ['nullable', 'string', 'max:512'],
            'default_robots' => ['nullable', 'string', 'max:160'],
            'og_image_upload' => ['nullable', 'image', 'max:8192', 'mimes:jpeg,jpg,png,webp,gif'],
            'clear_og_image' => ['sometimes', 'boolean'],
        ]);

        $row = SiteAdminSetting::instance();
        $defaults = [
            'default_meta_description' => '',
            'default_keywords' => '',
            'default_og_image' => '',
            'default_robots' => '',
        ];
        $prior = array_merge($defaults, is_array($row->global_seo) ? $row->global_seo : []);
        $priorOg = $prior['default_og_image'] ?? '';

        $merged = $prior;
        $merged['default_meta_description'] = $validated['default_meta_description'] ?? '';
        $merged['default_keywords'] = $validated['default_keywords'] ?? '';
        $merged['default_robots'] = $validated['default_robots'] ?? '';

        if ($request->hasFile('og_image_upload')) {
            self::deleteStoredGlobalOgIfManaged($priorOg);
            $path = $request->file('og_image_upload')->store('global-seo', 'public');
            $merged['default_og_image'] = 'storage/'.$path;
        } elseif ($request->boolean('clear_og_image')) {
            self::deleteStoredGlobalOgIfManaged($priorOg);
            $merged['default_og_image'] = '';
        } else {
            $merged['default_og_image'] = $validated['default_og_image'] ?? '';
        }

        $row->global_seo = $merged;
        $row->save();

        return redirect()->route('admin.global-seo.edit')->with('status', __('Global SEO defaults saved. Pages that define their own meta still override these values.'));
    }

    private static function deleteStoredGlobalOgIfManaged(?string $stored): void
    {
        if ($stored === null || $stored === '') {
            return;
        }
        if (! str_starts_with($stored, 'storage/global-seo/')) {
            return;
        }
        $relative = Str::after($stored, 'storage/');
        Storage::disk('public')->delete($relative);
    }
}
