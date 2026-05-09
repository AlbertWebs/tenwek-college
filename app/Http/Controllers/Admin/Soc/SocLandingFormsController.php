<?php

namespace App\Http\Controllers\Admin\Soc;

use App\Models\SocLandingSection;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocLandingFormsController extends BaseSocAdminController
{
    public function editHero(Request $request): View
    {
        $soc = $this->socSchool($request);
        $hero = $this->mergedSection($soc, 'hero');
        $assetsRow = SocLandingSection::query()
            ->where('school_id', $soc->id)
            ->where('section_key', 'site_assets')
            ->first();
        $assets = is_array($assetsRow?->payload) ? $assetsRow->payload : [];

        return view('admin.soc.landing.hero', compact('soc', 'hero', 'assets'));
    }

    public function updateHero(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
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
            'about_image' => ['nullable', 'image', 'max:8192'],
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
                'params' => ['school' => 'soc', 'pageSlug' => $validated['primary_cta_page_slug']],
            ],
            'secondary_cta' => [
                'label' => $validated['secondary_cta_label'],
                'hash' => $validated['secondary_cta_hash'],
            ],
        ];
        $this->persistSection($soc, 'hero', $heroPayload);

        $assets = SocLandingSection::query()
            ->where('school_id', $soc->id)
            ->where('section_key', 'site_assets')
            ->first()?->payload ?? [];
        if (! is_array($assets)) {
            $assets = [];
        }
        $prefix = 'soc/'.$soc->id;
        $hadUpload = false;
        foreach (['hero_image' => 'hero_image', 'about_image' => 'about_image', 'logo' => 'logo'] as $fileKey => $assetKey) {
            if ($request->hasFile($fileKey)) {
                $assets[$assetKey] = $request->file($fileKey)->store($prefix, 'public');
                $hadUpload = true;
            }
        }
        if ($hadUpload) {
            $this->persistSection($soc, 'site_assets', $assets);
        }

        return redirect()->route('admin.soc.hero.edit')->with('status', 'Hero and images saved.');
    }

    public function editAbout(Request $request): View
    {
        $soc = $this->socSchool($request);
        $about = $this->mergedSection($soc, 'about');

        return view('admin.soc.landing.about', compact('soc', 'about'));
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'kicker' => ['required', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:255'],
            'lead' => ['required', 'string', 'max:500'],
            'paragraphs' => ['required', 'string', 'max:20000'],
        ]);
        $paragraphs = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $validated['paragraphs']) ?: [])));
        $this->persistSection($soc, 'about', [
            'kicker' => $validated['kicker'],
            'title' => $validated['title'],
            'lead' => $validated['lead'],
            'paragraphs' => $paragraphs,
        ]);

        return redirect()->route('admin.soc.about.edit')->with('status', 'About section saved.');
    }

    public function editMissionVision(Request $request): View
    {
        $soc = $this->socSchool($request);
        $vision = $this->mergedSection($soc, 'vision');
        $mission = $this->mergedSection($soc, 'mission');

        return view('admin.soc.landing.mission-vision', compact('soc', 'vision', 'mission'));
    }

    public function updateMissionVision(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'vision_title' => ['required', 'string', 'max:255'],
            'vision_text' => ['required', 'string', 'max:5000'],
            'mission_title' => ['required', 'string', 'max:255'],
            'mission_text' => ['required', 'string', 'max:5000'],
        ]);
        $this->persistSection($soc, 'vision', [
            'title' => $validated['vision_title'],
            'text' => $validated['vision_text'],
        ]);
        $this->persistSection($soc, 'mission', [
            'title' => $validated['mission_title'],
            'text' => $validated['mission_text'],
        ]);

        return redirect()->route('admin.soc.mission-vision.edit')->with('status', 'Mission & vision saved.');
    }

    public function editMotto(Request $request): View
    {
        $soc = $this->socSchool($request);
        $motto = $this->mergedSection($soc, 'motto');

        return view('admin.soc.landing.motto', compact('soc', 'motto'));
    }

    public function updateMotto(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'kicker' => ['required', 'string', 'max:120'],
            'text' => ['required', 'string', 'max:2000'],
        ]);
        $this->persistSection($soc, 'motto', $validated);

        return redirect()->route('admin.soc.motto.edit')->with('status', 'Motto saved.');
    }

    public function editContact(Request $request): View
    {
        $soc = $this->socSchool($request);
        $contact = $this->mergedSection($soc, 'contact');
        $mapEmbed = app(SocLandingRepository::class)->forSchool($soc)['map_embed_url'] ?? '';

        return view('admin.soc.landing.contact', compact('soc', 'contact', 'mapEmbed'));
    }

    public function updateContact(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'kicker' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'location_lines' => ['required', 'string', 'max:5000'],
            'phones' => ['required', 'string', 'max:5000'],
            'office_hours_lines' => ['nullable', 'string', 'max:5000'],
            'map_embed_url' => ['nullable', 'string', 'max:2000'],
            'social_label' => ['nullable', 'array'],
            'social_label.*' => ['nullable', 'string', 'max:120'],
            'social_url' => ['nullable', 'array'],
            'social_url.*' => ['nullable', 'string', 'max:2000'],
        ]);
        $locationLines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $validated['location_lines']) ?: [])));
        $phones = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $validated['phones']) ?: [])));
        $officeHours = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($validated['office_hours_lines'] ?? '')) ?: [])));
        $labels = $validated['social_label'] ?? [];
        $urls = $validated['social_url'] ?? [];
        $socialLinks = [];
        $maxSocial = min(12, max(count($labels), count($urls)));
        for ($i = 0; $i < $maxSocial; $i++) {
            $lab = trim((string) ($labels[$i] ?? ''));
            $url = trim((string) ($urls[$i] ?? ''));
            if ($lab !== '' && $url !== '') {
                $socialLinks[] = ['label' => $lab, 'url' => $url];
            }
        }
        $this->persistSection($soc, 'contact', [
            'kicker' => $validated['kicker'],
            'email' => $validated['email'],
            'location_lines' => $locationLines,
            'phones' => $phones,
            'office_hours_lines' => $officeHours,
            'social_links' => $socialLinks,
        ]);
        $assets = SocLandingSection::query()
            ->where('school_id', $soc->id)
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
        $this->persistSection($soc, 'site_assets', $assets);

        return redirect()->route('admin.soc.contact.edit')->with('status', 'Contact block saved.');
    }

    public function editSeo(Request $request): View
    {
        $soc = $this->socSchool($request);
        $landingSeo = $this->mergedSection($soc, 'landing_seo');

        return view('admin.soc.landing.seo', compact('soc', 'landingSeo'));
    }

    public function updateSeo(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
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
        $row = SocLandingSection::query()
            ->where('school_id', $soc->id)
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
            $payload['og_image'] = $request->file('og_image_upload')->store('soc/'.$soc->id, 'public');
        }
        $this->persistSection($soc, 'landing_seo', $payload);

        return redirect()->route('admin.soc.seo.edit')->with('status', 'Landing SEO saved.');
    }

    public function editTopBar(Request $request): View
    {
        $soc = $this->socSchool($request);
        $topBar = $this->mergedSection($soc, 'top_bar');

        return view('admin.soc.landing.top-bar', compact('soc', 'topBar'));
    }

    public function updateTopBar(Request $request): RedirectResponse
    {
        $soc = $this->socSchool($request);
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'call_prefix' => ['nullable', 'string', 'max:64'],
            'call_display' => ['required', 'string', 'max:255'],
            'call_tel' => ['required', 'string', 'max:64'],
            'portal_label' => ['nullable', 'string', 'max:120'],
            'portal_url' => ['nullable', 'string', 'max:2000'],
        ]);
        $this->persistSection($soc, 'top_bar', $validated);

        return redirect()->route('admin.soc.top-bar.edit')->with('status', 'Top bar saved.');
    }
}
