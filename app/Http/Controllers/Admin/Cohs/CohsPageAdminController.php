<?php

namespace App\Http\Controllers\Admin\Cohs;

use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CohsPageAdminController extends BaseCohsAdminController
{
    public function index(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $pages = Page::query()
            ->where('school_id', $cohs->id)
            ->orderBy('title')
            ->paginate(30);

        return view('admin.cohs.pages.index', compact('cohs', 'pages'));
    }

    public function edit(Request $request, Page $page): View
    {
        $cohs = $this->cohsSchool($request);
        abort_unless((int) $page->school_id === (int) $cohs->id, 404);

        return view('admin.cohs.pages.edit', compact('cohs', 'page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        abort_unless((int) $page->school_id === (int) $cohs->id, 404);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'body' => ['nullable', 'string', 'max:500000'],
            'seo_title' => ['nullable', 'string', 'max:192'],
            'seo_description' => ['nullable', 'string', 'max:512'],
            'seo_keywords' => ['nullable', 'string', 'max:512'],
            'og_title' => ['nullable', 'string', 'max:192'],
            'canonical_path' => ['nullable', 'string', 'max:512'],
            'og_image_path' => ['nullable', 'string', 'max:512'],
            'robots' => ['nullable', 'string', 'max:120'],
            'published_at' => ['nullable', 'date'],
        ]);
        $page->update($validated);

        return redirect()->route('admin.cohs.pages.index')->with('status', 'Page updated.');
    }
}
