<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\DownloadCategory;
use App\Models\School;
use App\Support\Seo\SeoPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicDownloadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Download::query()->published()->with(['school', 'category'])->orderByDesc('published_at');

        if ($request->filled('q')) {
            $q = $request->string('q')->trim();
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', '%'.$q.'%')
                    ->orWhere('description', 'like', '%'.$q.'%');
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($c) => $c->where('slug', $request->string('category')));
        }

        if ($request->filled('school')) {
            $query->whereHas('school', fn ($s) => $s->where('slug', $request->string('school')));
        }

        $downloads = $query->paginate(12)->withQueryString();
        $categories = DownloadCategory::query()->orderBy('sort_order')->orderBy('name')->get();
        $schools = School::query()->where('is_active', true)->orderBy('sort_order')->get();

        $filterSchool = null;
        if ($request->filled('school')) {
            $filterSchool = School::query()
                ->where('slug', $request->string('school'))
                ->where('is_active', true)
                ->first();
        }

        $canonicalQuery = array_filter([
            'school' => $request->get('school'),
            'category' => $request->get('category'),
            'q' => $request->get('q'),
        ], fn ($v) => filled($v));

        $breadcrumbs = [
            ['label' => 'Home', 'href' => route('home')],
            ['label' => 'Downloads', 'href' => route('downloads.index')],
        ];
        $title = 'Downloads & forms | '.config('tenwek.name');
        $description = 'Admission forms, clinical placement documents, policies, and student resources from Tenwek Hospital College.';

        if ($filterSchool) {
            $title = $filterSchool->name.' — Downloads & forms | '.config('tenwek.name');
            $description = 'Forms, policies, and resources for '.$filterSchool->name.' at '.config('tenwek.name').'.';
            $breadcrumbs[] = [
                'label' => $filterSchool->name,
                'href' => route('schools.show', $filterSchool),
            ];
        }

        $seo = SeoPresenter::build($request, [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonicalQuery !== [] ? route('downloads.index', $canonicalQuery) : route('downloads.index'),
            'breadcrumbs' => $breadcrumbs,
        ]);

        return view('downloads.index', compact('seo', 'downloads', 'categories', 'schools', 'filterSchool'));
    }

    public function show(Request $request, string $slug): View
    {
        $download = Download::query()->where('slug', $slug)->published()->firstOrFail();
        $download->load(['school', 'category', 'relatedDownloads' => fn ($q) => $q->published()]);

        $breadcrumbs = [
            ['label' => 'Home', 'href' => route('home')],
        ];
        if ($download->school) {
            $breadcrumbs[] = ['label' => $download->school->name, 'href' => route('schools.show', $download->school)];
            $breadcrumbs[] = [
                'label' => 'Downloads',
                'href' => route('downloads.index', ['school' => $download->school->slug]),
            ];
        } else {
            $breadcrumbs[] = ['label' => 'Downloads', 'href' => route('downloads.index')];
        }
        $breadcrumbs[] = ['label' => $download->title, 'href' => route('downloads.show', $download)];

        $seo = SeoPresenter::build($request, [
            'title' => $download->seo_title ?? $download->title,
            'description' => $download->seo_description ?? $download->description ?? 'Download: '.$download->title,
            'canonical' => route('downloads.show', $download),
            'breadcrumbs' => $breadcrumbs,
        ]);

        return view('downloads.show', compact('seo', 'download'));
    }

    public function file(string $slug): StreamedResponse
    {
        $download = Download::query()->where('slug', $slug)->published()->firstOrFail();

        if (! $download->hasFile()) {
            abort(404);
        }

        $path = $download->file_path;

        if (! Storage::disk('downloads')->exists($path)) {
            abort(404);
        }

        $download->increment('download_count');

        return Storage::disk('downloads')->download($path, $download->original_filename ?? basename($path));
    }
}
