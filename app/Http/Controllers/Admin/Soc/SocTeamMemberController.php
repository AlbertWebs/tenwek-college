<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\SocTeamMember;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SocTeamMemberController extends BaseSocAdminController
{
    public function index(Request $request): View
    {
        $soc = $this->socSchool($request);
        $members = SocTeamMember::query()
            ->where('school_id', $soc->id)
            ->orderBy('team')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(30);

        return view('admin.soc.team.index', compact('soc', 'members'));
    }

    public function create(Request $request): View
    {
        $soc = $this->socSchool($request);

        return view('admin.soc.team.create', compact('soc'));
    }

    public function store(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'team' => ['required', Rule::in([SocTeamMember::TEAM_BOARD, SocTeamMember::TEAM_MANAGEMENT, SocTeamMember::TEAM_FACULTY])],
            'name' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:10000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'highlight' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
        $data = collect($validated)->except('image')->all();
        $data['school_id'] = $soc->id;
        $data['highlight'] = $request->boolean('highlight', false);
        $data['is_published'] = $request->boolean('is_published', true);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('soc/'.$soc->id.'/team', 'public');
        }
        SocTeamMember::query()->create($data);
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.team.index')->with('status', 'Team member added.');
    }

    public function edit(Request $request, SocTeamMember $team): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $team->school_id === (int) $soc->id, 404);

        return view('admin.soc.team.edit', compact('soc', 'team'));
    }

    public function update(Request $request, SocTeamMember $team): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $team->school_id === (int) $soc->id, 404);
        $validated = $request->validate([
            'team' => ['required', Rule::in([SocTeamMember::TEAM_BOARD, SocTeamMember::TEAM_MANAGEMENT, SocTeamMember::TEAM_FACULTY])],
            'name' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:10000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'highlight' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
        $data = collect($validated)->except('image')->all();
        $data['highlight'] = $request->boolean('highlight', false);
        $data['is_published'] = $request->boolean('is_published', true);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('soc/'.$soc->id.'/team', 'public');
        }
        $team->update($data);
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.team.index')->with('status', 'Team member updated.');
    }

    public function destroy(Request $request, SocTeamMember $team): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $team->school_id === (int) $soc->id, 404);
        $team->delete();
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.team.index')->with('status', 'Team member removed.');
    }
}
