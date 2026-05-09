<?php

namespace App\Http\Controllers\Admin\Cohs;

use App\Models\CohsLandingSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CohsJsonSectionController extends BaseCohsAdminController
{
    /** Advanced JSON overrides merged with config defaults. */
    private const ALLOWED = [
        'programmes',
    ];

    public function edit(Request $request, string $section): View
    {
        abort_unless(in_array($section, self::ALLOWED, true), 404);
        $cohs = $this->cohsSchool($request);
        $row = CohsLandingSection::query()
            ->where('school_id', $cohs->id)
            ->where('section_key', $section)
            ->first();
        $payload = is_array($row?->payload) ? $row->payload : [];
        $json = old('json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return view('admin.cohs.json-section', compact('cohs', 'section', 'json'));
    }

    public function update(Request $request, string $section): RedirectResponse
    {
        abort_unless(in_array($section, self::ALLOWED, true), 404);
        $cohs = $this->cohsSchool($request);
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
        $this->persistSection($cohs, $section, $decoded);

        return redirect()->route('admin.cohs.json.edit', $section)->with('status', 'Section saved. The public site merges this with defaults in config when keys match.');
    }
}
