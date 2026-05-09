<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Support\Cohs\CohsLandingRepository;
use App\Support\Seo\SeoPresenter;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function show(Request $request, School $school): View
    {
        if (! $school->is_active) {
            abort(404);
        }

        if ($school->slug === 'soc') {
            $seo = SeoPresenter::build($request, array_merge([
                'title' => $school->name.' | '.config('tenwek.name'),
                'description' => $school->excerpt ?? $school->tagline ?? config('tenwek.tagline'),
                'canonical' => route('schools.show', $school),
                'breadcrumbs' => [
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => $school->name, 'href' => route('schools.show', $school)],
                ],
            ], app(SocLandingRepository::class)->seoPayloadForBuild($school)));

            return view('schools.soc.landing', compact('seo', 'school'));
        }

        if ($school->slug === 'cohs') {
            $cohsRepo = app(CohsLandingRepository::class);
            $cohsL = $cohsRepo->forSchool($school);
            $hero = $cohsL['hero'] ?? [];
            $badge = is_array($hero) ? (string) ($hero['badge'] ?? '') : '';
            $subhead = is_array($hero) ? (string) ($hero['subhead'] ?? '') : '';
            $seo = SeoPresenter::build($request, array_merge([
                'title' => $school->name.' · '.$badge.' | '.config('tenwek.name'),
                'description' => $subhead !== '' ? $subhead : ($school->excerpt ?? $school->tagline ?? config('tenwek.tagline')),
                'canonical' => route('schools.show', $school),
                'breadcrumbs' => [
                    ['label' => 'Home', 'href' => route('home')],
                    ['label' => $school->name, 'href' => route('schools.show', $school)],
                ],
            ], $cohsRepo->seoPayloadForBuild($school)));

            return view('schools.cohs.landing', compact('seo', 'school'));
        }

        $seo = SeoPresenter::build($request, [
            'title' => $school->name.' | '.config('tenwek.name'),
            'description' => $school->excerpt ?? $school->tagline ?? config('tenwek.tagline'),
            'canonical' => route('schools.show', $school),
            'breadcrumbs' => [
                ['label' => 'Home', 'href' => route('home')],
                ['label' => $school->name, 'href' => route('schools.show', $school)],
            ],
        ]);

        return view('schools.show', compact('seo', 'school'));
    }
}
