<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\SocTestimonial;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocTestimonialController extends BaseSocAdminController
{
    public function index(Request $request): View
    {
        $soc = $this->socSchool($request);
        $testimonials = SocTestimonial::query()
            ->where('school_id', $soc->id)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.soc.testimonials.index', compact('soc', 'testimonials'));
    }

    public function create(Request $request): View
    {
        $soc = $this->socSchool($request);

        return view('admin.soc.testimonials.create', compact('soc'));
    }

    public function store(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
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
        $data['school_id'] = $soc->id;
        $data['is_published'] = $request->boolean('is_published', true);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('soc/'.$soc->id.'/testimonials', 'public');
        }
        SocTestimonial::query()->create($data);
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.testimonials.index')->with('status', 'Testimonial created.');
    }

    public function edit(Request $request, SocTestimonial $testimonial): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $testimonial->school_id === (int) $soc->id, 404);

        return view('admin.soc.testimonials.edit', compact('soc', 'testimonial'));
    }

    public function update(Request $request, SocTestimonial $testimonial): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $testimonial->school_id === (int) $soc->id, 404);
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
            $data['image_path'] = $request->file('image')->store('soc/'.$soc->id.'/testimonials', 'public');
        }
        $testimonial->update($data);
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.testimonials.index')->with('status', 'Testimonial updated.');
    }

    public function destroy(Request $request, SocTestimonial $testimonial): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $testimonial->school_id === (int) $soc->id, 404);
        $testimonial->delete();
        SocLandingRepository::flushCache();

        return redirect()->route('admin.soc.testimonials.index')->with('status', 'Testimonial removed.');
    }
}
