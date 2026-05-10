<?php

namespace App\Support\Admin;

final class CohsAdminPageHints
{
    public static function message(?string $routeName): ?string
    {
        if ($routeName === null) {
            return null;
        }

        return match ($routeName) {
            'admin.cohs.dashboard' => __('Shortcuts to all COHS tools. Everything here updates the public /cohs site and related inner pages.'),

            'admin.cohs.hero.edit' => __('The main /cohs landing hero: headline, supporting text, buttons, hero image, and logo treatment visitors see first.'),
            'admin.cohs.welcome.edit' => __('The welcome band directly under the hero: intro copy and layout for the COHS landing page.'),
            'admin.cohs.programmes-band.edit' => __('The programmes strip on the landing page: kicker, title, and intro above programme cards.'),
            'admin.cohs.testimonials-band.edit' => __('Heading and intro for the testimonials area on the landing page. Individual quotes are managed under Testimonials.'),
            'admin.cohs.about-us.edit' => __('The public About us page at /cohs/about-us: story, governance, and related blocks.'),
            'admin.cohs.social-life.edit' => __('The Social life inner page: copy and sections for student life content.'),
            'admin.cohs.facilities.edit' => __('The Facilities inner page: descriptions of campuses and learning spaces.'),
            'admin.cohs.contact.edit' => __('COHS contact page content and office details shown on /cohs/contact-us.'),
            'admin.cohs.seo.edit' => __('Default SEO metadata for the COHS site (titles and descriptions search engines may use).'),
            'admin.cohs.top-bar.edit' => __('The thin top announcement or utility bar above the COHS header on public pages.'),

            'admin.cohs.json.edit' => __('Structured JSON for one configurable section (for example programme listings). Changes affect the matching block on the live site.'),

            'admin.cohs.testimonials.index' => __('List of testimonial quotes that can appear in COHS landing or inner pages.'),
            'admin.cohs.testimonials.create' => __('Add a new testimonial quote, attribution, and optional photo.'),
            'admin.cohs.testimonials.edit' => __('Edit this testimonial as it appears across COHS pages.'),

            'admin.cohs.navigation.index' => __('Main and footer links for the COHS public header: labels, order, and destinations.'),
            'admin.cohs.navigation.create' => __('Add a navigation item to the COHS menu.'),
            'admin.cohs.navigation.edit' => __('Change label, URL, or visibility for this menu item.'),

            'admin.cohs.media.index' => __('Images and files uploaded for COHS pages and landing sections. Upload here, then pick assets in other forms.'),

            'admin.cohs.pages.index' => __('School-scoped static pages (for example diploma pages) under /cohs/{slug}. Open one to edit body and SEO.'),
            'admin.cohs.pages.edit' => __('Content and SEO for this COHS page only. Publishing controls whether visitors can open it.'),

            'admin.cohs.news.index' => __('News posts labelled for the College of Health Sciences on the public news hub and COHS areas.'),
            'admin.cohs.news.create' => __('Draft a new COHS news item: title, excerpt, body, and optional featured image.'),
            'admin.cohs.news.edit' => __('Update this news post, schedule publishing, or adjust how it appears publicly.'),

            'admin.cohs.submissions.index' => __('Form messages and applications submitted with COHS selected (for example contact or enquiry forms).'),
            'admin.cohs.submissions.show' => __('Read the full submission payload, mark processed, and follow up outside the CMS if needed.'),

            default => self::fallbackForRoute($routeName),
        };
    }

    private static function fallbackForRoute(string $routeName): ?string
    {
        if (! str_starts_with($routeName, 'admin.cohs.')) {
            return null;
        }

        return __('This screen is part of the COHS admin. Save changes to update the public College of Health Sciences site where that content appears.');
    }
}
