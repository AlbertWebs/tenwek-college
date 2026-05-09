<?php

namespace App\Support\Cohs;

use App\Models\CohsLandingSection;
use App\Models\CohsNavItem;
use App\Models\CohsTestimonial;
use App\Models\School;

final class CohsLandingRepository
{
    /** @var array<int|string, array<string, mixed>> */
    private static array $cache = [];

    /**
     * @return array<string, mixed>
     */
    public function forSchool(School $school): array
    {
        if ($school->slug !== 'cohs') {
            return $this->configCopy();
        }

        $key = (string) $school->id;
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $base = $this->configCopy();
        $sections = CohsLandingSection::query()
            ->where('school_id', $school->id)
            ->get()
            ->keyBy('section_key');

        $assetKeys = ['logo', 'hero_image', 'welcome_image', 'map_embed_url', 'off_campus_application_url'];
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

        self::$cache[$key] = $base;

        return $base;
    }

    public static function flushCache(): void
    {
        self::$cache = [];
    }

    /**
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
        if (str_starts_with($path, 'cohs/') || str_starts_with($path, 'soc/')) {
            return asset('storage/'.$path);
        }

        return asset($path);
    }

    /**
     * @param  array<string, mixed>  $testimonialBlock
     * @return array<string, mixed>
     */
    private function mergeTestimonials(School $school, array $testimonialBlock): array
    {
        $rows = CohsTestimonial::query()
            ->where('school_id', $school->id)
            ->published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($rows->isEmpty()) {
            return $testimonialBlock;
        }

        $testimonialBlock['items'] = $rows->map(static function (CohsTestimonial $t) {
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
        $top = CohsNavItem::query()
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

        return $top->map(fn (CohsNavItem $item) => $this->navItemToMenuArray($item))->values()->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function navItemToMenuArray(CohsNavItem $item): array
    {
        $children = $item->children;
        if ($children->isNotEmpty()) {
            $megaId = filled($item->mega_id) ? $item->mega_id : 'cohs-mega-'.$item->id;

            return [
                'label' => $item->label,
                'mega_id' => $megaId,
                'children' => $children->map(fn (CohsNavItem $c) => $this->navLeafToMenu($c))->all(),
            ];
        }

        return $this->navLeafToMenu($item);
    }

    /**
     * @return array<string, mixed>
     */
    private function navLeafToMenu(CohsNavItem $item): array
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
        if (filled($item->external_config_key)) {
            $out['external_config_key'] = $item->external_config_key;
            $out['open_new_tab'] = $item->open_new_tab;
            if (filled($item->page_slug)) {
                $out['slug'] = $item->page_slug;
            }

            return $out;
        }
        if (filled($item->route_name)) {
            $out['route'] = $item->route_name;
        }
        if (filled($item->page_slug)) {
            $out['slug'] = $item->page_slug;
        }

        return $out;
    }

    /**
     * @return array<string, mixed>
     */
    private function configCopy(): array
    {
        $raw = config('tenwek.cohs_landing', []);

        return json_decode(json_encode($raw), true) ?? [];
    }
}
