<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\School;
use App\Models\SchoolEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SocSchoolEventController extends BaseSocAdminController
{
    public function index(Request $request): View
    {
        $soc = $this->socSchool($request);
        $events = SchoolEvent::query()
            ->where('school_id', $soc->id)
            ->orderByDesc('starts_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.soc.events.index', compact('soc', 'events'));
    }

    public function create(Request $request): View
    {
        $soc = $this->socSchool($request);

        return view('admin.soc.events.create', compact('soc'));
    }

    public function store(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $request->merge([
            'ends_at' => $request->input('ends_at') ?: null,
            'published_at' => $request->input('published_at') ?: null,
            'registration_url' => $request->input('registration_url') ?: null,
        ]);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:192'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'body' => ['nullable', 'string', 'max:500000'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'location' => ['nullable', 'string', 'max:500'],
            'registration_url' => ['nullable', 'string', 'url', 'max:512'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:192'],
            'seo_description' => ['nullable', 'string', 'max:512'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $slug = $this->uniqueSlug($soc->id, $slug);
        $data = Arr::except($validated, ['slug', 'image']);
        $data['slug'] = $slug;
        $data['school_id'] = $soc->id;
        $data['author_id'] = $request->user()->id;
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store($soc->slug.'/'.$soc->id.'/events', 'public');
        }
        SchoolEvent::query()->create($data);

        return redirect()->route('admin.soc.events.index')->with('status', 'Event created.');
    }

    public function edit(Request $request, SchoolEvent $event): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $event->school_id === (int) $soc->id, 404);

        return view('admin.soc.events.edit', compact('soc', 'event'));
    }

    public function update(Request $request, SchoolEvent $event): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $event->school_id === (int) $soc->id, 404);
        $request->merge([
            'ends_at' => $request->input('ends_at') ?: null,
            'published_at' => $request->input('published_at') ?: null,
            'registration_url' => $request->input('registration_url') ?: null,
        ]);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:192'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'body' => ['nullable', 'string', 'max:500000'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'location' => ['nullable', 'string', 'max:500'],
            'registration_url' => ['nullable', 'string', 'url', 'max:512'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:192'],
            'seo_description' => ['nullable', 'string', 'max:512'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
        if (! empty($validated['slug']) && $validated['slug'] !== $event->slug) {
            $validated['slug'] = $this->uniqueSlug($soc->id, $validated['slug'], $event->id);
        } else {
            unset($validated['slug']);
        }
        $data = Arr::except($validated, ['image']);
        if ($request->hasFile('image')) {
            $this->deleteStoredImage($soc, $event->image_path);
            $data['image_path'] = $request->file('image')->store($soc->slug.'/'.$soc->id.'/events', 'public');
        }
        $event->update($data);

        return redirect()->route('admin.soc.events.index')->with('status', 'Event updated.');
    }

    public function destroy(Request $request, SchoolEvent $event): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $event->school_id === (int) $soc->id, 404);
        $this->deleteStoredImage($soc, $event->image_path);
        $event->delete();

        return redirect()->route('admin.soc.events.index')->with('status', 'Event deleted.');
    }

    private function uniqueSlug(int $schoolId, string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $i = 1;
        while (SchoolEvent::query()
            ->where('school_id', $schoolId)
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    private function deleteStoredImage(School $school, ?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }
        $prefix = $school->slug.'/'.$school->id.'/events/';
        if (str_starts_with($path, $prefix)) {
            Storage::disk('public')->delete($path);
        }
    }
}
