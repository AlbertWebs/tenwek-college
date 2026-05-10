<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolEvent;
use App\Support\Seo\SeoPresenter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolEventController extends Controller
{
    public function index(Request $request, School $school): View
    {
        if (! $school->is_active || ! in_array($school->slug, ['soc', 'cohs'], true)) {
            abort(404);
        }

        $events = SchoolEvent::query()
            ->where('school_id', $school->id)
            ->published()
            ->orderByDesc('starts_at')
            ->paginate(12);

        $schoolLabel = $school->slug === 'soc'
            ? 'School of Chaplaincy'
            : 'College of Health Sciences';

        $seo = SeoPresenter::build($request, [
            'title' => 'Events | '.$school->name.' | '.config('tenwek.name'),
            'description' => 'Upcoming and recent events at '.$school->name.'.',
            'canonical' => route('schools.events.index', $school),
            'breadcrumbs' => [
                ['label' => 'Home', 'href' => route('home')],
                ['label' => $school->name, 'href' => route('schools.show', $school)],
                ['label' => 'Events', 'href' => route('schools.events.index', $school)],
            ],
        ]);

        return view('schools.events.index', compact('seo', 'school', 'events', 'schoolLabel'));
    }

    public function show(Request $request, School $school, string $eventSlug): View
    {
        if (! $school->is_active || ! in_array($school->slug, ['soc', 'cohs'], true)) {
            abort(404);
        }

        $event = SchoolEvent::query()
            ->where('school_id', $school->id)
            ->where('slug', $eventSlug)
            ->published()
            ->firstOrFail();

        $img = $event->imagePublicUrl();
        $seo = SeoPresenter::build($request, [
            'title' => $event->seo_title ?? $event->title,
            'description' => $event->seo_description ?? $event->excerpt ?? strip_tags((string) $event->body),
            'canonical' => route('schools.events.show', [$school, $event->slug]),
            'og_type' => 'article',
            'image' => $img,
            'breadcrumbs' => [
                ['label' => 'Home', 'href' => route('home')],
                ['label' => $school->name, 'href' => route('schools.show', $school)],
                ['label' => 'Events', 'href' => route('schools.events.index', $school)],
                ['label' => $event->title, 'href' => route('schools.events.show', [$school, $event->slug])],
            ],
        ]);

        return view('schools.events.show', compact('seo', 'school', 'event'));
    }
}
