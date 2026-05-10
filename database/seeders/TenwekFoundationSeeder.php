<?php

namespace Database\Seeders;

use App\Models\Download;
use App\Models\DownloadCategory;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\School;
use App\Models\User;
use App\Support\CohsApplicationFormDownloads;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class TenwekFoundationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['super_admin', 'soc_admin', 'cohs_admin'] as $roleName) {
            Role::query()->firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $soc = School::query()->updateOrCreate(
            ['slug' => 'soc'],
            [
                'name' => 'School of Chaplaincy',
                'tagline' => 'Equipping Chaplains for Wholistic service',
                'excerpt' => 'Spiritual care formation integrated with clinical realities, preparing chaplains to serve patients, families, and teams with compassion and theological depth.',
                'body' => '<p>The School of Chaplaincy at Tenwek Hospital College develops leaders in spiritual care who can minister within fast-paced hospital environments without losing sight of the dignity of every person.</p><p>Programmes emphasize pastoral presence, ethics, grief and trauma support, and collaboration with clinical colleagues across Tenwek Hospital.</p>',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $cohs = School::query()->updateOrCreate(
            ['slug' => 'cohs'],
            [
                'name' => 'College of Health Sciences',
                'tagline' => 'Caring in Christ\'s name',
                'excerpt' => 'With over 30 years\' experience training nursing care practitioners, servant leaders in compassionate medical care, anchored at Tenwek Hospital.',
                'body' => '<p>The College of Health Sciences advances nursing and clinical medicine education anchored at Tenwek Hospital. Learners train alongside specialists in medicine, surgery, cardiothoracic care, and community health.</p><p>Our graduates serve across Kenya and the wider region with clinical confidence and Christ-minded compassion.</p>',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $categoriesCohs = [
            ['name' => 'Admission forms', 'slug' => 'admission-forms', 'sort_order' => 1],
            ['name' => 'Student forms', 'slug' => 'student-forms', 'sort_order' => 2],
            ['name' => 'Clinical placement forms', 'slug' => 'clinical-placement-forms', 'sort_order' => 3],
            ['name' => 'Internship forms', 'slug' => 'internship-forms', 'sort_order' => 4],
            ['name' => 'Academic documents', 'slug' => 'academic-documents', 'sort_order' => 5],
            ['name' => 'Policies & guidelines', 'slug' => 'policies-guidelines', 'sort_order' => 6],
        ];

        foreach ($categoriesCohs as $cat) {
            DownloadCategory::query()->updateOrCreate(
                ['school_id' => $cohs->id, 'slug' => $cat['slug']],
                ['name' => $cat['name'], 'sort_order' => $cat['sort_order']]
            );
        }

        $catAdmission = DownloadCategory::query()->where('school_id', $cohs->id)->where('slug', 'admission-forms')->first();

        $fetchPdfs = filter_var(
            env('TENWEK_FETCH_COHS_APPLICATION_PDFS', ! app()->environment('testing')),
            FILTER_VALIDATE_BOOLEAN
        );
        $disk = Storage::disk('downloads');

        foreach (CohsApplicationFormDownloads::definitions() as $form) {
            $originalName = basename(parse_url($form['source_url'], PHP_URL_PATH) ?? '') ?: $form['slug'].'.pdf';
            $relativePath = 'cohs/application-forms/'.$form['slug'].'.pdf';
            $filePath = null;
            $mime = null;
            $sizeBytes = 0;

            if ($fetchPdfs) {
                try {
                    $response = Http::timeout(120)
                        ->retry(2, 1000)
                        ->withHeaders(['User-Agent' => 'TenwekHospitalCollegeSiteSeeder/1.0'])
                        ->get($form['source_url']);
                    if ($response->successful() && $response->body() !== '') {
                        $disk->put($relativePath, $response->body());
                    }
                } catch (\Throwable) {
                    // keep external_url only
                }
            }

            if ($disk->exists($relativePath)) {
                $filePath = $relativePath;
                $mime = 'application/pdf';
                $sizeBytes = (int) $disk->size($relativePath);
            }

            Download::query()->updateOrCreate(
                ['slug' => $form['slug']],
                [
                    'school_id' => $cohs->id,
                    'category_id' => $catAdmission?->id,
                    'title' => $form['title'],
                    'description' => 'Official College of Health Sciences application form.',
                    'file_path' => $filePath,
                    'external_url' => $form['source_url'],
                    'original_filename' => $originalName,
                    'mime' => $mime,
                    'size_bytes' => $sizeBytes,
                    'extension' => 'pdf',
                    'is_active' => true,
                    'published_at' => now(),
                    'seo_title' => $form['title'].' | COHS | '.config('tenwek.name'),
                    'seo_description' => 'Download '.$form['title'].' from Tenwek Hospital College, College of Health Sciences.',
                ]
            );
        }

        $pages = [
            [
                'slug' => 'about',
                'title' => 'About Tenwek Hospital College',
                'excerpt' => 'The School of Chaplaincy and the College of Health Sciences form one college beside Tenwek Hospital, shaping spiritual caregivers and clinical leaders for Kenya and beyond.',
                'template' => 'about-institution',
                'body' => '',
            ],
            ['slug' => 'admissions', 'title' => 'Admissions', 'excerpt' => 'Start your journey with transparent requirements, key dates, and downloadable application materials.'],
            ['slug' => 'academics', 'title' => 'Academics', 'excerpt' => 'Rigorous curricula blending classroom excellence with bedside learning at a Level 5 teaching hospital.'],
            ['slug' => 'programs', 'title' => 'Programmes', 'excerpt' => 'Explore nursing, perfusion, critical care, community health nursing, chaplaincy, and continuing professional development pathways.'],
            ['slug' => 'student-life', 'title' => 'Student life', 'excerpt' => 'Spiritual formation, student support, campus culture, and community living in Bomet County.'],
            ['slug' => 'clinical-training', 'title' => 'Clinical training', 'excerpt' => 'Clinical immersion at Tenwek Hospital — from general wards to specialised centres of excellence.'],
            ['slug' => 'governance', 'title' => 'Governance', 'excerpt' => 'Institutional accountability, academic policies, and alignment with regulatory partners in Kenya.'],
            ['slug' => 'parents', 'title' => 'Parents & guardians', 'excerpt' => 'Resources for families supporting students through healthcare education.'],
            ['slug' => 'faculty-resources', 'title' => 'Faculty & staff', 'excerpt' => 'Internal pathways for educators and administrators serving Tenwek Hospital College.'],
            ['slug' => 'partners', 'title' => 'Partners', 'excerpt' => 'Collaborate with Tenwek Hospital College on training, research, and community health initiatives.'],
            ['slug' => 'international', 'title' => 'International applicants', 'excerpt' => 'Guidance for applicants from outside Kenya seeking clinical or academic placements.'],
        ];

        foreach ($pages as $p) {
            Page::query()->updateOrCreate(
                ['school_id' => null, 'slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'excerpt' => $p['excerpt'],
                    'template' => $p['template'] ?? 'default',
                    'body' => array_key_exists('body', $p)
                        ? $p['body']
                        : '<p>This page is part of the new Tenwek Hospital College platform. Editorial teams can expand this content in the forthcoming CMS module while preserving the full legacy sitemap.</p>',
                    'published_at' => now(),
                    'seo_title' => $p['title'].' | '.config('tenwek.name'),
                    'seo_description' => $p['excerpt'],
                ]
            );
        }

        $legacyNote = '<p>Content structure matches the former <a href="https://tenwekhospitalcollege.ac.ke/" rel="noopener noreferrer">Tenwek Hospital College</a> WordPress site. Replace this placeholder with the published article.</p>';

        $socPages = [
            ['slug' => 'about-us', 'title' => 'About us', 'excerpt' => 'Learn about the School of Chaplaincy at Tenwek Hospital College.'],
            ['slug' => 'register', 'title' => 'Register', 'excerpt' => 'Registration information for School of Chaplaincy programmes.'],
            ['slug' => 'academic-programmes', 'title' => 'Academic programmes', 'excerpt' => 'Programmes offered by the School of Chaplaincy.'],
            ['slug' => 'admissions', 'title' => 'Admissions', 'excerpt' => 'Admissions requirements and process for the School of Chaplaincy.'],
            ['slug' => 'board-and-management-team', 'title' => 'Board and Management Team', 'excerpt' => 'Hospital board oversight and school management: the same governance that serves Tenwek Hospital.'],
            ['slug' => 'faqs', 'title' => 'FAQs', 'excerpt' => 'Frequently asked questions: School of Chaplaincy.'],
            ['slug' => 'fee', 'title' => 'Fees', 'excerpt' => 'Fee information for School of Chaplaincy students.'],
            ['slug' => 'gallery', 'title' => 'Gallery', 'excerpt' => 'Photo gallery from the School of Chaplaincy.'],
            ['slug' => 'message-from-the-principal', 'title' => 'Message from the principal', 'excerpt' => 'A welcome from the principal: our calling, context, and invitation to study chaplaincy at Tenwek.'],
            ['slug' => 'our-history', 'title' => 'Our history', 'excerpt' => 'From the L. Nelson Bell Chaplaincy Training School (1991) to today’s competency-based programmes and TVET – CDACC partnership.'],
            ['slug' => 'strategic-partners', 'title' => 'Strategic Partners', 'excerpt' => 'Africa Gospel Church, Samaritan’s Purse, World Gospel Mission, Friends of Tenwek: partners who walk with us in quality, affordable healthcare and formation.'],
        ];

        foreach ($socPages as $p) {
            Page::query()->updateOrCreate(
                ['school_id' => $soc->id, 'slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'excerpt' => $p['excerpt'],
                    'body' => $legacyNote,
                    'published_at' => now(),
                    'seo_title' => $p['title'].' | '.$soc->name.' | '.config('tenwek.name'),
                    'seo_description' => $p['excerpt'],
                ]
            );
        }

        $socAdmissionsUrl = route('schools.pages.show', [$soc, 'admissions']);

        $socProgrammePages = [
            [
                'slug' => 'certificate-in-chaplaincy',
                'title' => 'Certificate in Chaplaincy',
                'excerpt' => 'Level 5 introductory chaplaincy qualification examined by CDACC.',
                'body' => '<p>Certificate in Chaplaincy (Level 5) is an introductory level to chaplaincy studies that targets students with the qualification of KCSE D (Plain) and above. It shall be a build up to the Diploma programme.</p>'
                    .'<p>This qualification is offered as part of the School of Chaplaincy’s competency-based pathway examined by CDACC. <a href="'.$socAdmissionsUrl.'">Apply online</a>.</p>',
            ],
            [
                'slug' => 'diploma-in-chaplaincy',
                'title' => 'Diploma in Chaplaincy',
                'excerpt' => 'Level 6 advanced chaplaincy programme: two years over six trimesters, examined by CDACC.',
                'body' => '<p>Diploma in Chaplaincy (Level 6) is an advanced level envisioned to prepare chaplains at a higher level of discharging their roles appropriately. The programme has been designed to take 2 years offered in six (6) trimesters.</p>'
                    .'<p>Like the Certificate, the Diploma is examined by CDACC. <a href="'.$socAdmissionsUrl.'">Learn about admissions</a>.</p>',
            ],
            [
                'slug' => 'healthcare-chaplaincy',
                'title' => 'Healthcare Chaplaincy',
                'excerpt' => 'Spiritual care in hospitals, hospices, and other healing environments.',
                'body' => '<p>Healthcare chaplains serve in environments of sickness, pain, birth, and death, including hospital and hospice chaplaincy. They walk alongside patients, families, and staff with compassion and cultural and religious sensitivity.</p>',
            ],
            [
                'slug' => 'military-chaplaincy',
                'title' => 'Military Chaplaincy',
                'excerpt' => 'Counseling and spiritual support for service members and families.',
                'body' => '<p>Military chaplaincy provides counseling and spiritual support for military personnel and families, often across deployments, trauma, loss, and transition.</p>',
            ],
            [
                'slug' => 'educational-chaplaincy',
                'title' => 'Educational Chaplaincy',
                'excerpt' => 'Serving schools, colleges, and universities.',
                'body' => '<p>Educational chaplains serve the spiritual needs of students, teachers, administrators, and support staff at all levels of education, from primary school to higher education.</p>',
            ],
            [
                'slug' => 'police-chaplaincy',
                'title' => 'Police Chaplaincy',
                'excerpt' => 'Support for officers, staff, and families.',
                'body' => '<p>Police chaplaincy provides counseling and spiritual support for police officers, employees, and families, often in the aftermath of critical incidents and sustained operational stress.</p>',
            ],
            [
                'slug' => 'prison-chaplaincy',
                'title' => 'Prison (correctional ministry) chaplaincy',
                'excerpt' => 'Pastoral care in correctional and rehabilitation settings.',
                'body' => '<p>Prison (or correctional ministry) chaplaincy supports inmates, officers, and their families with counseling and spiritual care in men’s and women’s prisons, borstal institutions (youth offenders), rehabilitation schools, and probation departments.</p>',
            ],
            [
                'slug' => 'future-chaplaincy-programmes',
                'title' => 'Future programmes',
                'excerpt' => 'Planned specialties and further levels in chaplaincy formation.',
                'body' => '<p>The school envisions developing other areas of specialty training in chaplaincy alongside other study levels to propel chaplaincy as an area of professional growth.</p>',
            ],
        ];

        foreach ($socProgrammePages as $p) {
            Page::query()->updateOrCreate(
                ['school_id' => $soc->id, 'slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'excerpt' => $p['excerpt'],
                    'body' => $p['body'],
                    'published_at' => now(),
                    'seo_title' => $p['title'].' | '.$soc->name.' | '.config('tenwek.name'),
                    'seo_description' => $p['excerpt'],
                ]
            );
        }

        $cohsPages = [
            [
                'slug' => 'about-us',
                'title' => 'About us',
                'excerpt' => 'Our history, vision, mission, and the hospital board that governs the College of Health Sciences.',
            ],
            ['slug' => 'application-forms', 'title' => 'Application forms', 'excerpt' => 'College of Health Sciences application and document library.'],
            ['slug' => 'contact-us', 'title' => 'Contact us', 'excerpt' => 'Message the College of Health Sciences office, or reach us by phone, email, or post in Bomet.'],
            [
                'slug' => 'diploma-in-clinical-medicine',
                'title' => 'Diploma in Clinical Medicine',
                'excerpt' => 'An accredited three-year diploma plus one-year internship, training clinicians in a compassionate, Christ-like ethos at Tenwek.',
            ],
            [
                'slug' => 'diploma-in-nursing',
                'title' => 'Diploma in Nursing',
                'excerpt' => 'A three-and-a-half-year pathway in classroom learning, clinical rounds, and community health, graduating midwives, general nurses, and community health nurses.',
            ],
            [
                'slug' => 'facilities',
                'title' => 'Facilities',
                'excerpt' => 'Modern campus, accommodation and meals, a well-stocked library with ICT, and a ward-style skills lab for hands-on training.',
            ],
            [
                'slug' => 'offcampus-link',
                'title' => 'Off-campus link',
                'excerpt' => 'Apply online through the college application portal (opens in a new tab).',
            ],
            ['slug' => 'oncampus-link', 'title' => 'On-campus link', 'excerpt' => 'On-campus resources and links for students.'],
            [
                'slug' => 'social-life',
                'title' => 'Social life',
                'excerpt' => 'Facilities and spaces to connect, play, grow in faith, and serve: from sports and CU to trips and community volunteering.',
            ],
        ];

        foreach ($cohsPages as $p) {
            $body = $legacyNote;
            if (in_array($p['slug'], ['about-us', 'application-forms', 'contact-us', 'diploma-in-nursing', 'diploma-in-clinical-medicine', 'facilities', 'offcampus-link', 'social-life'], true)) {
                $body = '';
            }
            Page::query()->updateOrCreate(
                ['school_id' => $cohs->id, 'slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'excerpt' => $p['excerpt'],
                    'body' => $body,
                    'published_at' => now(),
                    'seo_title' => $p['title'].' | '.$cohs->name.' | '.config('tenwek.name'),
                    'seo_description' => $p['excerpt'],
                ]
            );
        }

        NewsPost::query()->updateOrCreate(
            ['slug' => 'welcome-to-the-new-tenwek-college-website'],
            [
                'school_id' => null,
                'title' => 'A refreshed digital home for Tenwek Hospital College',
                'excerpt' => 'Our institutional site now mirrors the clarity and warmth of Tenwek Hospital and the Cardiothoracic Centre.',
                'body' => '<p>We are rolling out a faster, mobile-first experience with enterprise security, structured data for search engines, and dedicated portals for each school.</p>',
                'published_at' => now(),
                'seo_title' => 'News | '.config('tenwek.name'),
                'seo_description' => 'Updates from Tenwek Hospital College on academics, admissions, and hospital partnerships.',
            ]
        );

        $super = User::query()->updateOrCreate(
            ['email' => 'admin@tenwekhospitalcollege.ac.ke'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('ChangeMe!2026'),
                'school_id' => null,
                'is_active' => true,
            ]
        );
        $super->syncRoles(['super_admin']);

        $socUser = User::query()->updateOrCreate(
            ['email' => 'soc.admin@tenwekhospitalcollege.ac.ke'],
            [
                'name' => 'SOC Administrator',
                'password' => Hash::make('ChangeMe!2026'),
                'school_id' => $soc->id,
                'is_active' => true,
            ]
        );
        $socUser->syncRoles(['soc_admin']);

        $cohsUser = User::query()->updateOrCreate(
            ['email' => 'cohs.admin@tenwekhospitalcollege.ac.ke'],
            [
                'name' => 'COHS Administrator',
                'password' => Hash::make('ChangeMe!2026'),
                'school_id' => $cohs->id,
                'is_active' => true,
            ]
        );
        $cohsUser->syncRoles(['cohs_admin']);
    }
}
