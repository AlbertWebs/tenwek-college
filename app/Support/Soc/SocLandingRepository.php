<?php

namespace App\Support\Soc;

use App\Models\MediaAsset;
use App\Models\School;
use App\Models\SocFaqItem;
use App\Models\SocLandingSection;
use App\Models\SocNavItem;
use App\Models\SocProgrammeGroup;
use App\Models\SocProgrammeItem;
use App\Models\SocTeamMember;
use App\Models\SocTestimonial;

final class SocLandingRepository
{
    /** @var array<int|string, array<string, mixed>> */
    private static array $cache = [];

    /**
     * @return array<string, mixed>
     */
    public function forSchool(School $school): array
    {
        if ($school->slug !== 'soc') {
            return $this->configCopy();
        }

        $key = (string) $school->id;
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $base = $this->configCopy();
        $sections = SocLandingSection::query()
            ->where('school_id', $school->id)
            ->get()
            ->keyBy('section_key');

        $assetKeys = ['logo', 'hero_image', 'about_image', 'map_embed_url'];
        if ($sections->has('site_assets')) {
            $payload = $sections->get('site_assets')->payload ?? [];
            if (is_array($payload)) {
                foreach ($assetKeys as $ak) {
                    if (! empty($payload[$ak]) && is_string($payload[$ak])) {
                        $base[$ak] = $payload[$ak];
                    }
                }
            }
        }

        foreach ($sections as $sectionKey => $row) {
            if ($sectionKey === 'site_assets') {
                continue;
            }
            $payload = $row->payload;
            if (! is_array($payload)) {
                if ($payload !== null) {
                    $base[$sectionKey] = $payload;
                }

                continue;
            }
            if (isset($base[$sectionKey]) && is_array($base[$sectionKey])) {
                $base[$sectionKey] = array_replace_recursive($base[$sectionKey], $payload);
            } else {
                $base[$sectionKey] = $payload;
            }
        }

        $base['testimonials'] = $this->mergeTestimonials($school, $base['testimonials'] ?? []);
        $base['main_nav'] = $this->mainNavFor($school, $base['main_nav'] ?? []);
        $base['board_and_management'] = $this->mergeTeam($school, $base['board_and_management'] ?? []);
        $base['academic_programmes'] = $this->mergeProgrammes($school, $base['academic_programmes'] ?? []);
        $base['gallery'] = $this->mergeGalleryMedia($school, $base['gallery'] ?? []);
        $base['faqs'] = $this->mergeFaqItems($school, $base['faqs'] ?? []);

        self::$cache[$key] = $base;

        return $base;
    }

    public static function flushCache(): void
    {
        self::$cache = [];
    }

    /**
     * Overrides for SeoPresenter::build: only keys with admin content are returned.
     *
     * @return array<string, mixed>
     */
    public function seoPayloadForBuild(School $school): array
    {
        $landing = $this->forSchool($school);
        $seo = $landing['landing_seo'] ?? [];
        if (! is_array($seo)) {
            return [];
        }

        $out = [];
        foreach (['title', 'description', 'keywords', 'canonical', 'og_title', 'og_description', 'robots'] as $k) {
            if (! empty($seo[$k]) && is_string($seo[$k])) {
                $out[$k] = $seo[$k];
            }
        }
        if (! empty($seo['og_image']) && is_string($seo['og_image'])) {
            $img = $seo['og_image'];
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                $out['image'] = $img;
            } else {
                $out['image'] = self::publicMediaUrl($img) ?? asset($img);
            }
        }

        return $out;
    }

    public static function publicMediaUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, 'soc/')) {
            return asset('storage/'.$path);
        }

        return asset($path);
    }

    /**
     * Prepends SOC media library images (admin uploads) into the gallery; newest first.
     * Skips duplicate `src` values already present in config/JSON items.
     *
     * @param  array<string, mixed>  $block
     * @return array<string, mixed>
     */
    /**
     * @param  array<string, mixed>  $faqsBlock
     * @return array<string, mixed>
     */
    private function mergeFaqItems(School $school, array $faqsBlock): array
    {
        if ($school->slug !== 'soc') {
            return $faqsBlock;
        }

        $rows = SocFaqItem::query()
            ->where('school_id', $school->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($rows->isEmpty()) {
            return $faqsBlock;
        }

        $faqsBlock['items'] = $rows->map(static fn (SocFaqItem $r) => $r->toLegacyItemArray())->all();

        return $faqsBlock;
    }

    private function mergeGalleryMedia(School $school, array $block): array
    {
        if ($school->slug !== 'soc') {
            return $block;
        }

        $items = $block['items'] ?? [];
        if (! is_array($items)) {
            $items = [];
        }

        $seen = [];
        foreach ($items as $row) {
            if (is_array($row) && filled($row['src'] ?? null)) {
                $seen[(string) $row['src']] = true;
            }
        }

        $mediaRows = [];
        $assets = MediaAsset::query()
            ->where('school_id', $school->id)
            ->orderByDesc('id')
            ->get();

        foreach ($assets as $asset) {
            if (! $asset->isPreviewableImage()) {
                continue;
            }
            $src = $asset->path;
            if (isset($seen[$src])) {
                continue;
            }
            $seen[$src] = true;
            $caption = filled($asset->alt_text)
                ? $asset->alt_text
                : pathinfo((string) $asset->original_filename, PATHINFO_FILENAME);
            $mediaRows[] = [
                'src' => $src,
                'alt' => $asset->alt_text ?: $caption,
                'caption' => $caption,
            ];
        }

        $block['items'] = array_values(array_merge($mediaRows, $items));

        return $block;
    }

    /**
     * @param  array<string, mixed>  $testimonialBlock
     * @return array<string, mixed>
     */
    private function mergeTestimonials(School $school, array $testimonialBlock): array
    {
        $rows = SocTestimonial::query()
            ->where('school_id', $school->id)
            ->published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($rows->isEmpty()) {
            return $testimonialBlock;
        }

        $testimonialBlock['items'] = $rows->map(static function (SocTestimonial $t) {
            $item = [
                'name' => $t->name,
                'role' => $t->designation ?: ($t->organization ?: ''),
                'quote' => $t->quote,
            ];
            if (filled($t->image_path)) {
                $item['image'] = $t->image_path;
            }
            if (filled($t->organization) && filled($t->designation)) {
                $item['organization'] = $t->organization;
            }

            return $item;
        })->all();

        return $testimonialBlock;
    }

    /**
     * @param  array<int, array<string, mixed>>  $fallback
     * @return array<int, array<string, mixed>>
     */
    private function mainNavFor(School $school, array $fallback): array
    {
        $top = SocNavItem::query()
            ->where('school_id', $school->id)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('id')])
            ->get();

        if ($top->isEmpty()) {
            return $fallback;
        }

        return $top->map(fn (SocNavItem $item) => $this->navItemToMenuArray($item))->values()->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function navItemToMenuArray(SocNavItem $item): array
    {
        $children = $item->children;
        if ($children->isNotEmpty()) {
            $out = [
                'label' => $item->label,
                'children' => $children->map(fn (SocNavItem $c) => $this->navLeafToMenu($c))->all(),
            ];

            return $out;
        }

        return $this->navLeafToMenu($item);
    }

    /**
     * @return array<string, mixed>
     */
    private function navLeafToMenu(SocNavItem $item): array
    {
        $out = ['label' => $item->label];
        if ($item->is_highlight) {
            $out['highlight'] = true;
        }
        if (filled($item->external_url)) {
            $out['external_url'] = $item->external_url;
            $out['open_new_tab'] = $item->open_new_tab;

            return $out;
        }
        if (filled($item->route_name) && $item->route_name === 'soc.register') {
            $out['slug'] = 'register';

            return $out;
        }
        if (filled($item->page_slug)) {
            $out['slug'] = $item->page_slug;
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $block
     * @return array<string, mixed>
     */
    private function mergeTeam(School $school, array $block): array
    {
        $board = SocTeamMember::query()
            ->where('school_id', $school->id)
            ->published()
            ->team(SocTeamMember::TEAM_BOARD)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($board->isNotEmpty()) {
            $block['board'] = $board->map(fn (SocTeamMember $m) => $this->teamMemberToLegacyCard($m))->all();
        }

        $management = SocTeamMember::query()
            ->where('school_id', $school->id)
            ->published()
            ->team(SocTeamMember::TEAM_MANAGEMENT)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($management->isNotEmpty()) {
            $block['management'] = $management->map(fn (SocTeamMember $m) => $this->teamMemberToLegacyCard($m))->all();
        }

        $faculty = SocTeamMember::query()
            ->where('school_id', $school->id)
            ->published()
            ->team(SocTeamMember::TEAM_FACULTY)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($faculty->isNotEmpty()) {
            $block['faculty'] = $faculty->map(fn (SocTeamMember $m) => $this->teamMemberToLegacyCard($m))->all();
        }

        return $block;
    }

    /**
     * @return array<string, mixed>
     */
    private function teamMemberToLegacyCard(SocTeamMember $m): array
    {
        $row = [
            'name' => $m->name,
            'role' => $m->role_title,
            'highlight' => $m->highlight,
        ];
        if (filled($m->image_path)) {
            $row['image'] = $m->image_path;
        }

        return $row;
    }

    /**
     * @param  array<string, mixed>  $block
     * @return array<string, mixed>
     */
    private function mergeProgrammes(School $school, array $block): array
    {
        $hasGroups = SocProgrammeGroup::query()->where('school_id', $school->id)->exists();
        if (! $hasGroups) {
            return $block;
        }

        $groups = SocProgrammeGroup::query()
            ->where('school_id', $school->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->with(['items' => fn ($q) => $q->published()->orderBy('sort_order')->orderBy('id')])
            ->get();

        $block['groups'] = $groups->map(static function (SocProgrammeGroup $g) {
            return [
                'heading' => $g->heading,
                'description' => $g->description,
                'items' => $g->items->map(fn (SocProgrammeItem $it) => self::programmeItemToFrontendArray($it))->values()->all(),
            ];
        })->filter(static fn (array $g) => count($g['items']) > 0)->values()->all();

        return $block;
    }

    /**
     * @return array<string, mixed>
     */
    public static function programmeItemToFrontendArray(SocProgrammeItem $it): array
    {
        $row = [
            'slug' => $it->slug,
            'title' => $it->title,
            'badge' => $it->badge,
            'summary' => $it->summary,
        ];
        if (filled($it->body)) {
            $row['body'] = $it->body;
        }
        foreach (['seo_title', 'seo_description', 'seo_keywords', 'og_title', 'og_image_path'] as $k) {
            if (filled($it->{$k})) {
                $row[$k] = $it->{$k};
            }
        }

        return $row;
    }

    /**
     * @return array<string, mixed>
     */
    private function configCopy(): array
    {
        $raw = config('tenwek.soc_landing', []);

        return json_decode(json_encode($raw), true) ?? [];
    }
}
