<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $logs = AuditLog::query()
            ->with(['user', 'auditable'])
            ->latest()
            ->paginate(50)
            ->withQueryString();

        return view('admin.activity.index', compact('logs'));
    }
}
