<?php

namespace App\Http\Controllers\Admin\Cohs;

use App\Models\CohsTestimonial;
use App\Support\Cohs\CohsLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CohsTestimonialController extends BaseCohsAdminController
{
    public function index(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $testimonials = CohsTestimonial::query()
            ->where('school_id', $cohs->id)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.cohs.testimonials.index', compact('cohs', 'testimonials'));
    }

    public function create(Request $request): View
    {
        $cohs = $this->cohsSchool($request);

        return view('admin.cohs.testimonials.create', compact('cohs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:10000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_published' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
        $data = collect($validated)->except('image')->all();
        $data['school_id'] = $cohs->id;
        $data['is_published'] = $request->boolean('is_published', true);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('cohs/'.$cohs->id.'/testimonials', 'public');
        }
        CohsTestimonial::query()->create($data);
        CohsLandingRepository::flushCache();

        return redirect()->route('admin.cohs.testimonials.index')->with('status', 'Testimonial created.');
    }

    public function edit(Request $request, CohsTestimonial $testimonial): View
    {
        $cohs = $this->cohsSchool($request);
        abort_unless((int) $testimonial->school_id === (int) $cohs->id, 404);

        return view('admin.cohs.testimonials.edit', compact('cohs', 'testimonial'));
    }

    public function update(Request $request, CohsTestimonial $testimonial): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        abort_unless((int) $testimonial->school_id === (int) $cohs->id, 404);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:10000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_published' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
        $data = collect($validated)->except('image')->all();
        $data['is_published'] = $request->boolean('is_published', true);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('cohs/'.$cohs->id.'/testimonials', 'public');
        }
        $testimonial->update($data);
        CohsLandingRepository::flushCache();

        return redirect()->route('admin.cohs.testimonials.index')->with('status', 'Testimonial updated.');
    }

    public function destroy(Request $request, CohsTestimonial $testimonial): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        abort_unless((int) $testimonial->school_id === (int) $cohs->id, 404);
        $testimonial->delete();
        CohsLandingRepository::flushCache();

        return redirect()->route('admin.cohs.testimonials.index')->with('status', 'Testimonial removed.');
    }
}
