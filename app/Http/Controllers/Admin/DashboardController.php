<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\FormSubmission;
use App\Models\NewsPost;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $schoolId = $user->isSuperAdmin() ? null : $user->school_id;

        $pageQuery = Page::query();
        $downloadQuery = Download::query();
        $newsQuery = NewsPost::query();

        if ($schoolId !== null) {
            $pageQuery->where('school_id', $schoolId);
            $downloadQuery->where('school_id', $schoolId);
            $newsQuery->where('school_id', $schoolId);
        }

        $stats = [
            'pages' => (clone $pageQuery)->count(),
            'downloads' => (clone $downloadQuery)->count(),
            'news' => (clone $newsQuery)->count(),
            'submissions' => FormSubmission::query()
                ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
