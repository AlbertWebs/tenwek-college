<x-layouts.admin header="College of Health Sciences — CMS" title="COHS CMS | {{ config('tenwek.name') }}">
    <div class="admin-page-wide">
        <p class="text-sm leading-relaxed text-thc-text/85">
            Manage the public <a href="{{ route('schools.show', $cohs) }}" class="admin-link">/cohs</a> experience. Structured forms update live content; JSON is available for full programme blocks.
        </p>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('admin.cohs.hero.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Landing</p>
                <p class="admin-dash-tile-title">Hero &amp; images</p>
                <p class="admin-dash-tile-desc">Headline, CTAs, hero, welcome column, logo</p>
            </a>
            <a href="{{ route('admin.cohs.welcome.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Landing</p>
                <p class="admin-dash-tile-title">Welcome</p>
                <p class="admin-dash-tile-desc">Intro band under the hero</p>
            </a>
            <a href="{{ route('admin.cohs.programmes-band.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Landing</p>
                <p class="admin-dash-tile-title">Programmes band</p>
                <p class="admin-dash-tile-desc">Kicker, title, intro above cards</p>
            </a>
            <a href="{{ route('admin.cohs.testimonials-band.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Landing</p>
                <p class="admin-dash-tile-title">Testimonials heading</p>
                <p class="admin-dash-tile-desc">Quote cards: Testimonials CRUD</p>
            </a>
            <a href="{{ route('admin.cohs.about-us.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Inner</p>
                <p class="admin-dash-tile-title">About us page</p>
                <p class="admin-dash-tile-desc">History, vision, mission, board</p>
            </a>
            <a href="{{ route('admin.cohs.social-life.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Inner</p>
                <p class="admin-dash-tile-title">Social life</p>
            </a>
            <a href="{{ route('admin.cohs.facilities.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Inner</p>
                <p class="admin-dash-tile-title">Facilities</p>
            </a>
            <a href="{{ route('admin.cohs.contact.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Inner</p>
                <p class="admin-dash-tile-title">Contact page</p>
                <p class="admin-dash-tile-desc">Phones, email, address, map</p>
            </a>
            <a href="{{ route('admin.cohs.seo.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">SEO</p>
                <p class="admin-dash-tile-title">Landing meta</p>
                <p class="admin-dash-tile-desc">/cohs home only</p>
            </a>
            <a href="{{ route('admin.cohs.top-bar.edit') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Chrome</p>
                <p class="admin-dash-tile-title">Utility top bar</p>
                <p class="admin-dash-tile-desc">Off-campus application URL</p>
            </a>
            <a href="{{ route('admin.cohs.testimonials.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">CRUD</p>
                <p class="admin-dash-tile-title">Testimonials</p>
                <p class="admin-dash-tile-desc">{{ number_format($stats['testimonials']) }} saved</p>
            </a>
            <a href="{{ route('admin.cohs.navigation.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Chrome</p>
                <p class="admin-dash-tile-title">Navigation</p>
                <p class="admin-dash-tile-desc">Custom menu (empty = config)</p>
            </a>
            <a href="{{ route('admin.cohs.media.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Media</p>
                <p class="admin-dash-tile-title">Library</p>
                <p class="admin-dash-tile-desc">{{ number_format($stats['media']) }} files</p>
            </a>
            <a href="{{ route('admin.cohs.pages.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Pages</p>
                <p class="admin-dash-tile-title">COHS pages &amp; SEO</p>
                <p class="admin-dash-tile-desc">{{ number_format($stats['pages']) }} pages</p>
            </a>
            <a href="{{ route('admin.cohs.news.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">News</p>
                <p class="admin-dash-tile-title">News &amp; events</p>
                <p class="admin-dash-tile-desc">{{ number_format($stats['news']) }} posts</p>
            </a>
            <a href="{{ route('admin.cohs.submissions.index') }}" class="admin-dash-tile">
                <p class="admin-dash-tile-kicker">Forms</p>
                <p class="admin-dash-tile-title">Submissions inbox</p>
                <p class="admin-dash-tile-desc">Contact &amp; on-campus application</p>
            </a>
        </div>

        <div class="admin-card p-6">
            <p class="text-sm font-semibold text-thc-navy">Advanced JSON</p>
            <p class="mt-1 text-sm text-thc-text/80">Merge overrides with <span class="admin-code">config/tenwek.php</span> under <span class="admin-code">cohs_landing.programmes</span>.</p>
            <ul class="mt-4 flex flex-wrap gap-2 text-sm">
                <li>
                    <a href="{{ route('admin.cohs.json.edit', 'programmes') }}" class="inline-flex rounded-full border border-thc-navy/12 bg-thc-navy/[0.03] px-3 py-1.5 font-medium text-thc-navy transition hover:border-thc-royal/35 hover:bg-thc-royal/5">programmes</a>
                </li>
            </ul>
        </div>

        <div class="admin-card p-6">
            <div class="grid gap-4 text-sm text-thc-text/80 sm:grid-cols-3">
                <p><span class="font-semibold text-thc-navy">Form submissions (7d)</span><br>{{ number_format($stats['submissions_7d']) }}</p>
                <p><span class="font-semibold text-thc-navy">Downloads</span><br>Use <a href="{{ route('admin.downloads.index', ['school' => 'cohs']) }}" class="admin-link">Downloads admin</a> (application PDFs, etc.).</p>
                <p><span class="font-semibold text-thc-navy">On-campus application</span><br>Public wizard; submissions appear in Form submissions.</p>
            </div>
        </div>
    </div>
</x-layouts.admin>
