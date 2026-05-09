<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\School;
use App\Models\SocLandingSection;
use App\Models\SocNavItem;
use App\Models\SocProgrammeGroup;
use App\Models\SocProgrammeItem;
use App\Models\SocTeamMember;
use App\Models\SocTestimonial;
use App\Support\Soc\SocLandingRepository;
use Illuminate\Database\Seeder;

/**
 * Copies config-driven SOC content into CMS tables so admin forms and JSON editors
 * start with the same data shown on the public site (first run only per table/row).
 */
class SocCmsInitialContentSeeder extends Seeder
{
    public function run(): void
    {
        $soc = School::query()->where('slug', 'soc')->where('is_active', true)->first();
        if ($soc === null) {
            return;
        }

        $L = config('tenwek.soc_landing', []);
        if ($L === []) {
            return;
        }

        $this->seedLandingSections($soc, $L);
        $this->seedTestimonials($soc, $L);
        $this->seedTeamMembers($soc, $L);
        $this->seedNavItems($soc, $L);
        $this->seedProgrammes($soc, $L);
        $this->enrichPlaceholderPages($soc);

        SocLandingRepository::flushCache();
    }

    /**
     * @param  array<string, mixed>  $L
     */
    private function seedLandingSections(School $soc, array $L): void
    {
        $copy = static fn (mixed $data): array => json_decode(json_encode($data), true) ?? [];

        foreach (['hero', 'about', 'vision', 'mission', 'motto', 'contact', 'top_bar'] as $key) {
            if (empty($L[$key]) || ! is_array($L[$key])) {
                continue;
            }
            SocLandingSection::query()->firstOrCreate(
                ['school_id' => $soc->id, 'section_key' => $key],
                ['payload' => $copy($L[$key])]
            );
        }

        $seo = array_filter(
            $L['landing_seo'] ?? [],
            static fn ($v) => $v !== null && $v !== ''
        );
        if ($seo !== []) {
            SocLandingSection::query()->firstOrCreate(
                ['school_id' => $soc->id, 'section_key' => 'landing_seo'],
                ['payload' => $copy($seo)]
            );
        }

        $assets = array_filter([
            'logo' => $L['logo'] ?? null,
            'hero_image' => $L['hero_image'] ?? null,
            'about_image' => $L['about_image'] ?? null,
            'map_embed_url' => $L['map_embed_url'] ?? null,
        ], static fn ($v) => filled($v));
        if ($assets !== []) {
            SocLandingSection::query()->firstOrCreate(
                ['school_id' => $soc->id, 'section_key' => 'site_assets'],
                ['payload' => $assets]
            );
        }

        $jsonKeys = [
            'our_history',
            'message_from_principal',
            'strategic_partners',
            'fee',
            'gallery',
            'faqs',
            'admissions',
            'board_and_management',
        ];
        foreach ($jsonKeys as $key) {
            if (empty($L[$key]) || ! is_array($L[$key])) {
                continue;
            }
            SocLandingSection::query()->firstOrCreate(
                ['school_id' => $soc->id, 'section_key' => $key],
                ['payload' => $copy($L[$key])]
            );
        }
    }

    /**
     * @param  array<string, mixed>  $L
     */
    private function seedTestimonials(School $soc, array $L): void
    {
        if (SocTestimonial::query()->where('school_id', $soc->id)->exists()) {
            return;
        }
        $items = $L['testimonials']['items'] ?? [];
        if (! is_array($items)) {
            return;
        }
        $order = 0;
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }
            $name = (string) ($item['name'] ?? '');
            $quote = (string) ($item['quote'] ?? '');
            if ($name === '' || $quote === '') {
                continue;
            }
            $role = (string) ($item['role'] ?? '');
            SocTestimonial::query()->create([
                'school_id' => $soc->id,
                'name' => $name,
                'designation' => $role,
                'organization' => (string) ($item['organization'] ?? ''),
                'quote' => $quote,
                'image_path' => isset($item['image']) && is_string($item['image']) ? $item['image'] : null,
                'sort_order' => $order++,
                'is_published' => true,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $L
     */
    private function seedTeamMembers(School $soc, array $L): void
    {
        if (SocTeamMember::query()->where('school_id', $soc->id)->exists()) {
            return;
        }
        $block = $L['board_and_management'] ?? [];
        if (! is_array($block)) {
            return;
        }
        $order = 0;
        foreach ($block['board'] ?? [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $name = (string) ($row['name'] ?? '');
            if ($name === '') {
                continue;
            }
            SocTeamMember::query()->create([
                'school_id' => $soc->id,
                'team' => SocTeamMember::TEAM_BOARD,
                'name' => $name,
                'role_title' => (string) ($row['role'] ?? ''),
                'bio' => null,
                'image_path' => isset($row['image']) && is_string($row['image']) ? $row['image'] : null,
                'highlight' => (bool) ($row['highlight'] ?? false),
                'sort_order' => $order++,
                'is_published' => true,
            ]);
        }
        $order = 0;
        foreach ($block['management'] ?? [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $name = (string) ($row['name'] ?? '');
            if ($name === '') {
                continue;
            }
            SocTeamMember::query()->create([
                'school_id' => $soc->id,
                'team' => SocTeamMember::TEAM_MANAGEMENT,
                'name' => $name,
                'role_title' => (string) ($row['role'] ?? ''),
                'bio' => null,
                'image_path' => isset($row['image']) && is_string($row['image']) ? $row['image'] : null,
                'highlight' => (bool) ($row['highlight'] ?? false),
                'sort_order' => $order++,
                'is_published' => true,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $L
     */
    private function seedNavItems(School $soc, array $L): void
    {
        if (SocNavItem::query()->where('school_id', $soc->id)->exists()) {
            return;
        }
        $mainNav = $L['main_nav'] ?? [];
        if (! is_array($mainNav)) {
            return;
        }
        $sort = 0;
        foreach ($mainNav as $entry) {
            if (! is_array($entry)) {
                continue;
            }
            if (! empty($entry['children']) && is_array($entry['children'])) {
                $parent = SocNavItem::query()->create([
                    'school_id' => $soc->id,
                    'parent_id' => null,
                    'label' => (string) ($entry['label'] ?? 'Menu'),
                    'page_slug' => null,
                    'route_name' => null,
                    'external_url' => null,
                    'open_new_tab' => false,
                    'is_highlight' => false,
                    'is_active' => true,
                    'sort_order' => $sort++,
                ]);
                $childSort = 0;
                foreach ($entry['children'] as $child) {
                    if (! is_array($child) || empty($child['slug'])) {
                        continue;
                    }
                    SocNavItem::query()->create([
                        'school_id' => $soc->id,
                        'parent_id' => $parent->id,
                        'label' => (string) ($child['label'] ?? ''),
                        'page_slug' => (string) $child['slug'],
                        'route_name' => null,
                        'external_url' => null,
                        'open_new_tab' => false,
                        'is_highlight' => false,
                        'is_active' => true,
                        'sort_order' => $childSort++,
                    ]);
                }

                continue;
            }
            $slug = (string) ($entry['slug'] ?? '');
            if ($slug === '') {
                continue;
            }
            SocNavItem::query()->create([
                'school_id' => $soc->id,
                'parent_id' => null,
                'label' => (string) ($entry['label'] ?? ''),
                'page_slug' => $slug,
                'route_name' => null,
                'external_url' => isset($entry['external_url']) ? (string) $entry['external_url'] : null,
                'open_new_tab' => (bool) ($entry['open_new_tab'] ?? false),
                'is_highlight' => (bool) ($entry['highlight'] ?? false),
                'is_active' => true,
                'sort_order' => $sort++,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $L
     */
    private function seedProgrammes(School $soc, array $L): void
    {
        if (SocProgrammeGroup::query()->where('school_id', $soc->id)->exists()) {
            return;
        }
        $ap = $L['academic_programmes'] ?? [];
        $groups = $ap['groups'] ?? [];
        if (! is_array($groups)) {
            return;
        }
        $gOrder = 0;
        foreach ($groups as $group) {
            if (! is_array($group)) {
                continue;
            }
            $heading = (string) ($group['heading'] ?? '');
            if ($heading === '') {
                continue;
            }
            $model = SocProgrammeGroup::query()->create([
                'school_id' => $soc->id,
                'heading' => $heading,
                'description' => isset($group['description']) && is_string($group['description']) ? $group['description'] : null,
                'sort_order' => $gOrder++,
            ]);
            $items = $group['items'] ?? [];
            if (! is_array($items)) {
                continue;
            }
            $iOrder = 0;
            foreach ($items as $item) {
                if (! is_array($item) || empty($item['slug'])) {
                    continue;
                }
                $slug = (string) $item['slug'];
                $page = Page::query()->where('school_id', $soc->id)->where('slug', $slug)->first();
                SocProgrammeItem::query()->create([
                    'school_id' => $soc->id,
                    'soc_programme_group_id' => $model->id,
                    'slug' => $slug,
                    'title' => (string) ($item['title'] ?? $slug),
                    'badge' => isset($item['badge']) && is_string($item['badge']) ? $item['badge'] : null,
                    'summary' => (string) ($item['summary'] ?? ''),
                    'body' => $page?->body,
                    'seo_title' => $page?->seo_title,
                    'seo_description' => $page?->seo_description,
                    'sort_order' => $iOrder++,
                    'is_published' => true,
                ]);
            }
        }
    }

    private function enrichPlaceholderPages(School $soc): void
    {
        $markers = ['tenwekhospitalcollege.ac.ke', 'forthcoming CMS'];

        Page::query()
            ->where(function ($q) use ($markers) {
                $q->where('body', 'like', '%'.$markers[0].'%')
                    ->orWhere('body', 'like', '%'.$markers[1].'%');
            })
            ->each(function (Page $page) use ($soc) {
                $excerpt = trim(strip_tags((string) $page->excerpt));
                if ($excerpt === '') {
                    return;
                }
                $safe = htmlspecialchars($excerpt, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $html = '<p class="text-lg font-medium text-thc-navy">'.$safe.'</p>';

                if ($page->school_id === null) {
                    $html .= '<p>Browse schools, programme pages, downloads, and news from the main navigation.</p>';
                } elseif ((int) $page->school_id === (int) $soc->id) {
                    if ($page->slug === 'register') {
                        $url = htmlspecialchars(route('soc.register'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $html .= '<p>Use the <a class="font-semibold text-thc-royal hover:underline" href="'.$url.'">online registration form</a> to apply.</p>';
                    } else {
                        $html .= '<p>Use the menus above for programmes, fees, admissions, and FAQs, or contact the School of Chaplaincy office.</p>';
                    }
                } else {
                    $html .= '<p>For application forms and programme details, use the College of Health Sciences pages and downloads hub.</p>';
                }

                $page->update(['body' => $html]);
            });
    }
}
