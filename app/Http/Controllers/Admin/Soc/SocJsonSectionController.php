<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\SocLandingSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocJsonSectionController extends BaseSocAdminController
{
    /** Section keys stored as JSON overrides (merge with config defaults on the site). */
    private const ALLOWED = [
        'our_history',
        'message_from_principal',
        'strategic_partners',
        'academic_programmes',
        'fee',
        'gallery',
        'faqs',
        'admissions',
        'board_and_management',
        'testimonials',
    ];

    public function edit(Request $request, string $section): View
    {
        abort_unless(in_array($section, self::ALLOWED, true), 404);
        $soc = $this->socSchool($request);
        $row = SocLandingSection::query()
            ->where('school_id', $soc->id)
            ->where('section_key', $section)
            ->first();
        $payload = is_array($row?->payload) ? $row->payload : [];
        $json = old('json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return view('admin.soc.json-section', compact('soc', 'section', 'json'));
    }

    public function update(Request $request, string $section): RedirectResponse
    {
        abort_unless(in_array($section, self::ALLOWED, true), 404);
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'json' => ['required', 'string', 'max:500000'],
        ]);
        try {
            $decoded = json_decode($validated['json'], true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return back()->withErrors(['json' => 'Invalid JSON: '.$e->getMessage()])->withInput();
        }
        if (! is_array($decoded)) {
            return back()->withErrors(['json' => 'JSON must decode to an object.'])->withInput();
        }
        $this->persistSection($soc, $section, $decoded);

        return redirect()->route('admin.soc.json.edit', $section)->with('status', 'Section saved. The public site merges this with built-in defaults.');
    }
}
