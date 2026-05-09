<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteAdminSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        ]);

        $row = SiteAdminSetting::instance();
        $row->global_seo = $validated;
        $row->save();

        return redirect()->route('admin.global-seo.edit')->with('status', __('Global SEO defaults saved. Pages that define their own meta still override these values.'));
    }
}
