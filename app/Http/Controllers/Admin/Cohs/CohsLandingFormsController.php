<?php

namespace App\Http\Controllers\Admin\Cohs;

use App\Models\CohsLandingSection;
use App\Support\Cohs\CohsLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CohsLandingFormsController extends BaseCohsAdminController
{
    public function editHero(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $hero = $this->mergedSection($cohs, 'hero');
        $assetsRow = CohsLandingSection::query()
            ->where('school_id', $cohs->id)
            ->where('section_key', 'site_assets')
            ->first();
        $assets = is_array($assetsRow?->payload) ? $assetsRow->payload : [];

        return view('admin.cohs.landing.hero', compact('cohs', 'hero', 'assets'));
    }

    public function updateHero(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'badge' => ['required', 'string', 'max:255'],
            'eyebrow' => ['required', 'string', 'max:255'],
            'headline' => ['required', 'string', 'max:255'],
            'subhead' => ['required', 'string', 'max:2000'],
            'primary_cta_label' => ['required', 'string', 'max:120'],
            'primary_cta_page_slug' => ['required', 'string', 'max:192'],
            'secondary_cta_label' => ['required', 'string', 'max:120'],
            'secondary_cta_hash' => ['required', 'string', 'max:64'],
            'hero_image' => ['nullable', 'image', 'max:8192'],
            'welcome_image' => ['nullable', 'image', 'max:8192'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ]);

        $heroPayload = [
            'badge' => $validated['badge'],
            'eyebrow' => $validated['eyebrow'],
            'headline' => $validated['headline'],
            'subhead' => $validated['subhead'],
            'primary_cta' => [
                'label' => $validated['primary_cta_label'],
                'route' => 'schools.pages.show',
                'params' => ['school' => 'cohs', 'pageSlug' => $validated['primary_cta_page_slug']],
            ],
            'secondary_cta' => [
                'label' => $validated['secondary_cta_label'],
                'hash' => $validated['secondary_cta_hash'],
            ],
        ];
        $this->persistSection($cohs, 'hero', $heroPayload);

        $assets = CohsLandingSection::query()
            ->where('school_id', $cohs->id)
            ->where('section_key', 'site_assets')
            ->first()?->payload ?? [];
        if (! is_array($assets)) {
            $assets = [];
        }
        $prefix = 'cohs/'.$cohs->id;
        $hadUpload = false;
        foreach (['hero_image' => 'hero_image', 'welcome_image' => 'welcome_image', 'logo' => 'logo'] as $fileKey => $assetKey) {
            if ($request->hasFile($fileKey)) {
                $stored = $request->file($fileKey)->store($prefix, 'public');
                $assets[$assetKey] = $stored;
                $hadUpload = true;
            }
        }
        if ($hadUpload) {
            $this->persistSection($cohs, 'site_assets', $assets);
        }

        return redirect()->route('admin.cohs.hero.edit')->with('status', 'Hero and images saved.');
    }

    public function editWelcome(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $welcome = $this->mergedSection($cohs, 'welcome');

        return view('admin.cohs.landing.welcome', compact('cohs', 'welcome'));
    }

    public function updateWelcome(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'kicker' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:255'],
            'lead' => ['required', 'string', 'max:2000'],
            'paragraphs' => ['required', 'string', 'max:20000'],
        ]);
        $paragraphs = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $validated['paragraphs']) ?: [])));
        $this->persistSection($cohs, 'welcome', [
            'kicker' => $validated['kicker'],
            'title' => $validated['title'],
            'lead' => $validated['lead'],
            'paragraphs' => $paragraphs,
        ]);

        return redirect()->route('admin.cohs.welcome.edit')->with('status', 'Welcome section saved.');
    }

    public function editProgrammesBand(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $programmesBand = $this->mergedSection($cohs, 'programmes_band');

        return view('admin.cohs.landing.programmes-band', compact('cohs', 'programmesBand'));
    }

    public function updateProgrammesBand(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'kicker' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:255'],
            'intro' => ['required', 'string', 'max:2000'],
        ]);
        $band = $this->mergedSection($cohs, 'programmes_band');
        $band['kicker'] = $validated['kicker'];
        $band['title'] = $validated['title'];
        $band['intro'] = $validated['intro'];
        $this->persistSection($cohs, 'programmes_band', $band);

        return redirect()->route('admin.cohs.programmes-band.edit')->with('status', 'Programmes band saved.');
    }

    public function editTopBar(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $topBar = $this->mergedSection($cohs, 'top_bar');
        $offCampus = app(CohsLandingRepository::class)->forSchool($cohs)['off_campus_application_url'] ?? '';

        return view('admin.cohs.landing.top-bar', compact('cohs', 'topBar', 'offCampus'));
    }

    public function updateTopBar(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'call_prefix' => ['nullable', 'string', 'max:64'],
            'call_display' => ['required', 'string', 'max:255'],
            'call_tel' => ['required', 'string', 'max:64'],
            'portal_label' => ['nullable', 'string', 'max:120'],
            'portal_url' => ['nullable', 'string', 'max:2000'],
            'off_campus_application_url' => ['nullable', 'string', 'max:2000'],
        ]);
        $this->persistSection($cohs, 'top_bar', [
            'email' => $validated['email'],
            'call_prefix' => $validated['call_prefix'] ?? 'Call:',
            'call_display' => $validated['call_display'],
            'call_tel' => $validated['call_tel'],
            'portal_label' => $validated['portal_label'] ?? null,
            'portal_url' => $validated['portal_url'] ?? null,
        ]);

        $assets = CohsLandingSection::query()
            ->where('school_id', $cohs->id)
            ->where('section_key', 'site_assets')
            ->first()?->payload ?? [];
        if (! is_array($assets)) {
            $assets = [];
        }
        if (filled($validated['off_campus_application_url'] ?? null)) {
            $assets['off_campus_application_url'] = $validated['off_campus_application_url'];
        } else {
            unset($assets['off_campus_application_url']);
        }
        $this->persistSection($cohs, 'site_assets', $assets);

        return redirect()->route('admin.cohs.top-bar.edit')->with('status', 'Top bar and application URL saved.');
    }

    public function editSeo(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $landingSeo = $this->mergedSection($cohs, 'landing_seo');

        return view('admin.cohs.landing.seo', compact('cohs', 'landingSeo'));
    }

    public function updateSeo(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:192'],
            'description' => ['nullable', 'string', 'max:512'],
            'keywords' => ['nullable', 'string', 'max:512'],
            'canonical' => ['nullable', 'string', 'max:512'],
            'og_title' => ['nullable', 'string', 'max:192'],
            'og_description' => ['nullable', 'string', 'max:512'],
            'og_image' => ['nullable', 'string', 'max:512'],
            'og_image_upload' => ['nullable', 'image', 'max:8192'],
            'robots' => ['nullable', 'string', 'max:120'],
        ]);
        $row = CohsLandingSection::query()
            ->where('school_id', $cohs->id)
            ->where('section_key', 'landing_seo')
            ->first();
        $payload = is_array($row?->payload) ? $row->payload : [];
        foreach (['title', 'description', 'keywords', 'canonical', 'og_title', 'og_description', 'og_image', 'robots'] as $key) {
            if (! array_key_exists($key, $validated)) {
                continue;
            }
            $v = $validated[$key];
            if ($v !== null && $v !== '') {
                $payload[$key] = $v;
            } else {
                unset($payload[$key]);
            }
        }
        if ($request->hasFile('og_image_upload')) {
            $payload['og_image'] = $request->file('og_image_upload')->store('cohs/'.$cohs->id, 'public');
        }
        $this->persistSection($cohs, 'landing_seo', $payload);

        return redirect()->route('admin.cohs.seo.edit')->with('status', 'Landing SEO saved.');
    }

    public function editContactPage(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $contactPage = $this->mergedSection($cohs, 'contact_page');
        $mapEmbed = app(CohsLandingRepository::class)->forSchool($cohs)['map_embed_url'] ?? '';

        return view('admin.cohs.landing.contact', compact('cohs', 'contactPage', 'mapEmbed'));
    }

    public function updateContactPage(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'hero_kicker' => ['required', 'string', 'max:120'],
            'headline' => ['required', 'string', 'max:120'],
            'headline_accent' => ['nullable', 'string', 'max:120'],
            'lead' => ['required', 'string', 'max:2000'],
            'intro' => ['required', 'string', 'max:2000'],
            'email' => ['required', 'email', 'max:255'],
            'office_title' => ['required', 'string', 'max:255'],
            'address_lines' => ['required', 'string', 'max:2000'],
            'phone_rows_json' => ['required', 'string', 'max:20000'],
            'map_embed_url' => ['nullable', 'string', 'max:2000'],
        ]);
        try {
            $phoneRows = json_decode($validated['phone_rows_json'], true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return back()->withErrors(['phone_rows_json' => 'Invalid JSON: '.$e->getMessage()])->withInput();
        }
        if (! is_array($phoneRows)) {
            return back()->withErrors(['phone_rows_json' => 'Phone rows must be a JSON array.'])->withInput();
        }
        $addressLines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $validated['address_lines']) ?: [])));
        $this->persistSection($cohs, 'contact_page', [
            'hero_kicker' => $validated['hero_kicker'],
            'headline' => $validated['headline'],
            'headline_accent' => $validated['headline_accent'] ?? '',
            'lead' => $validated['lead'],
            'intro' => $validated['intro'],
            'email' => $validated['email'],
            'office_title' => $validated['office_title'],
            'address_lines' => $addressLines,
            'phone_rows' => $phoneRows,
        ]);

        $assets = CohsLandingSection::query()
            ->where('school_id', $cohs->id)
            ->where('section_key', 'site_assets')
            ->first()?->payload ?? [];
        if (! is_array($assets)) {
            $assets = [];
        }
        if (filled($validated['map_embed_url'])) {
            $assets['map_embed_url'] = $validated['map_embed_url'];
        } else {
            unset($assets['map_embed_url']);
        }
        $this->persistSection($cohs, 'site_assets', $assets);

        return redirect()->route('admin.cohs.contact.edit')->with('status', 'Contact page content saved.');
    }

    public function editSocialLife(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $socialLife = $this->mergedSection($cohs, 'social_life');

        return view('admin.cohs.landing.social-life', compact('cohs', 'socialLife'));
    }

    public function updateSocialLife(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'kicker' => ['required', 'string', 'max:120'],
            'headline_before' => ['required', 'string', 'max:120'],
            'headline_emphasis' => ['required', 'string', 'max:120'],
            'pull_quote' => ['nullable', 'string', 'max:500'],
            'paragraphs' => ['required', 'string', 'max:20000'],
            'highlights_json' => ['required', 'string', 'max:50000'],
            'hero_image' => ['nullable', 'image', 'max:8192'],
        ]);
        try {
            $highlights = json_decode($validated['highlights_json'], true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return back()->withErrors(['highlights_json' => 'Invalid JSON: '.$e->getMessage()])->withInput();
        }
        if (! is_array($highlights)) {
            return back()->withErrors(['highlights_json' => 'Highlights must be a JSON array.'])->withInput();
        }
        $paragraphs = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $validated['paragraphs']) ?: [])));
        $prev = $this->mergedSection($cohs, 'social_life');
        $payload = [
            'kicker' => $validated['kicker'],
            'headline_before' => $validated['headline_before'],
            'headline_emphasis' => $validated['headline_emphasis'],
            'pull_quote' => $validated['pull_quote'] ?? '',
            'paragraphs' => $paragraphs,
            'highlights' => $highlights,
        ];
        $assets = CohsLandingSection::query()
            ->where('school_id', $cohs->id)
            ->where('section_key', 'site_assets')
            ->first()?->payload ?? [];
        if (! is_array($assets)) {
            $assets = [];
        }
        if ($request->hasFile('hero_image')) {
            $assets['cohs_social_hero'] = $request->file('hero_image')->store('cohs/'.$cohs->id, 'public');
            $payload['hero_image'] = $assets['cohs_social_hero'];
        } elseif (! empty($prev['hero_image']) && is_string($prev['hero_image'])) {
            $payload['hero_image'] = $prev['hero_image'];
        }
        $this->persistSection($cohs, 'site_assets', $assets);
        $this->persistSection($cohs, 'social_life', $payload);

        return redirect()->route('admin.cohs.social-life.edit')->with('status', 'Social life section saved.');
    }

    public function editFacilities(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $facilities = $this->mergedSection($cohs, 'facilities');

        return view('admin.cohs.landing.facilities', compact('cohs', 'facilities'));
    }

    public function updateFacilities(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'kicker' => ['required', 'string', 'max:120'],
            'headline_before' => ['required', 'string', 'max:120'],
            'headline_emphasis' => ['required', 'string', 'max:120'],
            'paragraphs' => ['required', 'string', 'max:20000'],
            'highlights_json' => ['required', 'string', 'max:50000'],
            'hero_image' => ['nullable', 'image', 'max:8192'],
        ]);
        try {
            $highlights = json_decode($validated['highlights_json'], true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return back()->withErrors(['highlights_json' => 'Invalid JSON: '.$e->getMessage()])->withInput();
        }
        if (! is_array($highlights)) {
            return back()->withErrors(['highlights_json' => 'Highlights must be a JSON array.'])->withInput();
        }
        $paragraphs = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $validated['paragraphs']) ?: [])));
        $prev = $this->mergedSection($cohs, 'facilities');
        $payload = [
            'kicker' => $validated['kicker'],
            'headline_before' => $validated['headline_before'],
            'headline_emphasis' => $validated['headline_emphasis'],
            'paragraphs' => $paragraphs,
            'highlights' => $highlights,
        ];
        $assets = CohsLandingSection::query()
            ->where('school_id', $cohs->id)
            ->where('section_key', 'site_assets')
            ->first()?->payload ?? [];
        if (! is_array($assets)) {
            $assets = [];
        }
        if ($request->hasFile('hero_image')) {
            $assets['cohs_facilities_hero'] = $request->file('hero_image')->store('cohs/'.$cohs->id, 'public');
            $payload['hero_image'] = $assets['cohs_facilities_hero'];
        } elseif (! empty($prev['hero_image']) && is_string($prev['hero_image'])) {
            $payload['hero_image'] = $prev['hero_image'];
        }
        $this->persistSection($cohs, 'site_assets', $assets);
        $this->persistSection($cohs, 'facilities', $payload);

        return redirect()->route('admin.cohs.facilities.edit')->with('status', 'Facilities section saved.');
    }

    public function editTestimonialsBand(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $testimonials = $this->mergedSection($cohs, 'testimonials');

        return view('admin.cohs.landing.testimonials-band', compact('cohs', 'testimonials'));
    }

    public function updateTestimonialsBand(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'kicker' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:255'],
        ]);
        $block = $this->mergedSection($cohs, 'testimonials');
        $block['kicker'] = $validated['kicker'];
        $block['title'] = $validated['title'];
        $this->persistSection($cohs, 'testimonials', $block);

        return redirect()->route('admin.cohs.testimonials-band.edit')->with('status', 'Testimonials heading saved. Quote cards are managed under Testimonials CRUD.');
    }

    public function editAboutUs(Request $request): View
    {
        $cohs = $this->cohsSchool($request);
        $about = $this->mergedSection($cohs, 'about_us');

        return view('admin.cohs.landing.about-us', compact('cohs', 'about'));
    }

    public function updateAboutUs(Request $request): RedirectResponse
    {
        $cohs = $this->cohsSchool($request);
        $validated = $request->validate([
            'kicker' => ['required', 'string', 'max:120'],
            'headline' => ['required', 'string', 'max:255'],
            'history_heading' => ['required', 'string', 'max:255'],
            'history_paragraphs' => ['required', 'string', 'max:20000'],
            'history_image' => ['nullable', 'string', 'max:512'],
            'history_image_alt' => ['nullable', 'string', 'max:500'],
            'vision_title' => ['required', 'string', 'max:255'],
            'vision_text' => ['required', 'string', 'max:5000'],
            'mission_title' => ['required', 'string', 'max:255'],
            'mission_text' => ['required', 'string', 'max:5000'],
            'motto_title' => ['required', 'string', 'max:255'],
            'motto_text' => ['required', 'string', 'max:2000'],
            'board_section_heading' => ['required', 'string', 'max:255'],
            'board_intro' => ['required', 'string', 'max:5000'],
            'board_heading' => ['required', 'string', 'max:255'],
            'board_json' => ['required', 'string', 'max:50000'],
            'history_image_upload' => ['nullable', 'image', 'max:8192'],
        ]);
        try {
            $board = json_decode($validated['board_json'], true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return back()->withErrors(['board_json' => 'Invalid JSON: '.$e->getMessage()])->withInput();
        }
        if (! is_array($board)) {
            return back()->withErrors(['board_json' => 'Board must be a JSON array.'])->withInput();
        }
        $historyParagraphs = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $validated['history_paragraphs']) ?: [])));

        $payload = [
            'kicker' => $validated['kicker'],
            'headline' => $validated['headline'],
            'history_heading' => $validated['history_heading'],
            'history_paragraphs' => $historyParagraphs,
            'history_image' => $validated['history_image'] ?? config('tenwek.cohs_landing.about_us.history_image'),
            'history_image_alt' => $validated['history_image_alt'] ?? '',
            'vision' => ['title' => $validated['vision_title'], 'text' => $validated['vision_text']],
            'mission' => ['title' => $validated['mission_title'], 'text' => $validated['mission_text']],
            'motto' => ['title' => $validated['motto_title'], 'text' => $validated['motto_text']],
            'board_section_heading' => $validated['board_section_heading'],
            'board_intro' => $validated['board_intro'],
            'board_heading' => $validated['board_heading'],
            'board' => $board,
        ];

        if ($request->hasFile('history_image_upload')) {
            $path = $request->file('history_image_upload')->store('cohs/'.$cohs->id.'/about', 'public');
            $payload['history_image'] = $path;
        }

        $this->persistSection($cohs, 'about_us', $payload);

        return redirect()->route('admin.cohs.about-us.edit')->with('status', 'About us content saved.');
    }
}
