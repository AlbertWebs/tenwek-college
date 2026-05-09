<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Page;
use App\Models\School;
use App\Support\Cohs\CohsLandingRepository;
use App\Support\CohsApplicationFormDownloads;
use App\Support\Seo\SeoPresenter;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $page = Page::query()
            ->whereNull('school_id')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $seoOverrides = [
            'title' => $page->seo_title ?? $page->title,
            'description' => $page->seo_description ?? $page->excerpt ?? strip_tags((string) $page->body),
            'canonical' => $page->canonical_path ? url($page->canonical_path) : route('pages.show', $page->slug),
            'robots' => $page->robots,
            'breadcrumbs' => [
                ['label' => 'Home', 'href' => route('home')],
                ['label' => $page->title, 'href' => route('pages.show', $page->slug)],
            ],
        ];
        if (filled($page->seo_keywords)) {
            $seoOverrides['keywords'] = $page->seo_keywords;
        }
        if (filled($page->og_title)) {
            $seoOverrides['og_title'] = $page->og_title;
        }
        if ($page->og_image_path) {
            $seoOverrides['image'] = SocLandingRepository::publicMediaUrl($page->og_image_path) ?? asset($page->og_image_path);
        }
        $seo = SeoPresenter::build($request, $seoOverrides);

        return view('pages.show', compact('seo', 'page'));
    }

    public function showSchool(Request $request, School $school, string $pageSlug): View|RedirectResponse
    {
        if (! $school->is_active) {
            abort(404);
        }

        $socCourseSlugs = [];
        if ($school->slug === 'soc') {
            $socLanding = app(SocLandingRepository::class)->forSchool($school);
            $socCourseSlugs = collect($socLanding['academic_programmes']['groups'] ?? [])
                ->flatMap(fn (array $group) => $group['items'] ?? [])
                ->pluck('slug')
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        $page = Page::query()
            ->where('school_id', $school->id)
            ->where('slug', $pageSlug)
            ->published()
            ->first();

        if (! $page && $school->slug === 'soc' && in_array($pageSlug, $socCourseSlugs, true)) {
            $page = $this->syntheticSocProgrammePage($school, $pageSlug);
        }

        if (! $page) {
            abort(404);
        }

        $seoOverrides = [
            'title' => $page->seo_title ?? $page->title,
            'description' => $page->seo_description ?? $page->excerpt ?? strip_tags((string) $page->body),
            'canonical' => route('schools.pages.show', [$school, $page->slug]),
            'robots' => $page->robots,
            'breadcrumbs' => [
                ['label' => 'Home', 'href' => route('home')],
                ['label' => $school->name, 'href' => route('schools.show', $school)],
                ['label' => $page->title, 'href' => route('schools.pages.show', [$school, $page->slug])],
            ],
        ];
        if (filled($page->seo_keywords)) {
            $seoOverrides['keywords'] = $page->seo_keywords;
        }
        if (filled($page->og_title)) {
            $seoOverrides['og_title'] = $page->og_title;
        }
        if ($page->og_image_path) {
            $seoOverrides['image'] = ($school->slug === 'cohs'
                ? CohsLandingRepository::publicMediaUrl($page->og_image_path)
                : SocLandingRepository::publicMediaUrl($page->og_image_path)) ?? asset($page->og_image_path);
        }
        $seo = SeoPresenter::build($request, $seoOverrides);

        if ($school->slug === 'cohs' && $pageSlug === 'oncampus-link') {
            return redirect()->route('cohs.on-campus-application');
        }

        if ($school->slug === 'cohs') {
            return match ($pageSlug) {
                'about-us' => view('schools.cohs.about-us', compact('seo', 'page', 'school')),
                'contact-us' => view('schools.cohs.contact-us', compact('seo', 'page', 'school')),
                'application-forms' => view('schools.cohs.application-forms', [
                    'seo' => $seo,
                    'page' => $page,
                    'school' => $school,
                    'applicationForms' => $this->orderedCohsApplicationForms(),
                ]),
                'diploma-in-clinical-medicine' => view('schools.cohs.diploma-in-clinical-medicine', compact('seo', 'page', 'school')),
                'diploma-in-nursing' => view('schools.cohs.diploma-in-nursing', compact('seo', 'page', 'school')),
                'facilities' => view('schools.cohs.facilities', compact('seo', 'page', 'school')),
                'offcampus-link' => view('schools.cohs.offcampus-link', compact('seo', 'page', 'school')),
                'social-life' => view('schools.cohs.social-life', compact('seo', 'page', 'school')),
                default => view('pages.show', compact('seo', 'page', 'school')),
            };
        }

        if ($school->slug === 'soc') {
            return match (true) {
                $pageSlug === 'our-history' => view('schools.soc.our-history', compact('seo', 'page', 'school')),
                $pageSlug === 'message-from-the-principal' => view('schools.soc.message-from-principal', compact('seo', 'page', 'school')),
                $pageSlug === 'board-and-management-team' => view('schools.soc.board-and-management-team', compact('seo', 'page', 'school')),
                $pageSlug === 'strategic-partners' => view('schools.soc.strategic-partners', compact('seo', 'page', 'school')),
                $pageSlug === 'academic-programmes' => view('schools.soc.academic-programmes', compact('seo', 'page', 'school')),
                $pageSlug === 'fee' => view('schools.soc.fee', compact('seo', 'page', 'school')),
                $pageSlug === 'admissions' => view('schools.soc.admissions', compact('seo', 'page', 'school')),
                $pageSlug === 'gallery' => view('schools.soc.gallery', compact('seo', 'page', 'school')),
                $pageSlug === 'faqs' => view('schools.soc.faqs', compact('seo', 'page', 'school')),
                in_array($pageSlug, $socCourseSlugs, true) => view('schools.soc.programme-show', compact('seo', 'page', 'school')),
                default => view('pages.show', compact('seo', 'page', 'school')),
            };
        }

        return view('pages.show', compact('seo', 'page', 'school'));
    }

    /**
     * When academic programme rows are missing from the database (e.g. seed not run),
     * still render programme pages from config so /soc/{slug} links never 404.
     */
    private function syntheticSocProgrammePage(School $school, string $pageSlug): Page
    {
        $socLanding = app(SocLandingRepository::class)->forSchool($school);
        $item = collect($socLanding['academic_programmes']['groups'] ?? [])
            ->flatMap(fn (array $group) => $group['items'] ?? [])
            ->firstWhere('slug', $pageSlug);

        if ($item === null) {
            abort(404);
        }

        $title = $item['title'] ?? Str::title(str_replace('-', ' ', $pageSlug));
        $summary = (string) ($item['summary'] ?? '');
        $admissions = route('schools.pages.show', [$school, 'admissions']);
        $fees = route('schools.pages.show', [$school, 'fee']);

        $defaultBody = '<p>'.e($summary).'</p>'
            .'<p class="mt-6 flex flex-wrap gap-x-4 gap-y-2">'
            .'<a class="font-semibold text-thc-royal hover:underline" href="'.e($admissions).'">'.e(__('Apply online')).'</a>'
            .'<span class="text-thc-text/35" aria-hidden="true">·</span>'
            .'<a class="font-semibold text-thc-royal hover:underline" href="'.e($fees).'">'.e(__('View fees')).'</a>'
            .'</p>';
        $body = filled($item['body'] ?? null) ? (string) $item['body'] : $defaultBody;

        $defaultSeoTitle = $title.' | '.$school->name.' | '.config('tenwek.name');

        return new Page([
            'school_id' => $school->id,
            'slug' => $pageSlug,
            'title' => $title,
            'excerpt' => Str::limit($summary, 240),
            'body' => $body,
            'published_at' => now(),
            'seo_title' => filled($item['seo_title'] ?? null) ? (string) $item['seo_title'] : $defaultSeoTitle,
            'seo_description' => filled($item['seo_description'] ?? null) ? (string) $item['seo_description'] : Str::limit($summary, 160),
            'seo_keywords' => filled($item['seo_keywords'] ?? null) ? (string) $item['seo_keywords'] : null,
            'og_title' => filled($item['og_title'] ?? null) ? (string) $item['og_title'] : null,
            'og_image_path' => filled($item['og_image_path'] ?? null) ? (string) $item['og_image_path'] : null,
        ]);
    }

    /**
     * @return Collection<int, Download>
     */
    private function orderedCohsApplicationForms(): Collection
    {
        $slugs = CohsApplicationFormDownloads::orderedSlugs();
        $downloads = Download::query()
            ->whereIn('slug', $slugs)
            ->published()
            ->get()
            ->keyBy('slug');

        return collect($slugs)
            ->map(fn (string $slug) => $downloads->get($slug))
            ->filter()
            ->values();
    }
}
