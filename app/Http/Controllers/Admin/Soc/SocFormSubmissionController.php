<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\FormSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocFormSubmissionController extends BaseSocAdminController
{
    /** @var list<string> */
    public const FORM_KEYS = ['contact', 'soc_chaplaincy_registration'];

    public function index(Request $request): View
    {
        $soc = $this->socSchool($request);
        $submissions = FormSubmission::query()
            ->where('school_id', $soc->id)
            ->whereIn('form_key', self::FORM_KEYS)
            ->orderByDesc('id')
            ->paginate(25);

        return view('admin.soc.submissions.index', compact('soc', 'submissions'));
    }

    public function show(Request $request, FormSubmission $submission): View
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $submission->school_id === (int) $soc->id, 404);
        abort_unless(in_array($submission->form_key, self::FORM_KEYS, true), 404);

        return view('admin.soc.submissions.show', compact('soc', 'submission'));
    }

    public function update(Request $request, FormSubmission $submission): RedirectResponse
    {
        $soc = $this->socSchool($request);
        abort_unless((int) $submission->school_id === (int) $soc->id, 404);
        abort_unless(in_array($submission->form_key, self::FORM_KEYS, true), 404);
        $validated = $request->validate([
            'processed' => ['required', 'boolean'],
        ]);
        $submission->update(['processed' => $validated['processed']]);

        return redirect()->route('admin.soc.submissions.show', $submission)->with('status', 'Submission updated.');
    }
}
