<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\NewsPost;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SocNewsAdminController extends BaseSocAdminController
{
    public function index(Request $request): View
    {
        $soc = $this->socSchool($request);
        $posts = NewsPost::query()
            ->where('school_id', $soc->id)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.soc.news.index', compact('soc', 'posts'));
    }

    public function create(Request $request): View
    {
        $soc = $this->socSchool($request);

        return view('admin.soc.news.create', compact('soc'));
    }

    public function store(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:192'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'body' => ['nullable', 'string', 'max:500000'],
            'seo_title' => ['nullable', 'string', 'max:192'],
            'seo_description' => ['nullable', 'string', 'max:512'],
            'published_at' => ['nullable', 'date'],
            'featured_image' => ['nullable', 'image', 'max:8192'],
        ]);
        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $slug = $this->uniqueSlug($soc->id, $slug);
        $data = Arr::except($validated, ['slug', 'featured_image']);
        if ($request->hasFile('featured_image')) {
            $data['featured_image_path'] = $request->file('featured_image')->store($soc->slug.'/'.$soc->id.'/news', 'public');
        }
        NewsPost::query()->create([
            ...$data,
            'slug' => $slug,
            'school_id' => $soc->id,
            'author_id' => $request->user()->id,
        ]);

        return redirect()->route('admin.soc.news.index')->with('status', 'News post created.');
    }

    public function edit(Request $request, NewsPost $news): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $news->school_id === (int) $soc->id, 404);

        return view('admin.soc.news.edit', compact('soc', 'news'));
    }

    public function update(Request $request, NewsPost $news): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $news->school_id === (int) $soc->id, 404);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:192'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'body' => ['nullable', 'string', 'max:500000'],
            'seo_title' => ['nullable', 'string', 'max:192'],
            'seo_description' => ['nullable', 'string', 'max:512'],
            'published_at' => ['nullable', 'date'],
            'featured_image' => ['nullable', 'image', 'max:8192'],
        ]);
        if (! empty($validated['slug']) && $validated['slug'] !== $news->slug) {
            $validated['slug'] = $this->uniqueSlug($soc->id, $validated['slug'], $news->id);
        } else {
            unset($validated['slug']);
        }
        $data = Arr::except($validated, ['featured_image']);
        if ($request->hasFile('featured_image')) {
            $this->deleteStoredFeaturedImage($soc, $news->featured_image_path);
            $data['featured_image_path'] = $request->file('featured_image')->store($soc->slug.'/'.$soc->id.'/news', 'public');
        }
        $news->update($data);

        return redirect()->route('admin.soc.news.index')->with('status', 'News post updated.');
    }

    public function destroy(Request $request, NewsPost $news): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $news->school_id === (int) $soc->id, 404);
        $this->deleteStoredFeaturedImage($soc, $news->featured_image_path);
        $news->delete();

        return redirect()->route('admin.soc.news.index')->with('status', 'News post deleted.');
    }

    private function uniqueSlug(int $schoolId, string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $i = 1;
        while (NewsPost::query()
            ->where('school_id', $schoolId)
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    private function deleteStoredFeaturedImage(School $school, ?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }
        $prefix = $school->slug.'/'.$school->id.'/news/';
        if (str_starts_with($path, $prefix)) {
            Storage::disk('public')->delete($path);
        }
    }
}
