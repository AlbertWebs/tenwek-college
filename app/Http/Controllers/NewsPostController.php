<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use App\Support\Seo\SeoPresenter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsPostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = NewsPost::query()
            ->published()
            ->with('school')
            ->orderByDesc('published_at')
            ->paginate(9);

        $seo = SeoPresenter::build($request, [
            'title' => 'News & events | '.config('tenwek.name'),
            'description' => 'Announcements, academic updates, and community stories from Tenwek Hospital College.',
            'canonical' => route('news.index'),
            'breadcrumbs' => [
                ['label' => 'Home', 'href' => route('home')],
                ['label' => 'News & events', 'href' => route('news.index')],
            ],
        ]);

        return view('news.index', compact('seo', 'posts'));
    }

    public function show(Request $request, NewsPost $post): View
    {
        $post->load('school');

        $schemaArticle = SeoPresenter::schemaArticle(
            $post->title,
            $post->excerpt ?? strip_tags((string) $post->body),
            route('news.show', $post),
            $post->published_at->toIso8601String(),
            $post->featuredImagePublicUrl(),
        );

        $seo = SeoPresenter::build($request, [
            'title' => $post->seo_title ?? $post->title,
            'description' => $post->seo_description ?? $post->excerpt ?? strip_tags((string) $post->body),
            'canonical' => route('news.show', $post),
            'og_type' => 'article',
            'schema' => [$schemaArticle],
            'breadcrumbs' => array_values(array_filter([
                ['label' => 'Home', 'href' => route('home')],
                ['label' => 'News & events', 'href' => route('news.index')],
                ['label' => $post->title, 'href' => route('news.show', $post)],
            ])),
        ]);

        return view('news.show', compact('seo', 'post'));
    }
}
