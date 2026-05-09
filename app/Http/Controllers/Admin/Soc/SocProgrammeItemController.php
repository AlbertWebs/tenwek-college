<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\SocProgrammeGroup;
use App\Models\SocProgrammeItem;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SocProgrammeItemController extends BaseSocAdminController
{
    public function index(Request $request, SocProgrammeGroup $programme_group): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $programme_group->school_id === (int) $soc->id, 404);
        $items = SocProgrammeItem::query()
            ->where('soc_programme_group_id', $programme_group->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(30);

        return view('admin.soc.programme-items.index', compact('soc', 'programme_group', 'items'));
    }

    public function create(Request $request, SocProgrammeGroup $programme_group): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $programme_group->school_id === (int) $soc->id, 404);

        return view('admin.soc.programme-items.create', compact('soc', 'programme_group'));
    }

    public function store(Request $request, SocProgrammeGroup $programme_group): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $programme_group->school_id === (int) $soc->id, 404);
        $validated = $request->validate([
            'slug' => [
                'required', 'string', 'max:160', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('soc_programme_items', 'slug')->where('school_id', $soc->id),
            ],
            'title' => ['required', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:5000'],
            'body' => ['nullable', 'string', 'max:500000'],
            'seo_title' => ['nullable', 'string', 'max:192'],
            'seo_description' => ['nullable', 'string', 'max:512'],
            'seo_keywords' => ['nullable', 'string', 'max:512'],
            'og_title' => ['nullable', 'string', 'max:192'],
            'og_image_path' => ['nullable', 'string', 'max:512'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
        $validated['school_id'] = $soc->id;
        $validated['soc_programme_group_id'] = $programme_group->id;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_published'] = $request->boolean('is_published', true);
        SocProgrammeItem::query()->create($validated);
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.programme-groups.items.index', $programme_group)->with('status', 'Programme saved.');
    }

    public function edit(Request $request, SocProgrammeGroup $programme_group, SocProgrammeItem $item): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $programme_group->school_id === (int) $soc->id, 404);
        abort_unless((int) $item->soc_programme_group_id === (int) $programme_group->id, 404);

        return view('admin.soc.programme-items.edit', compact('soc', 'programme_group', 'item'));
    }

    public function update(Request $request, SocProgrammeGroup $programme_group, SocProgrammeItem $item): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $programme_group->school_id === (int) $soc->id, 404);
        abort_unless((int) $item->soc_programme_group_id === (int) $programme_group->id, 404);
        $validated = $request->validate([
            'slug' => [
                'required', 'string', 'max:160', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('soc_programme_items', 'slug')->where('school_id', $soc->id)->ignore($item->id),
            ],
            'title' => ['required', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:5000'],
            'body' => ['nullable', 'string', 'max:500000'],
            'seo_title' => ['nullable', 'string', 'max:192'],
            'seo_description' => ['nullable', 'string', 'max:512'],
            'seo_keywords' => ['nullable', 'string', 'max:512'],
            'og_title' => ['nullable', 'string', 'max:192'],
            'og_image_path' => ['nullable', 'string', 'max:512'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_published'] = $request->boolean('is_published', true);
        $item->update($validated);
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.programme-groups.items.index', $programme_group)->with('status', 'Programme updated.');
    }

    public function destroy(Request $request, SocProgrammeGroup $programme_group, SocProgrammeItem $item): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $programme_group->school_id === (int) $soc->id, 404);
        abort_unless((int) $item->soc_programme_group_id === (int) $programme_group->id, 404);
        $item->delete();
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.programme-groups.items.index', $programme_group)->with('status', 'Programme removed.');
    }
}
