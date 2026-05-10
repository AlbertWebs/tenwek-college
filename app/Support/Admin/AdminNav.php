<?php

namespace App\Support\Admin;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

final class AdminNav
{
    /**
     * @return list<array{label: string, items: list<array<string, mixed>>}>
     */
    public static function groups(Request $request): array
    {
        $user = $request->user();
        if ($user === null) {
            return [];
        }

        $soc = School::query()->where('slug', 'soc')->first();
        $cohs = School::query()->where('slug', 'cohs')->first();
        $managesSoc = $soc !== null && $user->managesSchool($soc);
        $managesCohs = $cohs !== null && $user->managesSchool($cohs);

        $out = [];

        $out[] = [
            'label' => 'Main',
            'items' => [
                self::link('Dashboard', 'admin.dashboard', [], 'squares-2x2', ['admin.dashboard']),
            ],
        ];

        $out[] = [
            'label' => 'Website',
            'items' => [
                self::external('Public home', 'home', 'home'),
                self::external('Contact page', 'contact.show', 'envelope'),
                self::external('News (public)', 'news.index', 'newspaper'),
            ],
        ];

        $out[] = [
            'label' => 'Content',
            'items' => [
                self::link('Downloads', 'admin.downloads.index', [], 'arrow-down-tray', ['admin.downloads.*']),
            ],
        ];

        if ($managesSoc || $user->hasRole('super_admin')) {
            $socChildren = [
                self::link('SOC overview', 'admin.soc.dashboard', [], 'rectangle-group', ['admin.soc.dashboard']),
                self::link('Hero & images', 'admin.soc.hero.edit', [], 'photo', ['admin.soc.hero.*']),
                self::link('Pages', 'admin.soc.pages.index', [], 'document-text', ['admin.soc.pages.*']),
                self::link('Programmes', 'admin.soc.programme-groups.index', [], 'academic-cap', ['admin.soc.programme-groups.*', 'admin.soc.programme-groups.items.*']),
                self::link('Testimonials', 'admin.soc.testimonials.index', [], 'chat-bubble-left-right', ['admin.soc.testimonials.*']),
                self::link('Board & management team', 'admin.soc.team.index', [], 'users', ['admin.soc.team.*']),
                self::link('News & events', 'admin.soc.news.index', [], 'megaphone', ['admin.soc.news.*']),
                self::link('Events', 'admin.soc.events.index', [], 'flag', ['admin.soc.events.*']),
                self::link('Media library', 'admin.soc.media.index', [], 'folder-open', ['admin.soc.media.*']),
                self::link('Form submissions', 'admin.soc.submissions.index', [], 'inbox', ['admin.soc.submissions.*']),
                self::link('Navigation menu', 'admin.soc.navigation.index', [], 'bars-3', ['admin.soc.navigation.*']),
                self::link('Landing SEO', 'admin.soc.seo.edit', [], 'globe-alt', ['admin.soc.seo.*']),
                self::link('Contact block', 'admin.soc.contact.edit', [], 'map-pin', ['admin.soc.contact.*']),
                self::link('About', 'admin.soc.about.edit', [], 'book-open', ['admin.soc.about.*']),
                self::link('Mission & vision', 'admin.soc.mission-vision.edit', [], 'flag', ['admin.soc.mission-vision.*']),
                self::link('FAQs', 'admin.soc.faqs.index', [], 'chat-bubble-left-right', ['admin.soc.faqs.*']),
                self::link('Motto', 'admin.soc.motto.edit', [], 'sparkles', ['admin.soc.motto.*']),
                self::link('Top bar', 'admin.soc.top-bar.edit', [], 'signal', ['admin.soc.top-bar.*']),
            ];

            $out[] = [
                'label' => 'School of Chaplaincy',
                'items' => [
                    [
                        'type' => 'branch',
                        'label' => 'SOC CMS',
                        'icon' => 'building-library',
                        'children' => $socChildren,
                    ],
                ],
            ];
        }

        if ($managesCohs || $user->hasRole('super_admin')) {
            $cohsChildren = [
                self::link('COHS overview', 'admin.cohs.dashboard', [], 'rectangle-group', ['admin.cohs.dashboard']),
                self::link('Hero & images', 'admin.cohs.hero.edit', [], 'photo', ['admin.cohs.hero.*']),
                self::link('Welcome & programmes band', 'admin.cohs.welcome.edit', [], 'document-text', ['admin.cohs.welcome.*', 'admin.cohs.programmes-band.*']),
                self::link('Testimonials band', 'admin.cohs.testimonials-band.edit', [], 'chat-bubble-left-right', ['admin.cohs.testimonials-band.*']),
                self::link('About us (landing)', 'admin.cohs.about-us.edit', [], 'book-open', ['admin.cohs.about-us.*']),
                self::link('Social life', 'admin.cohs.social-life.edit', [], 'sparkles', ['admin.cohs.social-life.*']),
                self::link('Facilities', 'admin.cohs.facilities.edit', [], 'rectangle-group', ['admin.cohs.facilities.*']),
                self::link('Contact page', 'admin.cohs.contact.edit', [], 'map-pin', ['admin.cohs.contact.*']),
                self::link('Landing SEO', 'admin.cohs.seo.edit', [], 'globe-alt', ['admin.cohs.seo.*']),
                self::link('Top bar & off-campus URL', 'admin.cohs.top-bar.edit', [], 'signal', ['admin.cohs.top-bar.*']),
                self::link('Programmes JSON', 'admin.cohs.json.edit', ['section' => 'programmes'], 'academic-cap', ['admin.cohs.json.*']),
                self::link('Testimonials', 'admin.cohs.testimonials.index', [], 'chat-bubble-left-right', ['admin.cohs.testimonials.*']),
                self::link('Navigation menu', 'admin.cohs.navigation.index', [], 'bars-3', ['admin.cohs.navigation.*']),
                self::link('Media library', 'admin.cohs.media.index', [], 'folder-open', ['admin.cohs.media.*']),
                self::link('Pages', 'admin.cohs.pages.index', [], 'document-text', ['admin.cohs.pages.*']),
                self::link('News & events', 'admin.cohs.news.index', [], 'megaphone', ['admin.cohs.news.*']),
                self::link('Events', 'admin.cohs.events.index', [], 'flag', ['admin.cohs.events.*']),
                self::link('Form submissions', 'admin.cohs.submissions.index', [], 'inbox', ['admin.cohs.submissions.*']),
                self::link('COHS downloads', 'admin.downloads.index', ['school' => 'cohs'], 'arrow-down-tray', ['admin.downloads.*']),
            ];
            if ($cohs) {
                $cohsChildren[] = self::externalUrl('Preview COHS site', route('schools.show', $cohs), 'arrow-top-right-on-square');
            }
            $out[] = [
                'label' => 'College of Health Sciences',
                'items' => [
                    [
                        'type' => 'branch',
                        'label' => 'COHS CMS',
                        'icon' => 'building-library',
                        'children' => $cohsChildren,
                    ],
                ],
            ];
        }

        if ($user->hasRole('super_admin')) {
            $out[] = [
                'label' => 'Administration',
                'items' => [
                    self::link('Activity log', 'admin.activity.index', [], 'document-text', ['admin.activity.*']),
                    self::link('Users & roles', 'admin.users.index', [], 'user-group', ['admin.users.*']),
                    self::link('Global SEO', 'admin.global-seo.edit', [], 'magnifying-glass', ['admin.global-seo.*']),
                    self::link('Settings', 'admin.settings.edit', [], 'cog-6-tooth', ['admin.settings.*']),
                ],
            ];
        }

        return $out;
    }

    /**
     * @param  list<string>  $active
     * @return array<string, mixed>
     */
    private static function link(string $label, string $route, array $params, string $icon, array $active): array
    {
        return [
            'type' => 'link',
            'label' => $label,
            'icon' => $icon,
            'href' => Route::has($route) ? route($route, $params) : '#',
            'active' => $active,
        ];
    }

    /**
     * @param  list<string>  $active
     * @return array<string, mixed>
     */
    private static function external(string $label, string $route, string $icon): array
    {
        return [
            'type' => 'link',
            'label' => $label,
            'icon' => $icon,
            'href' => Route::has($route) ? route($route) : '#',
            'active' => [],
            'external' => true,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function externalUrl(string $label, string $url, string $icon): array
    {
        return [
            'type' => 'link',
            'label' => $label,
            'icon' => $icon,
            'href' => $url,
            'active' => [],
            'external' => true,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function disabled(string $label, string $icon, string $title): array
    {
        return [
            'type' => 'disabled',
            'label' => $label,
            'icon' => $icon,
            'title' => $title,
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     */
    public static function isActive(array $item): bool
    {
        $patterns = $item['active'] ?? [];
        if ($patterns === []) {
            return false;
        }

        foreach ($patterns as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<array<string, mixed>>  $children
     */
    public static function anyChildActive(array $children): bool
    {
        foreach ($children as $child) {
            $type = $child['type'] ?? '';
            if ($type === 'link' && self::isActive($child)) {
                return true;
            }
            if ($type === 'branch' && isset($child['children']) && self::anyChildActive($child['children'])) {
                return true;
            }
        }

        return false;
    }
}
