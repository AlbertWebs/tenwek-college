<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSearchController extends Controller
{
    private const LIMIT = 25;

    public function __invoke(Request $request): View
    {
        $this->authorize('viewAny', Download::class);

        $user = $request->user();
        $q = mb_substr(trim((string) $request->input('q', '')), 0, 200);

        $downloadRows = collect();
        $userRows = collect();
        $newsRows = collect();
        $pageRows = collect();

        if ($q !== '') {
            $pattern = '%'.addcslashes($q, '%_\\').'%';

            $dq = Download::query()
                ->with(['school', 'category'])
                ->where(function ($sub) use ($pattern) {
                    $sub->where('title', 'like', $pattern)
                        ->orWhere('slug', 'like', $pattern)
                        ->orWhere('description', 'like', $pattern);
                });
            if (! $user->isSuperAdmin()) {
                $dq->where('school_id', $user->school_id);
            }
            $downloadRows = $dq->orderByDesc('updated_at')
                ->limit(self::LIMIT)
                ->get()
                ->map(fn (Download $download) => [
                    'download' => $download,
                    'url' => route('admin.downloads.edit', $download),
                ]);

            if ($user->isSuperAdmin()) {
                $userRows = User::query()
                    ->with(['school', 'roles'])
                    ->where(function ($sub) use ($pattern) {
                        $sub->where('name', 'like', $pattern)
                            ->orWhere('email', 'like', $pattern);
                    })
                    ->orderBy('name')
                    ->limit(self::LIMIT)
                    ->get()
                    ->map(fn (User $account) => [
                        'user' => $account,
                        'url' => route('admin.users.edit', $account),
                    ]);
            }

            $nq = NewsPost::query()
                ->with('school')
                ->where(function ($sub) use ($pattern) {
                    $sub->where('title', 'like', $pattern)
                        ->orWhere('slug', 'like', $pattern)
                        ->orWhere('excerpt', 'like', $pattern)
                        ->orWhere('body', 'like', $pattern);
                });
            if (! $user->isSuperAdmin()) {
                $nq->where('school_id', $user->school_id);
            }
            $newsRows = $nq->orderByDesc('published_at')
                ->limit(self::LIMIT)
                ->get()
                ->map(fn (NewsPost $post) => [
                    'post' => $post,
                    'admin_url' => $this->adminEditUrlForNews($post, $user),
                    'public_url' => route('news.show', $post),
                ]);

            $pq = Page::query()
                ->with('school')
                ->where(function ($sub) use ($pattern) {
                    $sub->where('title', 'like', $pattern)
                        ->orWhere('slug', 'like', $pattern)
                        ->orWhere('excerpt', 'like', $pattern)
                        ->orWhere('body', 'like', $pattern);
                });
            if (! $user->isSuperAdmin()) {
                $pq->where('school_id', $user->school_id);
            }
            $pageRows = $pq->orderBy('title')
                ->limit(self::LIMIT)
                ->get()
                ->map(fn (Page $page) => [
                    'page' => $page,
                    'admin_url' => $this->adminEditUrlForPage($page, $user),
                    'public_url' => $page->publicUrl(),
                ]);
        }

        $breadcrumbs = [
            ['label' => __('Dashboard'), 'href' => route('admin.dashboard')],
            ['label' => __('Search'), 'href' => route('admin.search', array_filter(['q' => $q !== '' ? $q : null]))],
        ];

        return view('admin.search', [
            'header' => __('Admin search'),
            'title' => __('Admin search').' | '.config('tenwek.name'),
            'breadcrumbs' => $breadcrumbs,
            'q' => $q,
            'downloadRows' => $downloadRows,
            'userRows' => $userRows,
            'newsRows' => $newsRows,
            'pageRows' => $pageRows,
        ]);
    }

    private function adminEditUrlForNews(NewsPost $post, User $user): ?string
    {
        $school = $post->school;
        if ($school === null) {
            return null;
        }
        if (! $user->isSuperAdmin() && ! $user->managesSchool($school)) {
            return null;
        }

        return match ($school->slug) {
            'soc' => route('admin.soc.news.edit', $post),
            'cohs' => route('admin.cohs.news.edit', $post),
            default => null,
        };
    }

    private function adminEditUrlForPage(Page $page, User $user): ?string
    {
        $school = $page->school;
        if ($school === null) {
            return null;
        }
        if (! $user->isSuperAdmin() && ! $user->managesSchool($school)) {
            return null;
        }

        return match ($school->slug) {
            'soc' => route('admin.soc.pages.edit', $page),
            'cohs' => route('admin.cohs.pages.edit', $page),
            default => null,
        };
    }
}
