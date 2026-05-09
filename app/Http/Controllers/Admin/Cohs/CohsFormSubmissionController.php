<?php

namespace App\Http\Controllers\Admin\Cohs;

use App\Models\FormSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CohsFormSubmissionController extends BaseCohsAdminController
{
    /** @var list<string> */
    public const FORM_KEYS = ['contact', 'cohs_on_campus_application'];

    public function index(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $submissions = FormSubmission::query()
            ->where('school_id', $cohs->id)
            ->whereIn('form_key', self::FORM_KEYS)
            ->orderByDesc('id')
            ->paginate(25);

        return view('admin.cohs.submissions.index', compact('cohs', 'submissions'));
    }

    public function show(Request $request, FormSubmission $submission): View
    {
        $cohs = $this->cohsSchool($request);
        abort_unless((int) $submission->school_id === (int) $cohs->id, 404);
        abort_unless(in_array($submission->form_key, self::FORM_KEYS, true), 404);

        return view('admin.cohs.submissions.show', compact('cohs', 'submission'));
    }

    public function update(Request $request, FormSubmission $submission): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        abort_unless((int) $submission->school_id === (int) $cohs->id, 404);
        abort_unless(in_array($submission->form_key, self::FORM_KEYS, true), 404);
        $validated = $request->validate([
            'processed' => ['required', 'boolean'],
        ]);
        $submission->update(['processed' => $validated['processed']]);

        return redirect()->route('admin.cohs.submissions.show', $submission)->with('status', 'Submission updated.');
    }
}
