<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\SocNavItem;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SocNavItemController extends BaseSocAdminController
{
    public function index(Request $request): View
    {
        $soc = $this->socSchool($request);
        $items = SocNavItem::query()
            ->where('school_id', $soc->id)
            ->with('parent')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(40);

        return view('admin.soc.navigation.index', compact('soc', 'items'));
    }

    public function create(Request $request): View
    {
        $soc = $this->socSchool($request);
        $parents = SocNavItem::query()
            ->where('school_id', $soc->id)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();

        return view('admin.soc.navigation.create', compact('soc', 'parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:soc_nav_items,id'],
            'label' => ['required', 'string', 'max:255'],
            'page_slug' => ['nullable', 'string', 'max:192'],
            'route_name' => ['nullable', Rule::in(['soc.register'])],
            'external_url' => ['nullable', 'url', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
            'is_highlight' => ['sometimes', 'boolean'],
            'open_new_tab' => ['sometimes', 'boolean'],
        ]);
        $this->validateNavTarget($validated);
        if (! empty($validated['parent_id'])) {
            $parent = SocNavItem::query()->findOrFail($validated['parent_id']);
            abort_unless((int) $parent->school_id === (int) $soc->id, 403);
            abort_unless($parent->parent_id === null, 422); // one level of children only
        }
        $validated['school_id'] = $soc->id;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_highlight'] = $request->boolean('is_highlight', false);
        $validated['open_new_tab'] = $request->boolean('open_new_tab', false);
        SocNavItem::query()->create($validated);
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.navigation.index')->with('status', 'Navigation item created. Leave all navigation rows deleted to fall back to default menu from config.');
    }

    public function edit(Request $request, SocNavItem $navigation): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $navigation->school_id === (int) $soc->id, 404);
        $parents = SocNavItem::query()
            ->where('school_id', $soc->id)
            ->whereNull('parent_id')
            ->where('id', '!=', $navigation->id)
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();

        return view('admin.soc.navigation.edit', compact('soc', 'navigation', 'parents'));
    }

    public function update(Request $request, SocNavItem $navigation): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $navigation->school_id === (int) $soc->id, 404);
        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:soc_nav_items,id'],
            'label' => ['required', 'string', 'max:255'],
            'page_slug' => ['nullable', 'string', 'max:192'],
            'route_name' => ['nullable', Rule::in(['soc.register'])],
            'external_url' => ['nullable', 'url', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
            'is_highlight' => ['sometimes', 'boolean'],
            'open_new_tab' => ['sometimes', 'boolean'],
        ]);
        $this->validateNavTarget($validated);
        if (! empty($validated['parent_id'])) {
            $parent = SocNavItem::query()->findOrFail($validated['parent_id']);
            abort_unless((int) $parent->school_id === (int) $soc->id, 403);
            abort_unless($parent->parent_id === null, 422);
            abort_if((int) $validated['parent_id'] === (int) $navigation->id, 422);
        }
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_highlight'] = $request->boolean('is_highlight', false);
        $validated['open_new_tab'] = $request->boolean('open_new_tab', false);
        $navigation->update($validated);
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.navigation.index')->with('status', 'Navigation item updated.');
    }

    public function destroy(Request $request, SocNavItem $navigation): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $navigation->school_id === (int) $soc->id, 404);
        $navigation->delete();
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.navigation.index')->with('status', 'Navigation item deleted.');
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function validateNavTarget(array $validated): void
    {
        $hasExternal = filled($validated['external_url'] ?? null);
        $hasSlug = filled($validated['page_slug'] ?? null);
        $hasRoute = filled($validated['route_name'] ?? null);
        $targets = (int) $hasExternal + (int) $hasSlug + (int) $hasRoute;
        if ($targets > 1) {
            abort(422, 'Choose only one destination: external URL, page slug, or register route.');
        }
        if (! empty($validated['parent_id']) && $targets !== 1) {
            abort(422, 'Submenu links must have exactly one destination.');
        }
    }
}
