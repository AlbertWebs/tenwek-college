<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocRegistrationStoreRequest;
use App\Models\FormSubmission;
use App\Models\Page;
use App\Models\School;
use App\Support\Seo\SeoPresenter;
use App\Support\SocRegistrationCountries;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SocRegistrationController extends Controller
{
    public function show(Request $request): View
    {
        $school = School::query()->where('slug', 'soc')->where('is_active', true)->firstOrFail();

        $page = Page::query()
            ->where('school_id', $school->id)
            ->where('slug', 'register')
            ->published()
            ->firstOrFail();

        $seoOverrides = [
            'title' => $page->seo_title ?? $page->title.' | '.$school->name.' | '.config('tenwek.name'),
            'description' => $page->seo_description ?? $page->excerpt ?? 'Online application for School of Chaplaincy programmes.',
            'canonical' => route('soc.register'),
            'robots' => $page->robots,
            'breadcrumbs' => [
                ['label' => 'Home', 'href' => route('home')],
                ['label' => $school->name, 'href' => route('schools.show', $school)],
                ['label' => $page->title, 'href' => route('soc.register')],
            ],
        ];
        if ($page->og_image_path) {
            $seoOverrides['image'] = asset($page->og_image_path);
        }
        $seo = SeoPresenter::build($request, $seoOverrides);

        $countries = SocRegistrationCountries::all();

        return view('schools.soc.register', compact('seo', 'school', 'page', 'countries'));
    }

    public function store(SocRegistrationStoreRequest $request): RedirectResponse
    {
        $school = School::query()->where('slug', 'soc')->where('is_active', true)->firstOrFail();

        $payload = collect($request->validated())
            ->except(['bank_slip', 'photograph', 'certificates'])
            ->all();

        $submission = FormSubmission::query()->create([
            'form_key' => 'soc_chaplaincy_registration',
            'school_id' => $school->id,
            'payload' => $payload,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
        ]);

        $base = 'soc-registrations/'.$submission->id;
        $files = [
            'bank_slip_path' => $request->file('bank_slip')->store($base, 'local'),
            'photograph_path' => $request->file('photograph')->store($base, 'local'),
            'certificates_path' => $request->file('certificates')->store($base, 'local'),
        ];

        $submission->update([
            'payload' => array_merge($submission->payload, $files),
        ]);

        $to = config('mail.from.address');
        if ($to && filter_var($to, FILTER_VALIDATE_EMAIL)) {
            try {
                $lines = [
                    'New School of Chaplaincy online registration',
                    'Submission ID: '.$submission->id,
                    'Applicant: '.($payload['first_name'] ?? '').' '.($payload['last_name'] ?? ''),
                    'Email: '.($payload['email'] ?? ''),
                    'Programme: '.($payload['application_type'] ?? ''),
                ];
                Mail::raw(implode("\n", $lines), function ($message) use ($to, $payload): void {
                    $message->to($to)->subject('SOC registration: '.($payload['last_name'] ?? 'Applicant'));
                });
            } catch (\Throwable) {
                // Stored; mail optional
            }
        }

        return redirect()
            ->route('soc.register')
            ->with('status', __('Thank you. Your application has been received. We will contact you using the details you provided.'));
    }
}
