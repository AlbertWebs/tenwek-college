<?php

namespace App\Support\Seo;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoPresenter
{
    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function build(Request $request, array $overrides = []): array
    {
        $g = config('site_global_seo', []);
        if (! is_array($g)) {
            $g = [];
        }

        $site = config('tenwek.name');
        $tagline = config('tenwek.tagline');
        $baseTitle = "{$site} | ".config('tenwek.institution_legal');

        $canonical = self::absoluteUrl($request, $overrides['canonical'] ?? $request->url());
        $ogImagePath = ! empty($g['default_og_image']) ? $g['default_og_image'] : config('tenwek.default_og_image');
        $defaultImage = Str::startsWith((string) $ogImagePath, ['http://', 'https://'])
            ? self::absoluteUrl($request, (string) $ogImagePath)
            : self::absoluteUrl($request, asset($ogImagePath));

        $title = self::normalizeTitle($overrides['title'] ?? $baseTitle, $baseTitle);
        $descriptionSource = $overrides['description']
            ?? (! empty($g['default_meta_description']) ? $g['default_meta_description'] : $tagline);
        $description = self::normalizeDescription($descriptionSource);
        $image = isset($overrides['image']) && $overrides['image'] !== null && $overrides['image'] !== ''
            ? self::absoluteUrl($request, $overrides['image'])
            : $defaultImage;
        $defaultRobots = ! empty($g['default_robots'])
            ? $g['default_robots']
            : 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';
        $robots = $overrides['robots'] ?? $defaultRobots;
        $keywords = $overrides['keywords'] ?? self::keywordsFromGlobalOrDefault($g);
        $locale = str_replace('_', '-', app()->getLocale());

        $schemas = [];
        $schemas[] = self::schemaOrganization($request, $site, $image);
        $schemas[] = self::schemaCollegeOrUniversity($request, $site, $image);
        $schemas[] = self::schemaMedicalOrganization($request, $site);
        $schemas[] = self::schemaHospitalAffiliation($request);
        $schemas[] = self::schemaWebSite($request, $site);

        if (! empty($overrides['breadcrumbs']) && is_array($overrides['breadcrumbs'])) {
            $schemas[] = self::schemaBreadcrumbs($request, $overrides['breadcrumbs']);
        }

        if (! empty($overrides['faq']) && is_array($overrides['faq'])) {
            $schemas[] = self::schemaFaq($overrides['faq']);
        }

        if (! empty($overrides['schema']) && is_array($overrides['schema'])) {
            foreach ($overrides['schema'] as $block) {
                if (is_array($block)) {
                    $schemas[] = $block;
                }
            }
        }

        $schemas[] = self::schemaWebPage($request, $title, $description, $canonical, $image);

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'robots' => $robots,
            'canonical' => $canonical,
            'locale' => $locale,
            'og' => [
                'type' => $overrides['og_type'] ?? 'website',
                'title' => $overrides['og_title'] ?? $title,
                'description' => $overrides['og_description'] ?? $description,
                'image' => $image,
                'url' => $canonical,
                'site_name' => $site,
                'locale' => $locale,
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $overrides['twitter_title'] ?? $title,
                'description' => $overrides['twitter_description'] ?? $description,
                'image' => $image,
            ],
            'json_ld' => array_values(array_filter($schemas)),
        ];
    }

    public static function absoluteUrl(Request $request, string $url): string
    {
        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        return $request->getSchemeAndHttpHost().'/'.ltrim($url, '/');
    }

    protected static function normalizeTitle(string $title, string $baseTitle): string
    {
        $title = strip_tags($title);

        return Str::length($title) > 70 ? Str::limit($title, 67, '…') : $title;
    }

    protected static function normalizeDescription(string $description): string
    {
        $description = preg_replace('/\s+/', ' ', strip_tags($description)) ?? '';

        return Str::length($description) > 160 ? Str::limit($description, 157, '…') : $description;
    }

    /**
     * @param  array<string, mixed>  $globalSeo
     * @return list<string>
     */
    protected static function keywordsFromGlobalOrDefault(array $globalSeo): array
    {
        $raw = $globalSeo['default_keywords'] ?? null;
        if ($raw === null || $raw === '') {
            return self::defaultKeywords();
        }
        if (is_array($raw)) {
            return array_values(array_filter(array_map('trim', $raw)));
        }
        if (is_string($raw)) {
            return array_values(array_filter(array_map('trim', explode(',', $raw))));
        }

        return self::defaultKeywords();
    }

    /**
     * @return list<string>
     */
    protected static function defaultKeywords(): array
    {
        return [
            'Tenwek Hospital College',
            'nursing college Kenya',
            'medical training Kenya',
            'clinical education East Africa',
            'Bomet',
            'College of Health Sciences Tenwek',
            'School of Chaplaincy',
            'KRCHN',
            'healthcare education',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function schemaOrganization(Request $request, string $name, string $image): array
    {
        $url = config('tenwek.url');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => $url.'#organization',
            'name' => $name,
            'url' => $url,
            'logo' => $image,
            'image' => $image,
            'email' => config('tenwek.email_public'),
            'telephone' => config('tenwek.phone'),
            'address' => self::postalAddress(),
            'sameAs' => array_values(array_filter(config('tenwek.social'))),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function schemaCollegeOrUniversity(Request $request, string $name, string $image): array
    {
        $url = config('tenwek.url');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollegeOrUniversity',
            '@id' => $url.'#college',
            'name' => $name,
            'url' => $url,
            'image' => $image,
            'description' => config('tenwek.tagline'),
            'parentOrganization' => [
                '@type' => 'Organization',
                'name' => config('tenwek.hospital.name'),
                'url' => config('tenwek.hospital.url'),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function schemaMedicalOrganization(Request $request, string $name): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalOrganization',
            'name' => $name,
            'url' => config('tenwek.url'),
            'medicalSpecialty' => [
                'NursingEducation',
                'ClinicalEducation',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function schemaHospitalAffiliation(Request $request): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Hospital',
            'name' => config('tenwek.hospital.name'),
            'url' => config('tenwek.hospital.url'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function schemaWebSite(Request $request, string $name): array
    {
        $url = config('tenwek.url');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => $url.'#website',
            'name' => $name,
            'url' => $url,
            'publisher' => ['@id' => $url.'#organization'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $url.'/downloads?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function schemaWebPage(Request $request, string $title, string $description, string $canonical, string $image): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $title,
            'description' => $description,
            'url' => $canonical,
            'isPartOf' => ['@id' => config('tenwek.url').'#website'],
            'primaryImageOfPage' => [
                '@type' => 'ImageObject',
                'url' => $image,
            ],
        ];
    }

    /**
     * @param  list<array{label: string, href: string}>  $items
     * @return array<string, mixed>
     */
    protected static function schemaBreadcrumbs(Request $request, array $items): array
    {
        $elements = [];
        foreach ($items as $i => $item) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['label'],
                'item' => self::absoluteUrl($request, $item['href']),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * @param  list<array{question: string, answer: string}>  $pairs
     * @return array<string, mixed>
     */
    protected static function schemaFaq(array $pairs): array
    {
        $entities = [];
        foreach ($pairs as $pair) {
            $entities[] = [
                '@type' => 'Question',
                'name' => $pair['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $pair['answer'],
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function postalAddress(): array
    {
        $a = config('tenwek.address');

        return [
            '@type' => 'PostalAddress',
            'streetAddress' => $a['street'],
            'addressLocality' => $a['locality'],
            'addressRegion' => $a['region'],
            'addressCountry' => $a['country_name'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function schemaArticle(string $headline, string $description, string $url, string $datePublished, ?string $image = null): array
    {
        $image = $image ?? self::absoluteUrl(request(), asset(config('tenwek.default_og_image')));

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $headline,
            'description' => $description,
            'url' => $url,
            'datePublished' => $datePublished,
            'image' => $image,
            'author' => [
                '@type' => 'Organization',
                'name' => config('tenwek.name'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('tenwek.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $image,
                ],
            ],
        ];
    }
}
