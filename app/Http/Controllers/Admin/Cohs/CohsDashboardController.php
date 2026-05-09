<?php

namespace App\Http\Controllers\Admin\Cohs;

use App\Models\CohsNavItem;
use App\Models\CohsTestimonial;
use App\Models\FormSubmission;
use App\Models\MediaAsset;
use App\Models\NewsPost;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CohsDashboardController extends BaseCohsAdminController
{
    public function index(Request $request): View
    {
        $cohs = $this->cohsSchool($request);

        $stats = [
            'pages' => Page::query()->where('school_id', $cohs->id)->count(),
            'news' => NewsPost::query()->where('school_id', $cohs->id)->count(),
            'testimonials' => CohsTestimonial::query()->where('school_id', $cohs->id)->count(),
            'nav_items' => CohsNavItem::query()->where('school_id', $cohs->id)->count(),
            'media' => MediaAsset::query()->where('school_id', $cohs->id)->count(),
            'submissions_7d' => FormSubmission::query()
                ->where('school_id', $cohs->id)
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return view('admin.cohs.dashboard', compact('cohs', 'stats'));
    }
}
