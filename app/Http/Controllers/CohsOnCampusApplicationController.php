<?php

namespace App\Http\Controllers;

use App\Http\Requests\CohsOnCampusApplicationStoreRequest;
use App\Models\FormSubmission;
use App\Models\School;
use App\Support\CohsHealthSciencesApplicationOptions;
use App\Support\CohsKenyaCounties;
use App\Support\Seo\SeoPresenter;
use App\Support\SocRegistrationCountries;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CohsOnCampusApplicationController extends Controller
{
    public function show(Request $request): View
    {
        $school = School::query()->where('slug', 'cohs')->where('is_active', true)->firstOrFail();

        $seo = SeoPresenter::build($request, [
            'title' => 'On-campus online application | '.$school->name.' | '.config('tenwek.name'),
            'description' => 'Apply to Tenwek Hospital College, School of Health Sciences: online application portal.',
            'canonical' => route('cohs.on-campus-application'),
            'breadcrumbs' => [
                ['label' => 'Home', 'href' => route('home')],
                ['label' => $school->name, 'href' => route('schools.show', $school)],
                ['label' => 'On-campus application', 'href' => route('cohs.on-campus-application')],
            ],
        ]);

        $countries = SocRegistrationCountries::all();
        $counties = CohsKenyaCounties::all();
        $programmes = CohsHealthSciencesApplicationOptions::programmes();
        $studyModes = CohsHealthSciencesApplicationOptions::studyModes();
        $campuses = CohsHealthSciencesApplicationOptions::campuses();

        return view('schools.cohs.on-campus-application', compact(
            'seo',
            'school',
            'countries',
            'counties',
            'programmes',
            'studyModes',
            'campuses',
        ));
    }

    public function store(CohsOnCampusApplicationStoreRequest $request): RedirectResponse
    {
        $school = School::query()->where('slug', 'cohs')->where('is_active', true)->firstOrFail();

        $validated = $request->validated();

        $parents = array_values(array_filter(
            $validated['parents'] ?? [],
            fn (array $row): bool => filled($row['full_name'] ?? null)
        ));

        $educationClean = [];
        foreach ($validated['education'] ?? [] as $row) {
            if (! filled($row['institution_name'] ?? null)) {
                continue;
            }
            $educationClean[] = Arr::except($row, ['transcript']);
        }

        $payload = Arr::except($validated, [
            'parents', 'education', 'proof_of_payment', 'profile_picture', 'fax', 'birth_cert_no_confirmation',
        ]);
        $payload['parents'] = $parents;
        $payload['education'] = $educationClean;
        $payload['programme_label'] = CohsHealthSciencesApplicationOptions::labelForProgramme($validated['programme']);

        $submission = FormSubmission::query()->create([
            'form_key' => 'cohs_on_campus_application',
            'school_id' => $school->id,
            'payload' => $payload,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
        ]);

        $base = 'cohs-on-campus/'.$submission->id;
        $filePaths = [
            'proof_of_payment_path' => $request->file('proof_of_payment')->store($base, 'local'),
            'profile_picture_path' => $request->file('profile_picture')->store($base, 'local'),
        ];

        $eduStored = [];
        foreach (range(0, 4) as $i) {
            if ($request->hasFile("education.{$i}.transcript")) {
                $eduStored[$i] = $request->file("education.{$i}.transcript")->store($base.'/education', 'local');
            }
        }

        $educationPayload = [];
        foreach ($validated['education'] ?? [] as $i => $row) {
            if (! filled($row['institution_name'] ?? null)) {
                continue;
            }
            $rowOut = Arr::except($row, ['transcript']);
            $rowOut['transcript_path'] = $eduStored[$i] ?? null;
            $educationPayload[] = $rowOut;
        }

        $submission->update([
            'payload' => array_merge($submission->payload, $filePaths, [
                'education' => $educationPayload,
            ]),
        ]);

        $to = config('mail.from.address');
        if ($to && filter_var($to, FILTER_VALIDATE_EMAIL)) {
            try {
                $lines = [
                    'New COHS on-campus online application',
                    'Submission ID: '.$submission->id,
                    'Applicant: '.($validated['first_name'] ?? '').' '.($validated['last_name'] ?? ''),
                    'Email: '.($validated['email'] ?? ''),
                    'Programme: '.($payload['programme_label'] ?? ''),
                ];
                Mail::raw(implode("\n", $lines), function ($message) use ($to, $validated): void {
                    $message->to($to)->subject('COHS application: '.($validated['last_name'] ?? 'Applicant'));
                });
            } catch (\Throwable) {
                // Stored; mail optional
            }
        }

        return redirect()
            ->route('cohs.on-campus-application')
            ->with('status', __('Thank you. Your application has been received. We will contact you using the details you provided.'));
    }
}
