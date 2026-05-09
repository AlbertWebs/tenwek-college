<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\FormSubmission;
use App\Models\MediaAsset;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\SocNavItem;
use App\Models\SocProgrammeGroup;
use App\Models\SocTeamMember;
use App\Models\SocTestimonial;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocDashboardController extends BaseSocAdminController
{
    public function index(Request $request): View
    {
        $soc = $this->socSchool($request);

        $stats = [
            'pages' => Page::query()->where('school_id', $soc->id)->count(),
            'news' => NewsPost::query()->where('school_id', $soc->id)->count(),
            'testimonials' => SocTestimonial::query()->where('school_id', $soc->id)->count(),
            'nav_items' => SocNavItem::query()->where('school_id', $soc->id)->count(),
            'team' => SocTeamMember::query()->where('school_id', $soc->id)->count(),
            'programme_groups' => SocProgrammeGroup::query()->where('school_id', $soc->id)->count(),
            'media' => MediaAsset::query()->where('school_id', $soc->id)->count(),
            'submissions_7d' => FormSubmission::query()
                ->where('school_id', $soc->id)
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return view('admin.soc.dashboard', compact('soc', 'stats'));
    }
}
