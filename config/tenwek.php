<?php

return [
    'name' => env('TENWEK_COLLEGE_NAME', 'Tenwek Hospital College'),
    'tagline' => env('TENWEK_COLLEGE_TAGLINE', 'Excellence in medical, nursing, and chaplaincy education, rooted in Christ-minded service.'),
    'institution_legal' => env('TENWEK_INSTITUTION_LEGAL', 'Tenwek Hospital College'),
    'url' => env('APP_URL', 'https://tenwekhospitalcollege.ac.ke'),
    'locale' => env('APP_LOCALE', 'en_KE'),
    'email_public' => env('TENWEK_PUBLIC_EMAIL', 'info@tenwekhospitalcollege.ac.ke'),
    'phone' => env('TENWEK_PUBLIC_PHONE', '+254 700 000 000'),
    'address' => [
        'street' => 'P.O. Box 39, 20400',
        'locality' => 'Bomet',
        'region' => 'Bomet County',
        'country' => 'KE',
        'country_name' => 'Kenya',
    ],
    'geo' => [
        'latitude' => env('TENWEK_GEO_LAT', '-0.7792'),
        'longitude' => env('TENWEK_GEO_LNG', '35.3369'),
    ],
    'hospital' => [
        'name' => 'Tenwek Hospital',
        'url' => 'https://tenwek.designekta.com/',
    ],
    'ctc' => [
        'name' => 'Tenwek Cardiothoracic Centre',
        'url' => 'https://ctc.tenwek.designekta.com/',
    ],
    /** Defaults match the legacy WordPress college site; override with env or set to empty string to hide. */
    'social' => array_filter([
        'facebook' => env('TENWEK_SOCIAL_FACEBOOK', 'https://www.facebook.com/TenwekHospital/'),
        'linkedin' => env('TENWEK_SOCIAL_LINKEDIN'),
        'youtube' => env('TENWEK_SOCIAL_YOUTUBE'),
        'x' => env('TENWEK_SOCIAL_X', 'https://twitter.com/tenwekhosp'),
    ]),
    'default_og_image' => 'ctc.jpg',

    /** Public path under public/ or absolute http(s) URL for the official circular seal. */
    'brand_logo' => env('TENWEK_BRAND_LOGO', 'images/tenwek-hospital-logo.png'),

    'hero' => [
        'credibility' => env('TENWEK_HERO_CREDIBILITY', 'Est. 1937 · Level 5 Teaching & Referral · Bomet, Kenya'),
        'image' => env('TENWEK_HERO_IMAGE', 'ctc.jpg'),
        'image_alt' => env('TENWEK_HERO_IMAGE_ALT', 'Tenwek Hospital College — clinical and academic excellence in Bomet, Kenya'),
    ],

    'landing' => [
        'cream_bg' => env('TENWEK_LANDING_SECTION_BG', '#FFFFFF'),
        'soc_purple' => env('TENWEK_SOC_CARD_BG', '#800000'),
        /** Hero image for School of Chaplaincy card on the home page (under public/). */
        'soc_card_image' => env('TENWEK_SOC_CARD_IMAGE', 'banner-a.jpg'),
        /** Hero image for College of Health Sciences / nursing card on the home page (under public/). */
        'cohs_card_image' => env('TENWEK_COHS_CARD_IMAGE', 'banner-nursing.jpg'),
        'pillars' => [
            [
                'title' => 'Clinical training',
                'description' => 'Hands-on education anchored at Tenwek Hospital — a Level 5 teaching and referral mission hospital serving Kenya and the wider region.',
                'route' => 'pages.show',
                'params' => ['slug' => 'clinical-training'],
                'cta' => 'Learn more',
            ],
            [
                'title' => 'Trusted governance',
                'description' => 'Transparent policies, academic integrity, and spiritual formation woven into every programme.',
                'route' => 'pages.show',
                'params' => ['slug' => 'governance'],
                'cta' => 'Governance',
            ],
            [
                'title' => 'Downloads hub',
                'description' => 'Admission packs, clinical placement forms, and student resources — searchable and kept current.',
                'route' => 'downloads.index',
                'params' => [],
                'cta' => 'Browse downloads',
            ],
        ],
    ],

    /**
     * School of Chaplaincy landing (/soc): section flow aligned with
     * https://tenwekhospitalcollege.ac.ke/soc/ (hero → about → mission/vision → motto → testimonials → contact).
     */
    'soc_landing' => [
        /** Navbar mark for /soc (file under public/). */
        'logo' => env('TENWEK_SOC_LOGO', 'logo-chaplain.png'),
        'hero_image' => env('TENWEK_SOC_LANDING_HERO_IMAGE', 'banner-a.jpg'),
        'about_image' => env('TENWEK_SOC_LANDING_ABOUT_IMAGE', 'banner-a.jpg'),
        'map_embed_url' => env('TENWEK_SOC_MAP_EMBED_URL', 'https://maps.google.com/maps?q=-0.7792,35.3369&z=14&output=embed'),
        /** Managed from admin (SOC → SEO). Null values fall back to auto-generated meta. */
        'landing_seo' => [
            'title' => null,
            'description' => null,
            'keywords' => null,
            'canonical' => null,
            'og_title' => null,
            'og_description' => null,
            'og_image' => null,
            'robots' => null,
        ],
        'nav_sections' => [
            ['id' => 'about', 'label' => 'About'],
            ['id' => 'mission-vision', 'label' => 'Mission & vision'],
            ['id' => 'motto', 'label' => 'Motto'],
            ['id' => 'testimonials', 'label' => 'Testimonials'],
            ['id' => 'contact', 'label' => 'Contact'],
        ],
        /**
         * Primary SOC header links (mirrors http://tenwekhospitalcollege.ac.ke/soc/ menu hierarchy).
         * Slugs are School of Chaplaincy page slugs (see TenwekFoundationSeeder).
         */
        'main_nav' => [
            [
                'label' => 'About Us',
                'children' => [
                    ['label' => 'Our History', 'slug' => 'our-history'],
                    ['label' => 'Message from the Principal', 'slug' => 'message-from-the-principal'],
                    ['label' => 'Board and Management Team', 'slug' => 'board-and-management-team'],
                    ['label' => 'Strategic Partners', 'slug' => 'strategic-partners'],
                ],
            ],
            ['label' => 'Academic Programmes', 'slug' => 'academic-programmes'],
            ['label' => 'Fees', 'slug' => 'fee'],
            ['label' => 'Admissions', 'slug' => 'admissions'],
            ['label' => 'Gallery', 'slug' => 'gallery'],
            ['label' => 'FAQs', 'slug' => 'faqs'],
        ],
        'hero' => [
            'badge' => 'Equipping chaplains for wholistic service',
            'eyebrow' => 'Tenwek Hospital College · School of Chaplaincy',
            'headline' => 'School of Chaplaincy',
            'subhead' => 'The best place for chaplaincy education: Christ-centred, academically rigorous, and rooted in compassionate hospital ministry.',
            'primary_cta' => ['label' => 'Choose a programme', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'academic-programmes']],
            'secondary_cta' => ['label' => 'Talk to us today', 'hash' => 'contact'],
        ],
        'about' => [
            'kicker' => 'Karibu',
            'title' => 'The Tenwek Hospital College School of Chaplaincy',
            'lead' => 'Excellence in healthcare chaplaincy, forming leaders who serve the whole person.',
            'paragraphs' => [
                'The Tenwek Hospital College – School of Chaplaincy was established in 1991 as the L. Nelson Bell Chaplaincy Training School. Dr. Ernie Steury, who was the first doctor to serve at Tenwek Hospital, realized that the institution needed full-time chaplains to serve alongside the dedicated medical team.',
                'There was no college or university offering training in chaplaincy at the time. That gap gave birth to the vision of opening a chaplaincy school at Tenwek Hospital: a vision we continue to live out today.',
            ],
        ],
        'vision' => [
            'title' => 'Vision',
            'text' => 'Christ transformed and competent professionals serving humanity.',
        ],
        'mission' => [
            'title' => 'Mission',
            'text' => 'To prepare Christ-like professionals for competent service through holistic, quality, Bible-centred training and research.',
        ],
        'motto' => [
            'kicker' => 'Our motto',
            'text' => 'To equip chaplains for wholistic service.',
        ],
        'testimonials' => [
            'kicker' => 'Chaplains say',
            'title' => 'Testimonials',
            'items' => [
                [
                    'role' => 'Retired chaplain',
                    'quote' => 'I served as a chaplain at Tenwek Hospital for the better part of my adult life. This was a fulfilling experience and I witnessed first-hand how God uses chaplains to inspire, encourage and comfort His people. Given the opportunity, I would go back to the Tenwek Hospital College School of Chaplaincy to get formal training.',
                    'name' => 'Mrs. Hellen Tangus',
                ],
                [
                    'role' => 'Alumnae, School of Chaplaincy',
                    'quote' => 'I am a trained secondary school teacher by profession and I was quite disturbed to see young girls and boys lose their way to the world. I chose to study chaplaincy and this is the best decision I have made so far. The experience and skills I gained from Tenwek Hospital College School of Chaplaincy prepared me to handle numerous situations within my line of duty.',
                    'name' => 'Robert Sang',
                ],
            ],
        ],
        /**
         * /soc/our-history: narrative from https://tenwekhospitalcollege.ac.ke/soc/our-history/
         * plus structured milestones for the timeline UI.
         */
        'our_history' => [
            'kicker' => 'About School of Chaplaincy',
            'intro_paragraphs' => [
                'The Tenwek Hospital College – School of Chaplaincy was established in 1991 as the L. Nelson Bell Chaplaincy Training School. Dr. Ernie Steury, who was the first doctor to serve at Tenwek Hospital, realised that the institution needed full-time chaplains to serve alongside the dedicated medical team. There was no college or university offering training in chaplaincy at the time, and that gave birth to the vision of opening one at Tenwek Hospital.',
                'Over 29 years later, the school had trained more than 200 students from various African nations including Rwanda, Zambia, Zimbabwe, Malawi, Uganda, Tanzania, Ghana, Nigeria, Madagascar and the Democratic Republic of Congo (DRC).',
                'Due to changing chaplaincy needs, the school has redesigned and upgraded its curriculum. This ensures consistency with the ongoing development of competency-based curriculum in all institutions of learning in Kenya. The school is working closely with Technical and Vocational Education and Training – Curriculum Development, Assessment and Certification Council (TVET – CDACC) in the development of Certificate and Diploma in Chaplaincy curricula that is nearing completion. The school envisions developing other areas of specialisation in chaplaincy alongside further study levels to advance chaplaincy as a field of professional growth.',
            ],
            'milestones' => [
                [
                    'year' => '1991',
                    'title' => 'L. Nelson Bell Chaplaincy Training School',
                    'body' => 'The School of Chaplaincy opens at Tenwek Hospital: the first dedicated response to the need for full-time, professionally formed chaplains alongside the medical team.',
                ],
                [
                    'year' => '1991–2020',
                    'title' => 'Formation across Africa',
                    'body' => 'In the decades following its founding, the school trained more than 200 students from Rwanda, Zambia, Zimbabwe, Malawi, Uganda, Tanzania, Ghana, Nigeria, Madagascar and the Democratic Republic of Congo (DRC), among others.',
                ],
                [
                    'year' => 'Today',
                    'title' => 'Competency-based curriculum & TVET – CDACC',
                    'body' => 'The curriculum has been redesigned and upgraded to align with competency-based education in Kenya. The school partners with TVET – CDACC on Certificate and Diploma in Chaplaincy programmes nearing completion, with a vision for further specialisations and study levels.',
                ],
            ],
            'about_sidebar' => [
                ['label' => 'Our History', 'slug' => 'our-history'],
                ['label' => 'Message from the Principal', 'slug' => 'message-from-the-principal'],
                ['label' => 'Board and Management Team', 'slug' => 'board-and-management-team'],
                ['label' => 'Strategic Partners', 'slug' => 'strategic-partners'],
            ],
        ],
        /**
         * /soc/message-from-the-principal: from https://tenwekhospitalcollege.ac.ke/soc/message-from-the-principal/
         */
        'message_from_principal' => [
            'kicker' => 'Welcome to Tenwek Hospital College · School of Chaplaincy.',
            'motto' => 'Equip Chaplains for Wholistic Service',
            /** Set to a path under public/ (e.g. images/soc/principal.jpg) when available; empty shows a placeholder frame. */
            'principal_image' => env('TENWEK_SOC_PRINCIPAL_IMAGE'),
            'principal_image_alt' => env('TENWEK_SOC_PRINCIPAL_IMAGE_ALT', 'Principal, School of Chaplaincy'),
            'principal_placeholder_caption' => 'Photograph coming soon',
            'paragraphs' => [
                'I am glad that you have chosen to read these words of welcome. The School of Chaplaincy is a specialised training institution whose motto is to equip chaplains for wholistic service, and we are committed to training wholesome chaplains who will meet the spiritual needs of children of God serving in various capacities in our educational institutions, military formations, correctional establishments and during events outside the four corners of the church.',
                'With extensive experience spanning over 29 years, the School of Chaplaincy is equipped with resources and a wealth of experience that makes us a premier institution in Kenya and across East Africa, focused on training chaplains to serve.',
                'Our school is surrounded by various institutions including Tenwek Hospital, several educational institutions (primary and secondary schools, colleges, universities), correctional facilities, sports teams based at Silibwet and much more, creating a conducive learning and practice environment for our scholars.',
                'Embedded in deep Christian roots, students are taught and mentored by great scholars in the fields of learning with utmost dedication and commitment. The school prides itself on a competency-based curriculum aligned to the special training needs of a chaplain, informed by a comprehensive chaplaincy training needs analysis undertaken in institutions across the country. The diverse modes of curriculum delivery are an advantage anyone would want to maximise. International and local students challenged by distance are not left behind; they can enrol for interactive online studies.',
            ],
            'belief' => 'We strongly believe that God has called some to be chaplains, and they are burning with great passion, as expressed by the great apostle:',
            'scripture' => [
                'text' => 'To the Jew I became like a Jew, to win the Jews … To the weak I became weak, to win the weak. I have become all things to all people so that by all possible means some might be saved.',
                'reference' => '1 Corinthians 9:19–22',
            ],
            'invitation' => 'If these words resonate with you, or you know someone with such longings, the School of Chaplaincy is the place for you and for them. Come, and we will support your God-given calling to fruitfulness.',
        ],
        /**
         * /soc/board-and-management-team: from https://tenwekhospitalcollege.ac.ke/soc/board-and-management-team/
         */
        'board_and_management' => [
            'intro' => 'Tenwek Hospital College · School of Chaplaincy is a training subsidiary of Tenwek Hospital. The board that governs the school is therefore the same board that governs the hospital.',
            'photo_note' => 'Official portraits are displayed on each card as they become available.',
            'board_heading' => 'Board',
            /** Optional per person: `'image' => 'images/board/filename.jpg'` */
            'board' => [
                ['name' => 'Rev. John Kisotu', 'role' => 'Assistant Bishop, AGC Kenya', 'highlight' => false],
                ['name' => 'Rev. Dr. Robert Langat', 'role' => 'Bishop, AGC Kenya & Tenwek Hospital Board Chair', 'highlight' => true],
                ['name' => 'Daniel Kirui', 'role' => 'Board member', 'highlight' => false],
                ['name' => 'Isaac Kitur', 'role' => 'Board member', 'highlight' => false],
                ['name' => 'Collins Cheruiyot', 'role' => 'Board member', 'highlight' => false],
            ],
            'management_heading' => 'School management',
            'management' => [
                ['name' => 'Mr Benjamin Siele', 'role' => 'AGC Tenwek Hospital CEO & management chair', 'highlight' => true],
                ['name' => 'Mr Weldon Kigen', 'role' => 'Finance director', 'highlight' => false],
                ['name' => 'Rev. Elijah Bii', 'role' => 'Director of spiritual services & member', 'highlight' => false],
                ['name' => 'Mr Jackson Mosonik', 'role' => 'Principal, Tenwek Hospital College of Health Sciences & member', 'highlight' => false],
            ],
        ],
        /**
         * /soc/strategic-partners: from https://tenwekhospitalcollege.ac.ke/soc/strategic-partners/
         */
        'strategic_partners' => [
            'intro' => [
                'They say that a good friend knows all your best stories, but a best friend has lived them with you. We pride ourselves in having wonderful strategic partners who have walked with us in our quest to provide quality yet affordable healthcare.',
                'Important institutions we have partnered with include:',
            ],
            /** Optional per partner: `'image' => 'images/partners/agc.jpg'` (path under public/). */
            'partners' => [
                [
                    'name' => 'The Africa Gospel Church',
                    'abbr' => 'AGC',
                    'paragraphs' => [
                        'As the medical ministry of the Africa Gospel Church, Tenwek Hospital falls under the Compassion Department in the Health Outreach Programme section. We draw our Christian values and standard operating procedures from biblically inspired teaching, as instructed by the doctrines of the AGC.',
                    ],
                    'list_intro' => 'The Africa Gospel Church plays the following roles in the management of Tenwek Hospital:',
                    'bullets' => [
                        'Provides spiritual support and guidance',
                        'Nominates the board of trustees who oversee the running of the hospital',
                        'The bishop and assistant bishop of the AGC serve as board chair and assistant board chair respectively',
                        'Connects Tenwek with strategic partners and stakeholders who help propel the hospital towards growth',
                    ],
                    'closing' => 'We are proud to be affiliated with the Africa Gospel Church and look forward to a continued and sustained relationship.',
                ],
                [
                    'name' => 'Samaritan’s Purse',
                    'abbr' => 'SP',
                    'paragraphs' => [
                        'The mission of Samaritan’s Purse is to follow the example of Christ by helping those in need and proclaiming the hope of the gospel. Over the years, our partnership has been sustained through the grace and favour of God.',
                    ],
                    'list_intro' => 'Through Samaritan’s Purse, Tenwek Hospital benefits in the following ways:',
                    'bullets' => [
                        'Equipment and medical supplies support',
                        'Support for our chaplains’ Bible ministry',
                        'Connecting missionary doctors and surgeons who volunteer at Tenwek Hospital',
                        'Prayer and support for the hospital across various projects',
                    ],
                    'closing' => null,
                ],
                [
                    'name' => 'World Gospel Mission',
                    'abbr' => 'WGM',
                    'paragraphs' => [
                        'The history of Tenwek Hospital is incomplete without World Gospel Mission. The WGM team engages Christ-like disciples to transform the world.',
                    ],
                    'list_intro' => 'Over the years, World Gospel Mission has supported Tenwek Hospital in the following ways:',
                    'bullets' => [
                        'Representation on our board and management teams',
                        'Sending missionaries to serve in different capacities within the hospital',
                        'Raising funds on our behalf and supporting projects across the facility',
                        'Offering spiritual support and guidance',
                    ],
                    'closing' => null,
                ],
                [
                    'name' => 'Friends of Tenwek',
                    'abbr' => 'FoT',
                    'paragraphs' => [
                        'Established in 2009, Friends of Tenwek is dedicated to developing key relationships and resources that help the hospital fulfil its mission, often through capturing impactful stories about people and the mission at Tenwek Hospital.',
                    ],
                    'list_intro' => 'Through Friends of Tenwek, the hospital is able to:',
                    'bullets' => [
                        'Initiate hospital projects and programmes that are resource intensive',
                        'Sustain our chaplaincy programmes and the School of Chaplaincy',
                        'Establish and sustain community health programmes',
                        'Create impact in the lives of both staff and patients',
                        'Support development initiatives by the Tenwek Hospital College of Health Sciences',
                    ],
                    'closing' => null,
                ],
            ],
        ],
        /**
         * /soc/academic-programmes: mirrors https://tenwekhospitalcollege.ac.ke/soc/academic-programmes/
         * Course cards link to /soc/{slug} pages seeded in TenwekFoundationSeeder.
         */
        'academic_programmes' => [
            'kicker' => 'Our courses',
            'intro_paragraphs' => [
                'Chaplaincy Levels 5 and 6 qualifications consist of competencies that an individual must achieve to enable him/her to be certified as a chaplain. The two levels shall be examined by CDACC.',
                'A chaplain is a priest or a minister who can demonstrate competency in administering to the spiritual needs of persons from diverse faith backgrounds or no faith background, within a given institution, including but not limited to: hospitals, schools, colleges, universities, business/corporate settings, NGOs, sports organizations, airports, police departments, fire departments, military, correctional institutions, intelligence agencies, government bodies, etc.',
                'Therefore, a chaplain is a uniquely-trained person who can carry out these responsibilities. These responsibilities comprise 30 units of competency training in Chaplaincy Diploma level.',
            ],
            'groups' => [
                [
                    'heading' => 'Certificate and Diploma',
                    'description' => 'The current Certificate and Diploma in Chaplaincy will introduce these specialised areas of chaplaincy to be continued in other levels and beyond.',
                    'items' => [
                        [
                            'slug' => 'certificate-in-chaplaincy',
                            'title' => 'Certificate in Chaplaincy',
                            'badge' => 'Level 5',
                            'summary' => 'An introductory level (Level 5) for students with KCSE D (Plain) and above, a build-up to the Diploma.',
                        ],
                        [
                            'slug' => 'diploma-in-chaplaincy',
                            'title' => 'Diploma in Chaplaincy',
                            'badge' => 'Level 6',
                            'summary' => 'An advanced level (Level 6) to prepare chaplains to serve well: two years over six (6) trimesters, examined by CDACC.',
                        ],
                    ],
                ],
                [
                    'heading' => 'Specialised areas',
                    'description' => null,
                    'items' => [
                        [
                            'slug' => 'healthcare-chaplaincy',
                            'title' => 'Healthcare Chaplaincy',
                            'badge' => null,
                            'summary' => 'Serving in environments of sickness, pain, birth, and death, including hospital and hospice chaplaincy.',
                        ],
                        [
                            'slug' => 'military-chaplaincy',
                            'title' => 'Military Chaplaincy',
                            'badge' => null,
                            'summary' => 'Counseling and spiritual support for military personnel and families.',
                        ],
                        [
                            'slug' => 'educational-chaplaincy',
                            'title' => 'Educational Chaplaincy',
                            'badge' => null,
                            'summary' => 'Serving the spiritual needs of students, teachers, administrators, and support staff from primary school to higher education.',
                        ],
                        [
                            'slug' => 'police-chaplaincy',
                            'title' => 'Police Chaplaincy',
                            'badge' => null,
                            'summary' => 'Counseling and spiritual support for police officers, employees, and families.',
                        ],
                        [
                            'slug' => 'prison-chaplaincy',
                            'title' => 'Prison (correctional ministry) chaplaincy',
                            'badge' => null,
                            'summary' => 'Supporting inmates, officers, and their families with counseling and spiritual care in prisons, borstal institutions, rehabilitation schools, and probation departments.',
                        ],
                    ],
                ],
                [
                    'heading' => 'Future programmes',
                    'description' => null,
                    'items' => [
                        [
                            'slug' => 'future-chaplaincy-programmes',
                            'title' => 'Future programmes',
                            'badge' => null,
                            'summary' => 'The school envisions developing other areas of specialty training in chaplaincy alongside further study levels.',
                        ],
                    ],
                ],
            ],
        ],
        /**
         * /soc/fee: mirrors https://tenwekhospitalcollege.ac.ke/soc/fee/
         */
        'fee' => [
            'kicker' => 'Fees & finance',
            'intro' => 'Figures below are per trimester unless noted. The school does not accept cash on site; please use the bank transfer or M-Pesa pay bill details provided.',
            'structure' => [
                'title' => '2021 fee structure (per trimester)',
                'columns' => [
                    'diploma' => 'Diploma',
                    'certificate' => 'Certificate',
                ],
                'rows' => [
                    ['description' => 'Tuition fees', 'diploma' => 20_000, 'certificate' => 10_000],
                    ['description' => 'Registration', 'diploma' => 1_000, 'certificate' => 1_000],
                    ['description' => 'Activity', 'diploma' => 1_000, 'certificate' => 1_000],
                    ['description' => 'Library fee', 'diploma' => 1_500, 'certificate' => 1_500],
                    ['description' => 'Computer facilities fee', 'diploma' => 2_000, 'certificate' => 1_000],
                    ['description' => 'Medical (emergency)', 'diploma' => 1_000, 'certificate' => 1_000],
                    ['description' => 'Repairs, maintenance and improvement', 'diploma' => 1_500, 'certificate' => 1_500],
                    ['description' => 'Examination fees (internal)', 'diploma' => 1_000, 'certificate' => 500],
                ],
                'totals' => ['diploma' => 29_000, 'certificate' => 17_500],
            ],
            'one_time' => [
                'title' => 'One-time fees (new students)',
                'items' => [
                    ['label' => 'Application fees', 'amount' => 1_000],
                    ['label' => 'Student ID', 'amount' => 500],
                    ['label' => 'Caution money', 'amount' => 3_000],
                ],
            ],
            'optional' => [
                'title' => 'Optional fees',
                'body' => 'Boarding on campus is optional. If desired, a student pays Ksh 24,000 per trimester.',
            ],
            'payment_notice' => 'Payment of fees to the school: pay to the following account. No cash payment is accepted.',
            'bank' => [
                'account_name' => 'Tenwek Hospital School of Chaplaincy',
                'bank_name' => 'Kenya Commercial Bank',
                'branch' => 'Bomet',
                'account_number' => '1104955806',
                'bank_code' => '01181',
                'branch_code' => '038',
                'swift' => 'KCBLKENX',
                'currency' => 'KSH',
            ],
            'mpesa' => [
                'title' => 'M-Pesa',
                'paybill' => '522522',
                'account' => '1104955806',
                'confirmation_note' => 'After payment, SMS the message to 0716178653 for confirmation.',
            ],
        ],
        /**
         * /soc/admissions: mirrors https://tenwekhospitalcollege.ac.ke/soc/admissions/
         */
        'admissions' => [
            'kicker' => 'Admissions',
            'intro' => 'Explore programme materials below, then review entry requirements for the Certificate and Diploma. We are glad to walk with you through the process.',
            'contact_prompt' => 'Contact us for more details',
            'resources' => [
                [
                    'title' => 'Download school brochure',
                    'description' => 'Overview of the School of Chaplaincy, programmes, and campus life.',
                    'cta' => 'Browse downloads',
                    'route' => 'downloads.index',
                    'route_params' => ['school' => 'soc'],
                ],
                [
                    'title' => 'Application form',
                    'description' => 'Admission and registration forms for new applicants.',
                    'cta' => 'Get forms',
                    'route' => 'downloads.index',
                    'route_params' => ['school' => 'soc'],
                ],
                [
                    'title' => 'Online application',
                    'description' => 'Apply or continue your application through the online student portal.',
                    'cta' => 'Open portal',
                    'external' => true,
                    /** Falls back to soc_landing.top_bar.portal_url when null. */
                    'url' => env('TENWEK_SOC_ADMISSIONS_PORTAL_URL'),
                ],
            ],
            'requirements' => [
                'title' => 'Admission requirements',
                'certificate' => [
                    'title' => 'Certificate in Chaplaincy',
                    'body' => 'KCSE D (Plain) and above.',
                ],
                'diploma' => [
                    'title' => 'Diploma in Chaplaincy',
                    'items' => [
                        'Must have attained a pass in their previous qualification or Certificate in Chaplaincy (Level 5), or',
                        'KCSE mean grade of C- and above or its equivalent, or high school certificate (A-level) with a minimum of two principal passes and one subsidiary or its equivalent, or a distinction or credit from an accredited institution.',
                    ],
                ],
                'intake_note' => 'Admissions are done in January, May and September of each year.',
            ],
        ],
        /**
         * /soc/gallery: mirrors https://tenwekhospitalcollege.ac.ke/soc/gallery/
         * Replace or extend `items` with paths under public/ (e.g. images/soc/gallery/photo.jpg).
         */
        'gallery' => [
            'kicker' => 'Our gallery',
            'headline' => 'SOC life',
            'intro' => 'Worship, study, and service in the Tenwek Hospital College School of Chaplaincy, alongside the wider hospital and college community in Bomet.',
            'items' => [
                [
                    'src' => 'banner-a.jpg',
                    'alt' => 'Tenwek Hospital College: chaplaincy and campus context',
                    'caption' => 'Formation in the Tenwek hills',
                ],
                [
                    'src' => 'ctc.jpg',
                    'alt' => 'Tenwek Hospital: ministry alongside clinical care',
                    'caption' => 'Where chaplains walk with patients and staff',
                ],
                [
                    'src' => 'banner-nursing.jpg',
                    'alt' => 'Tenwek Hospital College: health sciences and shared mission',
                    'caption' => 'One college family, many vocations',
                ],
            ],
        ],
        /**
         * /soc/faqs: mirrors https://tenwekhospitalcollege.ac.ke/soc/faqs/
         */
        'faqs' => [
            'kicker' => 'FAQs',
            'intro' => 'Quick answers about chaplaincy formation, entry requirements, and career pathways at the School of Chaplaincy.',
            'items' => [
                [
                    'question' => 'Is there a difference between a pastor and a chaplain?',
                    'lead' => 'Yes there is. Pastors and chaplains are trained differently and they serve in different ministries. Below are examples of key differences that distinguish the practice of the two:',
                    'comparison' => [
                        'left_header' => 'Pastors',
                        'right_header' => 'Chaplains',
                        'rows' => [
                            [
                                'left' => 'Ministry is church-based',
                                'right' => 'Ministry is beyond the church: it is community- and institution-based. Chaplains are the bridge between secular and sacred.',
                            ],
                            [
                                'left' => 'Serve members of same faith',
                                'right' => 'Serve people of all faiths',
                            ],
                            [
                                'left' => 'Members go to church where the pastor is',
                                'right' => 'Chaplains go where people are working, studying, healing, serving, or imprisoned',
                            ],
                            [
                                'left' => 'Trained to teach and lead in specific denominational doctrines and beliefs',
                                'right' => 'Trained to talk with and counsel people from a variety of faith backgrounds. Chaplains often function as supportive listeners more than verbal advisors',
                            ],
                            [
                                'left' => 'Shares his or her faith with members',
                                'right' => 'Meets the needs of the client, and does not share his or her own beliefs unless asked',
                            ],
                        ],
                    ],
                ],
                [
                    'question' => 'Why should I register for Certificate or Diploma in Chaplaincy?',
                    'paragraphs' => [
                        'Registering for Certificate or Diploma in Chaplaincy exposes you to theory and practical work. The theory equips every chaplain with the right concepts to approach everyday challenges, while the practical work exposes students to real-life scenarios so they can practice what they have learnt in class. This all-rounded training helps ensure that graduates are qualified to serve in different settings.',
                    ],
                ],
                [
                    'question' => 'What is the minimum qualification required to join the programme?',
                    'certificate' => [
                        'title' => 'Certificate in Chaplaincy',
                        'body' => 'KCSE D (Plain) and above.',
                    ],
                    'diploma' => [
                        'title' => 'Diploma in Chaplaincy admission requirements',
                        'items' => [
                            'Must have attained a pass in their previous qualification or Certificate in Chaplaincy (Level 5), or',
                            'KCSE mean grade of C- and above or its equivalent, or high school certificate (A-level) with a minimum of two principal passes and one subsidiary or its equivalent, or a distinction or credit from an accredited institution.',
                        ],
                    ],
                ],
                [
                    'question' => 'How long is the course?',
                    'lines' => [
                        'Certificate in Chaplaincy: 1 year, offered in 3 trimesters.',
                        'Diploma in Chaplaincy: 2 years, over 6 trimesters.',
                    ],
                ],
                [
                    'question' => 'Where can I serve after completing my studies?',
                    'paragraphs' => [
                        'Chaplains are trained to serve in a variety of settings including schools, universities, hospitals, correctional ministry, sports, the military or police, and many other institutions. We believe that need versus the supply of trained chaplains remains significant for reasons such as:',
                    ],
                    'bullets' => [
                        'In 2016, the Cabinet Secretary for Education announced that public high schools would be required to have a trained chaplain serving the student population, creating strong demand in the education sector.',
                        'Institutions across Kenya continue to recognise the role of chaplains. Between January and July 2019, over ten institutions advertised chaplaincy vacancies, including AIC Githumu Mission Hospital, PAC University, Nova Pioneer Schools, and Brookhurst International School.',
                    ],
                ],
            ],
        ],
        /** Slim strip above main SOC nav (matches legacy SOC bar: email, phone, portal). */
        'top_bar' => [
            'email' => env('TENWEK_SOC_EMAIL', 'soc@tenwekhosp.org'),
            'call_prefix' => 'Call:',
            /** Shown in the bar; use en dash before Ext as on the live site. */
            'call_display' => env('TENWEK_SOC_TOP_BAR_PHONE', '+254 728 091 900, Ext 1315/1334'),
            /** Dialable number for tel: (main line; extensions dial separately). */
            'call_tel' => env('TENWEK_SOC_TOP_BAR_TEL', '+254728091900'),
            'portal_label' => 'Online Portal',
            /** Student/LMS portal; falls back to SOC downloads if unset. */
            'portal_url' => env('TENWEK_SOC_PORTAL_URL', 'http://197.138.207.16:3036/osmis/'),
        ],
        'contact' => [
            'kicker' => 'Contact us',
            'location_lines' => [
                'Silibwet Township, Bomet Central, Kenya',
                'P.O. Box 39–20400, Bomet, Kenya',
                'Tenwek Hospital College · School of Chaplaincy',
            ],
            'phones' => [
                '+254 728 091 900, Ext. 1315 / 1334',
                '+254 202 045 542, Ext. 1315 / 1334',
            ],
            'email' => env('TENWEK_SOC_EMAIL', 'soc@tenwekhosp.org'),
            'office_hours_lines' => [],
            'social_links' => [],
        ],
    ],

    /**
     * College of Health Sciences landing (/cohs): section flow aligned with
     * https://tenwekhospitalcollege.ac.ke/cohs/
     */
    'cohs_landing' => [
        /** Navbar mark for /cohs (file under public/). */
        'logo' => env('TENWEK_COHS_LOGO', 'logo-cors.svg'),
        'hero_image' => env('TENWEK_COHS_LANDING_HERO_IMAGE', 'banner-nursing.jpg'),
        'welcome_image' => env('TENWEK_COHS_LANDING_WELCOME_IMAGE', 'banner-nursing.jpg'),
        'map_embed_url' => env('TENWEK_COHS_MAP_EMBED_URL', 'https://maps.google.com/maps?q=-0.7792,35.3369&z=14&output=embed'),
        /**
         * /cohs/contact-us, aligned with https://tenwekhospitalcollege.ac.ke/cohs/contact-us/
         */
        'contact_page' => [
            'hero_kicker' => 'College of Health Sciences',
            'headline' => 'Contact',
            'headline_accent' => 'us',
            'lead' => 'Send a note, call the office, or write to us in Bomet. We are glad to help with admissions, programmes, and general enquiries.',
            'intro' => 'Use the form to send us a message or email us at :email. One of our colleagues will get back to you soon. Have a great day!',
            'email' => env('TENWEK_COHS_EMAIL', 'collegeofhealthsciences@tenwekhosp.org'),
            'office_title' => 'Tenwek Hospital College of Health Sciences',
            'phone_rows' => [
                [
                    'label' => 'Phone',
                    'numbers' => [
                        ['display' => '0736 568 177', 'tel' => '+254736568177'],
                        ['display' => '020 204 5542', 'tel' => '+254202045542'],
                        ['display' => '0728 091 900', 'tel' => '+254728091900'],
                    ],
                ],
            ],
            'address_lines' => ['P.O. Box 39-20400', 'Bomet, Kenya'],
        ],
        'landing_seo' => [
            'title' => null,
            'description' => null,
            'keywords' => null,
            'canonical' => null,
            'og_title' => null,
            'og_description' => null,
            'og_image' => null,
            'robots' => null,
        ],
        'top_bar' => [
            'email' => env('TENWEK_COHS_EMAIL', 'collegeofhealthsciences@tenwekhosp.org'),
            'call_prefix' => 'Call:',
            'call_display' => env('TENWEK_COHS_TOP_BAR_PHONE', env('TENWEK_PUBLIC_PHONE', '+254 700 000 000')),
            'call_tel' => env('TENWEK_COHS_TOP_BAR_TEL', preg_replace('/[^\d+]/', '', env('TENWEK_PUBLIC_PHONE', '+254700000000'))),
            'portal_label' => 'Downloads & forms',
            'portal_url' => null,
        ],
        /** Off-campus / online application (Application → Off-campus link in header). */
        'off_campus_application_url' => env('TENWEK_COHS_OFF_CAMPUS_APPLICATION_URL', 'http://197.138.207.16:3036/online-application/'),
        /**
         * Primary COHS header (mirrors legacy /cohs/ menu).
         * Items with mega_id render a dropdown; others use slug for /cohs/{slug}.
         */
        'main_nav' => [
            ['label' => 'About Us', 'slug' => 'about-us'],
            [
                'label' => 'Courses',
                'mega_id' => 'cohs-courses',
                'children' => [
                    ['label' => 'Diploma in Nursing', 'slug' => 'diploma-in-nursing'],
                    ['label' => 'Diploma in Clinical Medicine', 'slug' => 'diploma-in-clinical-medicine'],
                ],
            ],
            ['label' => 'Social Life', 'slug' => 'social-life'],
            ['label' => 'Facilities', 'slug' => 'facilities'],
            [
                'label' => 'Application',
                'mega_id' => 'cohs-application',
                'children' => [
                    ['label' => 'On-campus link', 'route' => 'cohs.on-campus-application'],
                    [
                        'label' => 'Off-campus link',
                        /** For nav “active” state on /cohs/offcampus-link; link still uses external URL. */
                        'slug' => 'offcampus-link',
                        'external_config_key' => 'off_campus_application_url',
                        'open_new_tab' => true,
                    ],
                ],
            ],
            ['label' => 'Application forms', 'slug' => 'application-forms'],
            ['label' => 'Contact Us', 'slug' => 'contact-us'],
        ],
        'hero' => [
            'badge' => 'Caring in Christ\'s name',
            'eyebrow' => 'Tenwek Hospital College · College of Health Sciences',
            'headline' => 'College of Health Sciences',
            'subhead' => 'With over 30 years\' experience training highly coveted nursing care practitioners, we inspire students to become servant leaders by modelling excellence in compassionate medical care.',
            'primary_cta' => ['label' => 'View programmes', 'route' => 'schools.pages.show', 'params' => ['school' => 'cohs', 'pageSlug' => 'diploma-in-nursing']],
            'secondary_cta' => ['label' => 'Apply & downloads', 'hash' => 'programmes'],
        ],
        'welcome' => [
            'kicker' => 'Welcome',
            'title' => 'Welcome to the College of Health Sciences',
            'lead' => 'Established in 1987 as the Tenwek School of Nursing, the College of Health Sciences has grown into a trusted name in training quality medical practitioners in Kenya.',
            'paragraphs' => [
                'The school graduated its first class of registered community health nurses in 1999 and has continued to prepare clinicians and nurses for decades.',
                'Today we offer rigorous diploma pathways anchored in clinical training at Tenwek Hospital, forming professionals who serve with skill and Christ-minded compassion.',
            ],
        ],
        'programmes_band' => [
            'kicker' => 'About us',
            'title' => 'Over 30 years\' experience training nursing care practitioners',
            'intro' => 'Explore our flagship diploma programmes, each designed for bedside excellence and community impact.',
            'items' => [
                [
                    'title' => 'Diploma in Community Health Nursing',
                    'summary' => 'Registered community health nursing formation with deep clinical immersion and servant-leadership values.',
                    'page_slug' => 'diploma-in-nursing',
                ],
                [
                    'title' => 'Diploma in Clinical Medicine & Surgery',
                    'summary' => 'Clinical medicine training that pairs strong academics with the pace and breadth of a mission referral hospital.',
                    'page_slug' => 'diploma-in-clinical-medicine',
                ],
            ],
        ],
        'testimonials' => [
            'kicker' => 'Student voices',
            'title' => 'We don\'t like to brag, but here is what students say',
            'items' => [
                [
                    'role' => 'Nursing student',
                    'quote' => 'Growing up, my health was not in good conditions and my parents and I had to make several trips to Tenwek Hospital. My love for the nursing career grew here and I knew I wanted to offer care to the sick in a compassionate manner similar to what I received. I am now in 1st year and truly the Tenwek College of Health Sciences is the place to be.',
                    'name' => 'Brillian Chebet',
                ],
                [
                    'role' => 'Nursing student',
                    'quote' => 'My parents are protective; I was born in Bomet and went to both primary and secondary school here. I knew I wanted a career in medicine. After KCSE I came to Tenwek College of Health Sciences. The years have flown by and I am now a finalist. I am a better person because of the training I have received here.',
                    'name' => 'Matthew Langat',
                ],
                [
                    'role' => 'Clinical medicine student',
                    'quote' => 'I have a unique story. I scored a B- in KCSE and later joined Moi University for environmental health. The course helped me realise my passion was in clinical medicine. With my family\'s support I pursued that calling. I am now in first year with no regrets. I cannot wait to begin my practice serving the sick.',
                    'name' => 'Joram',
                ],
            ],
        ],
        /**
         * /cohs/about-us, mirrored from https://tenwekhospitalcollege.ac.ke/cohs/about-us/
         */
        'about_us' => [
            'kicker' => 'About',
            /** Page hero (legacy H1). */
            'headline' => 'Christian Centre of Excellence',
            /** Shown beside “Our history” (file under public/). */
            'history_image' => env('TENWEK_COHS_ABOUT_HISTORY_IMAGE', 'banner-nursing.jpg'),
            'history_image_alt' => 'Nursing and clinical training at Tenwek Hospital College of Health Sciences',
            'history_heading' => 'Our history',
            'history_paragraphs' => [
                'Established in 1987 as the Tenwek School of Nursing, the Tenwek Hospital College School of Health Sciences has since grown to become a household name in the training of quality medical practitioners in Kenya. The school graduated its first class of Registered Community Health Nurses in 1999 and has continued to do so for the past 30 years.',
                'We have diversified our training modules and continue to adapt to changing environmental needs by enhancing our curriculum to incorporate both classroom learning and clinical rounds. At the moment, the school offers training in Diploma in Nursing and Diploma in Clinical Medicine. We are still working on diversifying our course portfolio and hope to begin Accident and Emergency Training, Anesthesia Training, Critical Care Training, and a Bachelor of Science in Nursing (in partnership with Kenya Highland University).',
            ],
            'vision' => [
                'title' => 'Vision',
                'text' => 'To seek to be a Christian Centre of Excellence for training by providing outstanding education.',
            ],
            'mission' => [
                'title' => 'Mission',
                'text' => 'To inspire students to become servant leaders by modelling excellence in compassionate medical care while empowering them to go and do likewise to those who are in need.',
            ],
            'motto' => [
                'title' => 'Motto',
                'text' => 'Caring in Christ\'s name',
            ],
            'board_section_heading' => 'Board and management team',
            'board_intro' => 'Tenwek Hospital College Health Sciences is a training subsidiary of Tenwek Hospital. The Board that governs the school is therefore the same Board that governs the hospital.',
            'board_heading' => 'Board',
            'board' => [
                [
                    'name' => 'Rev. Dr. Robert Langat',
                    'role' => 'Bishop AGC Kenya & Tenwek Hospital Board Chair',
                    'highlight' => true,
                ],
                [
                    'name' => 'Rev. John Kisotu',
                    'role' => 'Assistant Bishop AGC Kenya',
                ],
                [
                    'name' => 'Daniel Kirui',
                    'role' => 'Board member',
                ],
                [
                    'name' => 'Isaac Kitur',
                    'role' => 'Board member',
                ],
                [
                    'name' => 'Collins Cheruiyot',
                    'role' => 'Board member',
                ],
            ],
        ],
        /**
         * Programme detail pages: /cohs/diploma-in-nursing/, /cohs/diploma-in-clinical-medicine/, etc.
         */
        'programmes' => [
            'diploma_in_clinical_medicine' => [
                'kicker' => 'Diploma programme',
                'hero_image' => env('TENWEK_COHS_CLINICAL_HERO', 'banner-a.jpg'),
                'pillars' => [
                    ['label' => 'Accredited curriculum', 'description' => 'A structured three-year diploma aligned with regulatory expectations.'],
                    ['label' => 'Internship year', 'description' => 'One year of mandatory internship so graduates enter practice with confidence.'],
                    ['label' => 'Christ-like formation', 'description' => 'Training that pairs clinical skill with compassionate, servant-hearted care.'],
                ],
                'overview' => 'With over 30 years\' experience in training highly coveted nursing care practitioners, Tenwek Hospital College of Health Sciences has begun offering a Diploma in Clinical Medicine. This is an accredited three-year course with a one-year mandatory internship. Our aim is to offer quality training in a compassionate, Christ-like manner, producing clinicians ready to serve their communities and country.',
                'admissions' => [
                    'heading' => 'Admission requirements',
                    'lead' => 'Those interested in pursuing a Diploma in Clinical Medicine must meet the requirements below.',
                    'mean_grade' => 'KCSE mean grade of C Plain and above, or its equivalent.',
                    'subject_rules' => [
                        'C Plain in English or Kiswahili',
                        'C Plain in Biology or Biological Sciences',
                        'C– (minus) in Chemistry',
                        'C– (minus) in any of the following subjects: Mathematics, Physics, or Physical Sciences',
                    ],
                ],
            ],
            'diploma_in_nursing' => [
                'kicker' => 'Diploma programme',
                'pillars' => [
                    ['label' => 'Classroom learning', 'description' => 'Strong foundations in theory and professional practice.'],
                    ['label' => 'Clinical rounds', 'description' => 'Bedside training at Tenwek Hospital and partner sites.'],
                    ['label' => 'Community health', 'description' => 'Practical experience in communities around the college.'],
                ],
                'overview' => 'This is a three-and-a-half-year training programme that incorporates classroom education, clinical rounds, and community health practical learning in the communities around the school. We offer diversification in training and our students graduate as qualified midwives, general nursing practitioners, and community health nurses. This training equips them with the right skills to serve in diverse settings, making them among the best prepared in the industry.',
                'admissions' => [
                    'heading' => 'Admission requirements',
                    'lead' => 'Those interested in pursuing a Diploma in Nursing must meet the requirements below.',
                    'mean_grade' => 'KCSE mean grade of C Plain and above, or its equivalent.',
                    'subject_rules' => [
                        'C Plain in English or Kiswahili',
                        'C Plain in Biology or Biological Sciences',
                        'C– (minus) in any of the following subjects: Mathematics, Physics, Chemistry, or Physical Sciences',
                    ],
                ],
            ],
        ],
        /**
         * /cohs/social-life: https://tenwekhospitalcollege.ac.ke/cohs/social-life/
         */
        'social_life' => [
            'kicker' => 'Student life',
            'headline_before' => 'Social',
            'headline_emphasis' => 'life',
            'pull_quote' => 'No man is an island!',
            'paragraphs' => [
                'The school provides facilities and spaces for students to interact, grow, have fun, and most of all, to grow in relationship with Christ. Our students have access to table tennis and football, Christian Union challenges, and outdoor trips. They also volunteer in communities surrounding Tenwek.',
                'You can visit us to get a feel of what studying at the Tenwek College of Health Sciences feels like!',
            ],
            'highlights' => [
                [
                    'title' => 'Sports & games',
                    'description' => 'Table tennis, football, and room to play and recharge between clinical days.',
                ],
                [
                    'title' => 'Christian Union',
                    'description' => 'Fellowship, challenges, and shared faith that anchors student life.',
                ],
                [
                    'title' => 'Outdoor trips',
                    'description' => 'Excursions that build camaraderie and widen perspective beyond the classroom.',
                ],
                [
                    'title' => 'Community volunteering',
                    'description' => 'Serving neighbourhoods around Tenwek as part of holistic formation.',
                ],
            ],
            'hero_image' => env('TENWEK_COHS_SOCIAL_HERO', 'banner-nursing.jpg'),
        ],
        /**
         * /cohs/facilities: https://tenwekhospitalcollege.ac.ke/cohs/facilities/
         */
        'facilities' => [
            'kicker' => 'Campus',
            'headline_before' => 'Our',
            'headline_emphasis' => 'facilities',
            'paragraphs' => [
                'Our College of Health Sciences has modern buildings and infrastructure that give students a comfortable space and serene environment for learning.',
                'We provide accommodation and meals for our students as part of the school fees arrangement. Our library is equipped with at least ten desktop computers and about 4,000 books to support research and revision.',
                'The school has a skills laboratory laid out like a hospital ward, fitted with equipment for practical learning and demonstration.',
            ],
            'highlights' => [
                [
                    'title' => 'Campus & accommodation',
                    'description' => 'Modern buildings and a calm setting for study, plus residential care and meals alongside your fees.',
                ],
                [
                    'title' => 'Library & ICT',
                    'description' => 'A well-stocked library with desktop workstations for research and quiet revision.',
                ],
                [
                    'title' => 'Skills laboratory',
                    'description' => 'Ward-style layout and equipment so students practise procedures in a realistic clinical space.',
                ],
            ],
            'hero_image' => env('TENWEK_COHS_FACILITIES_HERO', 'banner-nursing.jpg'),
        ],
        'contact' => [
            'kicker' => 'Contact us',
            'location_lines' => [
                'Silibwet Township, Bomet Central, Kenya',
                'P.O. Box 39–20400, Bomet, Kenya',
                'Tenwek Hospital College · College of Health Sciences',
            ],
            'phones' => [
                env('TENWEK_PUBLIC_PHONE', '+254 700 000 000'),
            ],
            'email' => env('TENWEK_COHS_EMAIL', 'info@tenwekhospitalcollege.ac.ke'),
            'office_hours_lines' => [],
            'social_links' => [],
        ],
    ],

    'footer' => [
        'accreditation' => array_filter([
            env('TENWEK_ACCREDITATION_1', 'Tenwek Hospital College is part of the Tenwek Hospital academic and ministry ecosystem.'),
            env('TENWEK_ACCREDITATION_2'),
        ]),
        'social_labels' => [
            'facebook' => 'Facebook',
            'linkedin' => 'LinkedIn',
            'youtube' => 'YouTube',
            'x' => 'X',
        ],
    ],

    'navigation' => [
        'primary' => [
            /** Sitemap mirrored from https://tenwekhospitalcollege.ac.ke/ subsites /soc/ and /cohs/ */
            ['label' => 'Schools', 'groups' => [
                [
                    'heading' => 'School of Chaplaincy',
                    'children' => [
                        ['label' => 'School home', 'route' => 'schools.show', 'params' => ['school' => 'soc']],
                        ['label' => 'About us', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'about-us']],
                        ['label' => 'Register', 'route' => 'soc.register', 'params' => []],
                        ['label' => 'Academic programmes', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'academic-programmes']],
                        ['label' => 'Admissions', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'admissions']],
                        ['label' => 'Board & management team', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'board-and-management-team']],
                        ['label' => 'FAQs', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'faqs']],
                        ['label' => 'Fees', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'fee']],
                        ['label' => 'Gallery', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'gallery']],
                        ['label' => 'Message from the principal', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'message-from-the-principal']],
                        ['label' => 'Our history', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'our-history']],
                        ['label' => 'Strategic partners', 'route' => 'schools.pages.show', 'params' => ['school' => 'soc', 'pageSlug' => 'strategic-partners']],
                    ],
                ],
                [
                    'heading' => 'College of Health Sciences',
                    'children' => [
                        ['label' => 'College home', 'route' => 'schools.show', 'params' => ['school' => 'cohs']],
                        ['label' => 'About us', 'route' => 'schools.pages.show', 'params' => ['school' => 'cohs', 'pageSlug' => 'about-us']],
                        ['label' => 'Diploma in Nursing', 'route' => 'schools.pages.show', 'params' => ['school' => 'cohs', 'pageSlug' => 'diploma-in-nursing']],
                        ['label' => 'Diploma in Clinical Medicine', 'route' => 'schools.pages.show', 'params' => ['school' => 'cohs', 'pageSlug' => 'diploma-in-clinical-medicine']],
                        ['label' => 'Social life', 'route' => 'schools.pages.show', 'params' => ['school' => 'cohs', 'pageSlug' => 'social-life']],
                        ['label' => 'Facilities', 'route' => 'schools.pages.show', 'params' => ['school' => 'cohs', 'pageSlug' => 'facilities']],
                        ['label' => 'On-campus application', 'route' => 'cohs.on-campus-application', 'params' => []],
                        ['label' => 'Off-campus application', 'route' => 'schools.pages.show', 'params' => ['school' => 'cohs', 'pageSlug' => 'offcampus-link']],
                        ['label' => 'Application forms', 'route' => 'schools.pages.show', 'params' => ['school' => 'cohs', 'pageSlug' => 'application-forms']],
                        ['label' => 'Downloads & forms', 'route' => 'downloads.index', 'params' => ['school' => 'cohs']],
                        ['label' => 'Contact us', 'route' => 'schools.pages.show', 'params' => ['school' => 'cohs', 'pageSlug' => 'contact-us']],
                    ],
                ],
            ]],
            ['label' => 'Admissions', 'route' => 'pages.show', 'params' => ['slug' => 'admissions']],
            ['label' => 'Academics', 'route' => 'pages.show', 'params' => ['slug' => 'academics']],
            ['label' => 'Programs', 'route' => 'pages.show', 'params' => ['slug' => 'programs']],
            ['label' => 'Student Life', 'route' => 'pages.show', 'params' => ['slug' => 'student-life']],
            ['label' => 'Clinical Training', 'route' => 'pages.show', 'params' => ['slug' => 'clinical-training']],
            ['label' => 'Downloads', 'route' => 'downloads.index'],
            ['label' => 'News & Events', 'route' => 'news.index'],
            ['label' => 'About', 'children' => [
                ['label' => 'About the Institution', 'route' => 'pages.show', 'params' => ['slug' => 'about']],
                ['label' => 'Governance', 'route' => 'pages.show', 'params' => ['slug' => 'governance']],
                ['label' => 'Contact', 'route' => 'contact.show'],
            ]],
        ],
        'audiences' => [
            ['label' => 'Prospective students', 'route' => 'pages.show', 'params' => ['slug' => 'admissions']],
            ['label' => 'Parents & guardians', 'route' => 'pages.show', 'params' => ['slug' => 'parents']],
            ['label' => 'Faculty & staff', 'route' => 'pages.show', 'params' => ['slug' => 'faculty-resources']],
            ['label' => 'Partners', 'route' => 'pages.show', 'params' => ['slug' => 'partners']],
            ['label' => 'International applicants', 'route' => 'pages.show', 'params' => ['slug' => 'international']],
        ],
    ],
];
