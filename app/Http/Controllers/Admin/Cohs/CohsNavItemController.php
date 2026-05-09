<?php

namespace App\Http\Controllers\Admin\Cohs;

use App\Models\CohsNavItem;
use App\Support\Cohs\CohsLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CohsNavItemController extends BaseCohsAdminController
{
    public function index(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $items = CohsNavItem::query()
            ->where('school_id', $cohs->id)
            ->with('parent')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(40);

        return view('admin.cohs.navigation.index', compact('cohs', 'items'));
    }

    public function create(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $parents = CohsNavItem::query()
            ->where('school_id', $cohs->id)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();

        return view('admin.cohs.navigation.create', compact('cohs', 'parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:cohs_nav_items,id'],
            'mega_id' => ['nullable', 'string', 'max:64'],
            'label' => ['required', 'string', 'max:255'],
            'page_slug' => ['nullable', 'string', 'max:192'],
            'route_name' => ['nullable', Rule::in(['cohs.on-campus-application'])],
            'external_url' => ['nullable', 'string', 'max:2000'],
            'external_config_key' => ['nullable', 'string', 'max:96', Rule::in(['off_campus_application_url'])],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
            'is_highlight' => ['sometimes', 'boolean'],
            'open_new_tab' => ['sometimes', 'boolean'],
        ]);
        $this->validateNavTarget($validated);
        if (! empty($validated['parent_id'])) {
            $parent = CohsNavItem::query()->findOrFail($validated['parent_id']);
            abort_unless((int) $parent->school_id === (int) $cohs->id, 403);
            abort_unless($parent->parent_id === null, 422);
        }
        $validated['school_id'] = $cohs->id;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_highlight'] = $request->boolean('is_highlight', false);
        $validated['open_new_tab'] = $request->boolean('open_new_tab', false);
        CohsNavItem::query()->create($validated);
        CohsLandingRepository::flushCache();

        return redirect()->route('admin.cohs.navigation.index')->with('status', 'Navigation item created. Delete all custom rows to fall back to config menu.');
    }

    public function edit(Request $request, CohsNavItem $navigation): View
    {
        $cohs = $this->cohsSchool($request);
        abort_unless((int) $navigation->school_id === (int) $cohs->id, 404);
        $parents = CohsNavItem::query()
            ->where('school_id', $cohs->id)
            ->whereNull('parent_id')
            ->where('id', '!=', $navigation->id)
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();

        return view('admin.cohs.navigation.edit', compact('cohs', 'navigation', 'parents'));
    }

    public function update(Request $request, CohsNavItem $navigation): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        abort_unless((int) $navigation->school_id === (int) $cohs->id, 404);
        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:cohs_nav_items,id'],
            'mega_id' => ['nullable', 'string', 'max:64'],
            'label' => ['required', 'string', 'max:255'],
            'page_slug' => ['nullable', 'string', 'max:192'],
            'route_name' => ['nullable', Rule::in(['cohs.on-campus-application'])],
            'external_url' => ['nullable', 'string', 'max:2000'],
            'external_config_key' => ['nullable', 'string', 'max:96', Rule::in(['off_campus_application_url'])],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
            'is_highlight' => ['sometimes', 'boolean'],
            'open_new_tab' => ['sometimes', 'boolean'],
        ]);
        $this->validateNavTarget($validated);
        if (! empty($validated['parent_id'])) {
            $parent = CohsNavItem::query()->findOrFail($validated['parent_id']);
            abort_unless((int) $parent->school_id === (int) $cohs->id, 403);
            abort_unless($parent->parent_id === null, 422);
            abort_if((int) $validated['parent_id'] === (int) $navigation->id, 422);
        }
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_highlight'] = $request->boolean('is_highlight', false);
        $validated['open_new_tab'] = $request->boolean('open_new_tab', false);
        $navigation->update($validated);
        CohsLandingRepository::flushCache();

        return redirect()->route('admin.cohs.navigation.index')->with('status', 'Navigation item updated.');
    }

    public function destroy(Request $request, CohsNavItem $navigation): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        abort_unless((int) $navigation->school_id === (int) $cohs->id, 404);
        $navigation->delete();
        CohsLandingRepository::flushCache();

        return redirect()->route('admin.cohs.navigation.index')->with('status', 'Navigation item deleted.');
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function validateNavTarget(array $validated): void
    {
        $hasExternal = filled($validated['external_url'] ?? null);
        $hasSlug = filled($validated['page_slug'] ?? null);
        $hasRoute = filled($validated['route_name'] ?? null);
        $hasConfigKey = filled($validated['external_config_key'] ?? null);

        $coreTargets = (int) $hasExternal + (int) $hasRoute + (int) $hasConfigKey;
        if ($coreTargets > 1) {
            abort(422, 'Choose only one of: external URL, named route, or config URL key.');
        }
        if ($hasSlug && ($hasExternal || $hasRoute)) {
            abort(422, 'Internal slug cannot be combined with an external URL or special route.');
        }

        if (! empty($validated['parent_id'])) {
            if (! $hasSlug && ! $hasExternal && ! $hasRoute && ! $hasConfigKey) {
                abort(422, 'Submenu links need a destination (page slug, route, external URL, or config URL key).');
            }
        }
    }
}
