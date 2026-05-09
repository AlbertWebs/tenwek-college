<x-layouts.admin header="School of Chaplaincy — CMS" title="SOC CMS | {{ config('tenwek.name') }}">
    <div class="admin-page-wide">
        <p class="text-sm leading-relaxed text-thc-text/85">
            Manage the public <a href="{{ route('schools.show', $soc) }}" class="admin-link">/soc</a> experience. Structured forms update live content; JSON modules are for advanced blocks (fees, FAQs, programmes list, etc.).
        </p>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('admin.soc.hero.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Landing</p>
                <p class="admin-dash-tile-title">Hero &amp; images</p>
                <p class="admin-dash-tile-desc">Headline, CTAs, hero/about/logo uploads</p>
            </a>
            <a href="{{ route('admin.soc.about.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Landing</p>
                <p class="admin-dash-tile-title">About</p>
                <p class="admin-dash-tile-desc">Karibu block copy</p>
            </a>
            <a href="{{ route('admin.soc.mission-vision.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Landing</p>
                <p class="admin-dash-tile-title">Mission &amp; vision</p>
            </a>
            <a href="{{ route('admin.soc.motto.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Landing</p>
                <p class="admin-dash-tile-title">Motto</p>
            </a>
            <a href="{{ route('admin.soc.contact.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Landing</p>
                <p class="admin-dash-tile-title">Contact strip</p>
                <p class="admin-dash-tile-desc">Phones, email, map embed</p>
            </a>
            <a href="{{ route('admin.soc.seo.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">SEO</p>
                <p class="admin-dash-tile-title">Landing meta</p>
                <p class="admin-dash-tile-desc">/soc home only</p>
            </a>
            <a href="{{ route('admin.soc.top-bar.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Chrome</p>
                <p class="admin-dash-tile-title">Utility top bar</p>
            </a>
            <a href="{{ route('admin.soc.testimonials.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">CRUD</p>
                <p class="admin-dash-tile-title">Testimonials</p>
                <p class="admin-dash-tile-desc">{{ number_format($stats['testimonials']) }} saved</p>
            </a>
            <a href="{{ route('admin.soc.navigation.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Chrome</p>
                <p class="admin-dash-tile-title">Navigation</p>
                <p class="admin-dash-tile-desc">Custom menu (or empty = config)</p>
            </a>
            <a href="{{ route('admin.soc.team.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">People</p>
                <p class="admin-dash-tile-title">Board &amp; management</p>
                <p class="admin-dash-tile-desc">Overrides config cards when set</p>
            </a>
            <a href="{{ route('admin.soc.media.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Media</p>
                <p class="admin-dash-tile-title">Library</p>
                <p class="admin-dash-tile-desc">{{ number_format($stats['media']) }} files</p>
            </a>
            <a href="{{ route('admin.soc.pages.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Pages</p>
                <p class="admin-dash-tile-title">SOC pages &amp; SEO</p>
                <p class="admin-dash-tile-desc">{{ number_format($stats['pages']) }} pages</p>
            </a>
            <a href="{{ route('admin.soc.news.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">News</p>
                <p class="admin-dash-tile-title">News &amp; events</p>
                <p class="admin-dash-tile-desc">{{ number_format($stats['news']) }} posts</p>
            </a>
            <a href="{{ route('admin.soc.programme-groups.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Programmes</p>
                <p class="admin-dash-tile-title">Academic programmes</p>
                <p class="admin-dash-tile-desc">{{ number_format($stats['programme_groups']) }} groups · replaces config when set</p>
            </a>
            <a href="{{ route('admin.soc.submissions.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Forms</p>
                <p class="admin-dash-tile-title">Submissions inbox</p>
                <p class="admin-dash-tile-desc">Contact &amp; registration</p>
            </a>
        </div>

        <div class="admin-card p-6">
            <p class="text-sm font-semibold text-thc-navy">Advanced JSON sections</p>
            <p class="mt-1 text-sm text-thc-text/80">Merge overrides with <span class="admin-code">config/tenwek.php</span> defaults. Use valid JSON objects.</p>
            <ul class="mt-4 flex flex-wrap gap-2 text-sm">
                @foreach (['our_history', 'message_from_principal', 'strategic_partners', 'academic_programmes', 'fee', 'gallery', 'faqs', 'admissions', 'board_and_management', 'testimonials'] as $key)
                    <li>
                        <a href="{{ route('admin.soc.json.edit', $key) }}" class="inline-flex rounded-full border border-thc-navy/12 bg-thc-navy/[0.03] px-3 py-1.5 font-medium text-thc-navy transition hover:border-thc-royal/35 hover:bg-thc-royal/5">{{ str_replace('_', ' ', $key) }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="admin-card p-6">
            <div class="grid gap-4 text-sm text-thc-text/80 sm:grid-cols-3">
                <p><span class="font-semibold text-thc-navy">Form submissions (7d)</span><br>{{ number_format($stats['submissions_7d']) }}</p>
                <p><span class="font-semibold text-thc-navy">Downloads</span><br>Use <a href="{{ route('admin.downloads.index', ['school' => 'soc']) }}" class="admin-link">Downloads admin</a> (school filter).</p>
                <p><span class="font-semibold text-thc-navy">Register form</span><br>Public wizard; submissions in Form submissions / audits.</p>
            </div>
        </div>
    </div>
</x-layouts.admin>
