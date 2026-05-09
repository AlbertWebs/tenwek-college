<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteAdminSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingsController extends Controller
{
    public function edit(): View
    {
        $defaults = [
            'site_name' => '',
            'tagline' => '',
            'institution_legal' => '',
            'email_public' => '',
            'phone' => '',
        ];
        $general = array_merge($defaults, SiteAdminSetting::instance()->general ?? []);

        return view('admin.settings.edit', compact('general'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:160'],
            'tagline' => ['nullable', 'string', 'max:500'],
            'institution_legal' => ['nullable', 'string', 'max:200'],
            'email_public' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:80'],
        ]);

        $row = SiteAdminSetting::instance();
        $row->general = $validated;
        $row->save();

        return redirect()->route('admin.settings.edit')->with('status', __('Institution settings saved. They apply site-wide on the next request.'));
    }
}
