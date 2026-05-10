<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Download;
use App\Models\DownloadCategory;
use App\Models\FormSubmission;
use App\Models\MediaAsset;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $schoolId = $user->isSuperAdmin() ? null : $user->school_id;
        $isSuper = $user->isSuperAdmin();

        $pageQuery = Page::query()->when($schoolId, fn ($q) => $q->where('school_id', $schoolId));
        $downloadQuery = Download::query()->when($schoolId, fn ($q) => $q->where('school_id', $schoolId));
        $newsQuery = NewsPost::query()->when($schoolId, fn ($q) => $q->where('school_id', $schoolId));
        $submissionQuery = FormSubmission::query()->when($schoolId, fn ($q) => $q->where('school_id', $schoolId));
        $mediaQuery = MediaAsset::query()->when($schoolId, fn ($q) => $q->where('school_id', $schoolId));

        $stats = [
            'pages' => (clone $pageQuery)->count(),
            'pages_college' => $isSuper ? Page::query()->whereNull('school_id')->count() : null,
            'downloads' => (clone $downloadQuery)->count(),
            'downloads_active' => (clone $downloadQuery)->where('is_active', true)->count(),
            'news' => (clone $newsQuery)->count(),
            'news_published' => (clone $newsQuery)->published()->count(),
            'submissions_total' => (clone $submissionQuery)->count(),
            'submissions_7d' => (clone $submissionQuery)->where('created_at', '>=', now()->subDays(7))->count(),
            'submissions_30d' => (clone $submissionQuery)->where('created_at', '>=', now()->subDays(30))->count(),
            'submissions_unprocessed' => (clone $submissionQuery)->where('processed', false)->count(),
            'media' => (clone $mediaQuery)->count(),
            'users' => $isSuper
                ? User::query()->count()
                : User::query()->where('school_id', $schoolId)->count(),
            'users_active' => $isSuper
                ? User::query()->where('is_active', true)->count()
                : User::query()->where('school_id', $schoolId)->where('is_active', true)->count(),
            'schools' => $isSuper ? School::query()->where('is_active', true)->count() : null,
            'download_categories' => $isSuper ? DownloadCategory::query()->count() : null,
            'audit_7d' => $this->auditCountLastDays($schoolId, $isSuper, 7),
            'audit_30d' => $this->auditCountLastDays($schoolId, $isSuper, 30),
        ];

        $trendDays = 14;
        $trendStart = now()->subDays($trendDays - 1)->startOfDay();
        $submissionTrend = [];
        for ($i = 0; $i < $trendDays; $i++) {
            $day = $trendStart->copy()->addDays($i);
            $submissionTrend[] = [
                'label' => $day->format('M j'),
                'count' => (clone $submissionQuery)->whereDate('created_at', $day->toDateString())->count(),
            ];
        }

        $submissionsByForm = (clone $submissionQuery)
            ->selectRaw('form_key, COUNT(*) as cnt')
            ->groupBy('form_key')
            ->orderByDesc('cnt')
            ->limit(10)
            ->get()
            ->mapWithKeys(fn ($row) => [$row->form_key => (int) $row->cnt]);

        $contentMix = [
            'pages' => (clone $pageQuery)->count(),
            'downloads' => (clone $downloadQuery)->count(),
            'news' => (clone $newsQuery)->published()->count(),
            'media' => (clone $mediaQuery)->count(),
        ];

        $chartData = [
            'trend' => $submissionTrend,
            'forms' => $submissionsByForm,
            'contentMix' => $contentMix,
        ];

        $recentActivity = AuditLog::query()
            ->with('user')
            ->when(! $isSuper && $schoolId, fn ($q) => $q->whereHas('user', fn ($uq) => $uq->where('school_id', $schoolId)))
            ->latest()
            ->limit(12)
            ->get();

        $recentNews = (clone $newsQuery)
            ->published()
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'chartData' => $chartData,
            'recentActivity' => $recentActivity,
            'recentNews' => $recentNews,
            'isSuper' => $isSuper,
        ]);
    }

    private function auditCountLastDays(?int $schoolId, bool $isSuper, int $days): int
    {
        $q = AuditLog::query()->where('created_at', '>=', now()->subDays($days));

        if (! $isSuper && $schoolId) {
            $q->whereHas('user', fn ($uq) => $uq->where('school_id', $schoolId));
        }

        return $q->count();
    }
}
