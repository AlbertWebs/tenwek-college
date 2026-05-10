<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Models\FormSubmission;
use App\Support\Seo\SeoPresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(Request $request): View
    {
        $seo = SeoPresenter::build($request, [
            'title' => 'Contact | '.config('tenwek.name'),
            'description' => 'Directory for Tenwek Hospital College (School of Chaplaincy and College of Health Sciences), Tenwek Hospital, and the Cardiothoracic Centre—plus a message form for general enquiries.',
            'canonical' => route('contact.show'),
            'schema' => [[
                '@context' => 'https://schema.org',
                '@type' => 'ContactPage',
                'name' => 'Contact '.config('tenwek.name'),
                'url' => route('contact.show'),
            ]],
            'breadcrumbs' => [
                ['label' => 'Home', 'href' => route('home')],
                ['label' => 'Contact', 'href' => route('contact.show')],
            ],
        ]);

        return view('contact', compact('seo'));
    }

    public function store(ContactMessageRequest $request): RedirectResponse
    {
        if ($request->filled('fax')) {
            return back()->with('status', __('Thank you — your message has been received.'));
        }

        $payload = $request->safe()->except(['_token', 'school_id']);
        $schoolId = $request->validated('school_id');

        FormSubmission::query()->create([
            'form_key' => 'contact',
            'school_id' => $schoolId,
            'payload' => $payload,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
        ]);

        $to = config('mail.from.address');
        if ($to && filter_var($to, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::raw(
                    collect($payload)->map(fn ($v, $k) => "{$k}: {$v}")->implode("\n"),
                    function ($message) use ($request, $to): void {
                        $subject = $request->filled('topic')
                            ? 'Tenwek College contact: '.$request->string('topic')
                            : 'Tenwek College contact: '.$request->string('name');
                        $message->to($to)->subject($subject);
                    }
                );
            } catch (\Throwable) {
                // Submission is stored; mail can be retried from admin.
            }
        }

        return back()->with('status', __('Thank you — your message has been received.'));
    }
}
