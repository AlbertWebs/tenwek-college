<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\SocProgrammeGroup;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocProgrammeGroupController extends BaseSocAdminController
{
    public function index(Request $request): View
    {
        $soc = $this->socSchool($request);
        $groups = SocProgrammeGroup::query()
            ->where('school_id', $soc->id)
            ->withCount('items')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);

        return view('admin.soc.programme-groups.index', compact('soc', 'groups'));
    }

    public function create(Request $request): View
    {
        $soc = $this->socSchool($request);

        return view('admin.soc.programme-groups.create', compact('soc'));
    }

    public function store(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);
        $validated['school_id'] = $soc->id;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        SocProgrammeGroup::query()->create($validated);
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.programme-groups.index')->with('status', 'Programme group created.');
    }

    public function edit(Request $request, SocProgrammeGroup $programme_group): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $programme_group->school_id === (int) $soc->id, 404);

        return view('admin.soc.programme-groups.edit', compact('soc', 'programme_group'));
    }

    public function update(Request $request, SocProgrammeGroup $programme_group): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $programme_group->school_id === (int) $soc->id, 404);
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $programme_group->update($validated);
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.programme-groups.index')->with('status', 'Programme group updated.');
    }

    public function destroy(Request $request, SocProgrammeGroup $programme_group): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $programme_group->school_id === (int) $soc->id, 404);
        $programme_group->delete();
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.programme-groups.index')->with('status', 'Programme group removed.');
    }
}
