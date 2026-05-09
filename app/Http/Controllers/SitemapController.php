<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\School;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $lines = collect();
        $lines->push('<?xml version="1.0" encoding="UTF-8"?>');
        $lines->push('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');

        $xmlLoc = fn (string $u): string => htmlspecialchars($u, ENT_XML1 | ENT_COMPAT, 'UTF-8');

        $add = function (string $loc, ?string $lastmod = null) use ($lines, $xmlLoc): void {
            $lines->push('<url><loc>'.$xmlLoc($loc).'</loc>');
            if ($lastmod !== null) {
                $lines->push('<lastmod>'.$xmlLoc($lastmod).'</lastmod>');
            }
            $lines->push('</url>');
        };

        $add(route('home'), now()->toDateString());
        $add(route('downloads.index'));
        $add(route('news.index'));
        $add(route('contact.show'));

        foreach (School::query()->where('is_active', true)->cursor() as $school) {
            $add(route('schools.show', $school), $school->updated_at?->toDateString());
        }

        foreach (Page::query()->whereNull('school_id')->published()->cursor() as $page) {
            $add(route('pages.show', $page->slug), $page->updated_at?->toDateString());
        }

        foreach (Page::query()->whereNotNull('school_id')->published()->with('school')->cursor() as $page) {
            if ($page->school) {
                $add(route('schools.pages.show', [$page->school, $page->slug]), $page->updated_at?->toDateString());
            }
        }

        foreach (Download::query()->published()->cursor() as $download) {
            $add(route('downloads.show', $download), $download->updated_at?->toDateString());
        }

        foreach (NewsPost::query()->published()->cursor() as $post) {
            $add(route('news.show', $post), $post->updated_at?->toDateString());
        }

        $lines->push('</urlset>');
        $xml = $lines->implode('');

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
