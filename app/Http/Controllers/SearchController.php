<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\School;
use App\Support\Seo\SeoPresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    private const PER_SECTION = 20;

    public function __invoke(Request $request): View|RedirectResponse
    {
        $q = mb_substr(trim((string) $request->input('q', '')), 0, 200);

        $schoolFromQuery = $request->filled('school');
        if ($schoolFromQuery) {
            $filterSchool = School::query()
                ->where('slug', $request->string('school'))
                ->where('is_active', true)
                ->first();
        } else {
            $filterSchool = $this->schoolFromSameSiteReferer($request);
            if ($filterSchool !== null) {
                return redirect()->route('search', array_filter([
                    'q' => $q !== '' ? $q : null,
                    'school' => $filterSchool->slug,
                ]));
            }
        }

        $searchQuery = array_filter([
            'q' => $q !== '' ? $q : null,
            'school' => $request->get('school'),
        ], fn ($v) => filled($v));

        $seo = SeoPresenter::build($request, [
            'title' => $q !== ''
                ? 'Search results for “'.$q.'” | '.config('tenwek.name')
                : 'Search | '.config('tenwek.name'),
            'description' => $q !== ''
                ? 'Results across pages, news, downloads, and schools for “'.$q.'”.'
                : 'Search Tenwek Hospital College pages, news, forms, and downloads.',
            'canonical' => route('search', $searchQuery),
            'robots' => 'noindex,follow',
            'breadcrumbs' => [
                ['label' => 'Home', 'href' => route('home')],
                ['label' => 'Search', 'href' => route('search', $searchQuery)],
            ],
        ]);

        if ($q === '') {
            return view('search.results', [
                'seo' => $seo,
                'q' => '',
                'filterSchool' => $filterSchool,
                'schools' => collect(),
                'schoolTotal' => 0,
                'pages' => collect(),
                'pageTotal' => 0,
                'newsPosts' => collect(),
                'newsTotal' => 0,
                'downloads' => collect(),
                'downloadTotal' => 0,
            ]);
        }

        $pattern = '%'.addcslashes($q, '%_\\').'%';

        $schoolQuery = School::query()
            ->where('is_active', true)
            ->where(function ($sub) use ($pattern) {
                $sub->where('name', 'like', $pattern)
                    ->orWhere('tagline', 'like', $pattern)
                    ->orWhere('excerpt', 'like', $pattern);
            });
        $schoolTotal = (clone $schoolQuery)->count();
        $schools = (clone $schoolQuery)->orderBy('sort_order')->orderBy('name')->limit(self::PER_SECTION)->get();

        $pageQuery = Page::query()
            ->published()
            ->with('school')
            ->where(function ($sub) use ($pattern) {
                $sub->where('title', 'like', $pattern)
                    ->orWhere('slug', 'like', $pattern)
                    ->orWhere('excerpt', 'like', $pattern)
                    ->orWhere('body', 'like', $pattern);
            });
        $pageTotal = (clone $pageQuery)->count();
        $pages = (clone $pageQuery)->orderBy('title')->limit(self::PER_SECTION)->get();

        $newsQuery = NewsPost::query()
            ->published()
            ->with('school')
            ->where(function ($sub) use ($pattern) {
                $sub->where('title', 'like', $pattern)
                    ->orWhere('slug', 'like', $pattern)
                    ->orWhere('excerpt', 'like', $pattern)
                    ->orWhere('body', 'like', $pattern);
            });
        $newsTotal = (clone $newsQuery)->count();
        $newsPosts = (clone $newsQuery)->orderByDesc('published_at')->limit(self::PER_SECTION)->get();

        $downloadQuery = Download::query()
            ->published()
            ->with(['school', 'category'])
            ->where(function ($sub) use ($pattern) {
                $sub->where('title', 'like', $pattern)
                    ->orWhere('slug', 'like', $pattern)
                    ->orWhere('description', 'like', $pattern);
            });
        $downloadTotal = (clone $downloadQuery)->count();
        $downloads = (clone $downloadQuery)->orderByDesc('published_at')->limit(self::PER_SECTION)->get();

        return view('search.results', compact(
            'seo',
            'q',
            'filterSchool',
            'schools',
            'schoolTotal',
            'pages',
            'pageTotal',
            'newsPosts',
            'newsTotal',
            'downloads',
            'downloadTotal',
        ));
    }

    private function schoolFromSameSiteReferer(Request $request): ?School
    {
        $referer = (string) $request->headers->get('referer', '');
        if ($referer === '') {
            return null;
        }

        $parsed = parse_url($referer);
        $refererHost = $parsed['host'] ?? '';
        if ($refererHost === '' || ! hash_equals($request->getHost(), $refererHost)) {
            return null;
        }

        $path = $parsed['path'] ?? '/';
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        $first = $segments[0] ?? null;
        if (in_array($first, ['cohs', 'soc'], true)) {
            return School::query()
                ->where('slug', $first)
                ->where('is_active', true)
                ->first();
        }

        if (! empty($parsed['query'])) {
            parse_str((string) $parsed['query'], $query);
            $slug = $query['school'] ?? null;
            if (in_array($slug, ['cohs', 'soc'], true)) {
                return School::query()
                    ->where('slug', $slug)
                    ->where('is_active', true)
                    ->first();
            }
        }

        return null;
    }
}
