@php
    $socSchool = \App\Models\School::query()->where('slug', 'soc')->where('is_active', true)->first();
    $cohsSchool = \App\Models\School::query()->where('slug', 'cohs')->where('is_active', true)->first();
    $socName = $socSchool?->name ?? __('School of Chaplaincy');
    $cohsName = $cohsSchool?->name ?? __('College of Health Sciences');
    $socTagline = $socSchool?->tagline ?? __('Competency-based chaplaincy formation beside a busy mission hospital.');
    $cohsTagline = $cohsSchool?->tagline ?? __('Clinical programmes that grow skilled nurses and clinicians for Kenya and beyond.');
    $socExcerpt = $socSchool?->excerpt;
    $cohsExcerpt = $cohsSchool?->excerpt;
    $hasExtraBody = filled(trim(strip_tags((string) $page->body)));
    $credibility = config('tenwek.hero.credibility', '');
@endphp

<div class="pb-20 lg:pb-28">
    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-thc-navy/8 bg-gradient-to-b from-thc-navy/[0.07] via-white to-thc-teal/[0.05]">
        <div class="pointer-events-none absolute -right-24 top-0 h-72 w-72 rounded-full bg-thc-royal/[0.06] blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-16 bottom-0 h-56 w-56 rounded-full bg-thc-teal/[0.08] blur-3xl" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 sm:py-20 lg:px-8 lg:py-24">
            @if(filled($credibility))
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-thc-text/55">{{ $credibility }}</p>
            @endif
            <p class="thc-kicker mt-5 text-thc-maroon sm:mt-6">{{ __('Our college') }}</p>
            <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl lg:text-[2.85rem] lg:leading-[1.12]">
                {{ $page->title }}
            </h1>
            @if($page->excerpt)
                <p class="mx-auto mt-7 max-w-2xl text-lg leading-relaxed text-thc-text/90 sm:text-xl">
                    {{ $page->excerpt }}
                </p>
            @endif
        </div>
    </section>

    {{-- Opening + two schools --}}
    <section class="thc-section pb-8 lg:pb-12">
        <div class="mx-auto max-w-6xl">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl lg:text-[2rem]">{{ __('One college beside one hospital') }}</h2>
                <p class="mt-5 text-base leading-relaxed text-thc-text/88 sm:text-lg">
                    {{ __('Tenwek Hospital College is the academic arm of the Tenwek ecosystem: the School of Chaplaincy and the College of Health Sciences share a campus culture with Tenwek Hospital in Bomet, so spiritual care and clinical excellence are never far apart.') }}
                </p>
            </div>

            <div class="mt-14 grid gap-8 lg:grid-cols-2 lg:gap-10">
                @if($socSchool)
                    <a
                        href="{{ route('schools.show', $socSchool) }}"
                        class="group relative flex min-h-[280px] flex-col overflow-hidden rounded-2xl border border-thc-navy/10 bg-white p-8 shadow-sm ring-1 ring-thc-navy/[0.04] transition duration-300 hover:border-thc-maroon/30 hover:shadow-lg hover:ring-thc-maroon/10"
                    >
                        <span class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-thc-maroon via-thc-maroon/80 to-thc-navy/60" aria-hidden="true"></span>
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-thc-maroon">{{ __('Spiritual care formation') }}</p>
                        <h3 class="mt-4 font-serif text-2xl font-semibold text-thc-navy transition group-hover:text-thc-royal lg:text-[1.65rem]">{{ $socName }}</h3>
                        <p class="mt-3 text-sm font-medium leading-relaxed text-thc-navy/80">{{ $socTagline }}</p>
                        @if(filled($socExcerpt))
                            <p class="mt-4 flex-1 text-sm leading-relaxed text-thc-text/82">{{ \Illuminate\Support\Str::limit(strip_tags((string) $socExcerpt), 220) }}</p>
                        @else
                            <p class="mt-4 flex-1 text-sm leading-relaxed text-thc-text/82">
                                {{ __('Certificate and diploma pathways in chaplaincy, examined through CDACC, with emphasis on healthcare, education, military, and community contexts.') }}
                            </p>
                        @endif
                        <span class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-thc-royal">
                            {{ __('Discover programmes & admissions') }}
                            <svg class="h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </a>
                @endif

                @if($cohsSchool)
                    <a
                        href="{{ route('schools.show', $cohsSchool) }}"
                        class="group relative flex min-h-[280px] flex-col overflow-hidden rounded-2xl border border-thc-navy/10 bg-white p-8 shadow-sm ring-1 ring-thc-navy/[0.04] transition duration-300 hover:border-thc-royal/35 hover:shadow-lg hover:ring-thc-royal/10"
                    >
                        <span class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-thc-royal via-thc-royal/85 to-thc-navy/50" aria-hidden="true"></span>
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-thc-royal">{{ __('Clinical education') }}</p>
                        <h3 class="mt-4 font-serif text-2xl font-semibold text-thc-navy transition group-hover:text-thc-royal lg:text-[1.65rem]">{{ $cohsName }}</h3>
                        <p class="mt-3 text-sm font-medium leading-relaxed text-thc-navy/80">{{ $cohsTagline }}</p>
                        @if(filled($cohsExcerpt))
                            <p class="mt-4 flex-1 text-sm leading-relaxed text-thc-text/82">{{ \Illuminate\Support\Str::limit(strip_tags((string) $cohsExcerpt), 220) }}</p>
                        @else
                            <p class="mt-4 flex-1 text-sm leading-relaxed text-thc-text/82">
                                {{ __('Diploma programmes in nursing and clinical medicine, taught with the discipline of a Level 5 teaching hospital and a long heritage of Christ-minded service.') }}
                            </p>
                        @endif
                        <span class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-thc-royal">
                            {{ __('Explore courses & apply') }}
                            <svg class="h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Insight: narrative + sticky summary --}}
    <section class="relative border-t border-thc-navy/8 bg-gradient-to-b from-thc-navy/[0.04] via-white to-thc-surface">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-thc-teal/30 to-transparent" aria-hidden="true"></div>
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
            <div class="lg:flex lg:items-start lg:gap-14 xl:gap-20">
                <div class="lg:w-[min(52%,42rem)] lg:shrink-0">
                    <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-thc-teal">{{ __('Our story') }}</p>
                    <h2 class="mt-3 font-serif text-3xl font-semibold tracking-tight text-thc-navy sm:text-[2rem] lg:text-[2.25rem] lg:leading-tight">
                        {{ __('Why both schools belong together') }}
                    </h2>
                    <div class="about-prose mt-10 space-y-7 text-[17px] leading-[1.75] text-thc-text/90">
                        <p class="first:font-medium first:text-thc-navy/95">
                            {{ __('Healing is more than technique. Patients and families need skilled hands and attentive hearts. Tenwek has long tried to hold those together, and the college exists so the next generation can do the same in hospitals, schools, barracks, and communities.') }}
                        </p>
                        <p>
                            {{ __('The School of Chaplaincy equips people to walk with others through fear, grief, and hope, especially where life is fragile. The College of Health Sciences equips nurses and clinical officers to lead with competence and compassion on busy wards. Different callings; one ethos.') }}
                        </p>
                        <p class="border-l-2 border-thc-royal/40 pl-6 text-thc-text/88">
                            {{ __('When you study here, you are not choosing between spiritual and medical formation. You are choosing a place that believes both matter, and that neither should be an afterthought.') }}
                        </p>
                    </div>
                </div>
                <aside class="mt-14 lg:sticky lg:top-28 lg:mt-2 lg:w-[min(44%,24rem)] lg:flex-1">
                    <div class="relative overflow-hidden rounded-2xl border border-thc-navy/10 bg-white shadow-md shadow-thc-navy/[0.06] ring-1 ring-thc-navy/[0.04]">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-thc-maroon via-thc-teal to-thc-royal" aria-hidden="true"></div>
                        <div class="p-8 sm:p-9">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-thc-teal">{{ __('At a glance') }}</p>
                            <p class="mt-2 text-xs leading-relaxed text-thc-text/65">{{ __('How the pieces of Tenwek College fit the hospital we serve beside.') }}</p>
                            <ul class="mt-8 divide-y divide-thc-navy/8">
                                <li class="flex gap-4 pb-6">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-thc-maroon/12 text-xs font-bold text-thc-maroon" aria-hidden="true">1</span>
                                    <div class="min-w-0 text-sm leading-relaxed text-thc-text/88">
                                        <span class="font-semibold text-thc-navy">{{ __('School of Chaplaincy') }}</span>
                                        <span class="text-thc-text/40" aria-hidden="true"> · </span>
                                        {{ __('CDACC competency-based chaplaincy, evangelical partnerships, and public-facing ministry.') }}
                                    </div>
                                </li>
                                <li class="flex gap-4 py-6">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-thc-royal/12 text-xs font-bold text-thc-royal" aria-hidden="true">2</span>
                                    <div class="min-w-0 text-sm leading-relaxed text-thc-text/88">
                                        <span class="font-semibold text-thc-navy">{{ __('College of Health Sciences') }}</span>
                                        <span class="text-thc-text/40" aria-hidden="true"> · </span>
                                        {{ __('Nursing and clinical medicine diplomas with daily immersion in a Level 5 teaching hospital.') }}
                                    </div>
                                </li>
                                <li class="flex gap-4 pt-6">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-thc-teal/15 text-xs font-bold text-thc-teal" aria-hidden="true">3</span>
                                    <div class="min-w-0 text-sm leading-relaxed text-thc-text/88">
                                        <span class="font-semibold text-thc-navy">{{ __('Tenwek Hospital') }}</span>
                                        <span class="text-thc-text/40" aria-hidden="true"> · </span>
                                        {{ __('Where lectures meet living patients, families, and the pace of real Kenyan healthcare.') }}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- Distinctives --}}
    <section class="thc-section border-t border-thc-navy/8 bg-white">
        <div class="mx-auto max-w-6xl">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-thc-maroon">{{ __('Formation') }}</p>
                <h2 class="mt-3 font-serif text-3xl font-semibold text-thc-navy sm:text-[2rem]">{{ __('What you can expect here') }}</h2>
                <p class="mt-4 text-base leading-relaxed text-thc-text/78">{{ __('Habits of mind and heart that outlast any single semester.') }}</p>
            </div>
            <div class="mt-16 grid gap-5 sm:grid-cols-3 sm:gap-6 lg:mt-20">
                <article class="group relative flex flex-col rounded-2xl border border-thc-navy/10 bg-gradient-to-b from-white to-thc-navy/[0.02] p-8 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-thc-teal/30 hover:shadow-md">
                    <span class="font-mono text-2xl font-semibold tabular-nums text-thc-teal/40 transition group-hover:text-thc-teal/70" aria-hidden="true">01</span>
                    <p class="mt-4 text-[11px] font-bold uppercase tracking-[0.18em] text-thc-teal">{{ __('Integration') }}</p>
                    <h3 class="mt-2 font-serif text-lg font-semibold text-thc-navy">{{ __('Character and craft') }}</h3>
                    <p class="mt-4 flex-1 text-sm leading-relaxed text-thc-text/85">
                        {{ __('We care how you think, speak, and show up. Chaplaincy students engage Scripture and ethics alongside pastoral skills; health sciences students learn protocols beside mentors who model mercy under pressure.') }}
                    </p>
                </article>
                <article class="group relative flex flex-col rounded-2xl border border-thc-navy/10 bg-gradient-to-b from-white to-thc-navy/[0.02] p-8 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-thc-teal/30 hover:shadow-md sm:mt-0">
                    <span class="font-mono text-2xl font-semibold tabular-nums text-thc-teal/40 transition group-hover:text-thc-teal/70" aria-hidden="true">02</span>
                    <p class="mt-4 text-[11px] font-bold uppercase tracking-[0.18em] text-thc-teal">{{ __('Place') }}</p>
                    <h3 class="mt-2 font-serif text-lg font-semibold text-thc-navy">{{ __('A serious teaching hospital') }}</h3>
                    <p class="mt-4 flex-1 text-sm leading-relaxed text-thc-text/85">
                        {{ __('Complex cases, busy teams, and rural Kenya’s realities shape your education. You graduate with stories from real rooms, not only from textbooks.') }}
                    </p>
                </article>
                <article class="group relative flex flex-col rounded-2xl border border-thc-navy/10 bg-gradient-to-b from-white to-thc-navy/[0.02] p-8 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-thc-teal/30 hover:shadow-md">
                    <span class="font-mono text-2xl font-semibold tabular-nums text-thc-teal/40 transition group-hover:text-thc-teal/70" aria-hidden="true">03</span>
                    <p class="mt-4 text-[11px] font-bold uppercase tracking-[0.18em] text-thc-teal">{{ __('Calling') }}</p>
                    <h3 class="mt-2 font-serif text-lg font-semibold text-thc-navy">{{ __('Service beyond self') }}</h3>
                    <p class="mt-4 flex-1 text-sm leading-relaxed text-thc-text/85">
                        {{ __('Whether you preach, pray, or place an IV, the aim is faithful service. We invite you to grow in skill for the sake of others, not for status alone.') }}
                    </p>
                </article>
            </div>
        </div>
    </section>

    {{-- Quote --}}
    <section class="bg-thc-surface px-4 py-14 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
        <figure class="relative mx-auto max-w-4xl overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-thc-navy via-thc-navy to-thc-navy/[0.92] px-8 py-14 text-white shadow-xl sm:px-16 sm:py-16">
            <div class="pointer-events-none absolute -left-8 -top-12 h-48 w-48 rounded-full bg-thc-royal/20 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-16 -right-8 h-56 w-56 rounded-full bg-thc-teal/25 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute inset-x-10 top-0 h-px bg-gradient-to-r from-transparent via-white/25 to-transparent" aria-hidden="true"></div>
            <div class="relative text-center font-serif text-6xl font-bold leading-none text-white/[0.12] sm:text-7xl" aria-hidden="true">&ldquo;</div>
            <blockquote class="relative -mt-4 text-center font-serif text-xl font-medium leading-snug text-white sm:text-2xl sm:leading-snug">
                {{ __('We treat, Jesus heals: that conviction runs through our chaplaincy and our clinical programmes alike. Education here is preparation for service, not merely a credential.') }}
            </blockquote>
            <figcaption class="relative mt-10 text-center">
                <span class="text-sm font-semibold tracking-wide text-white/90">{{ __('Tenwek Hospital College') }}</span>
                <span class="mt-2 block text-xs text-white/55">{{ config('tenwek.tagline') }}</span>
            </figcaption>
        </figure>
    </section>

    @if($hasExtraBody)
        <section class="border-t border-thc-navy/8 bg-white py-16 sm:py-20">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <h2 class="font-serif text-xl font-semibold text-thc-navy">{{ __('More information') }}</h2>
                <p class="mt-2 text-sm text-thc-text/70">{{ __('Updates and notes from our editorial team.') }}</p>
                <div class="prose-about mt-10 space-y-4 text-base leading-relaxed text-thc-text [&_a]:font-medium [&_a]:text-thc-royal [&_h2]:mt-10 [&_h2]:font-serif [&_h2]:text-xl [&_h2]:text-thc-navy [&_h3]:mt-8 [&_h3]:font-semibold [&_h3]:text-thc-navy [&_ul]:list-disc [&_ul]:pl-6">
                    {!! $page->body !!}
                </div>
            </div>
        </section>
    @endif

    {{-- CTA + bridge to site footer --}}
    <section class="border-t border-thc-navy/8 bg-gradient-to-b from-thc-navy/[0.03] to-thc-navy/[0.06] px-4 pb-16 pt-14 sm:px-6 sm:pb-20 sm:pt-16 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="font-serif text-2xl font-semibold text-thc-navy sm:text-[1.65rem]">{{ __('Choose your path, then take the next step') }}</h2>
            <p class="mx-auto mt-5 max-w-lg text-sm leading-relaxed text-thc-text/82">
                {{ __('Each school publishes programmes, fees, and how to apply. Browse downloads for forms, read college news, or write to us. We will help you find the right door.') }}
            </p>
            <div class="mt-10 flex flex-col items-stretch gap-3 sm:flex-row sm:flex-wrap sm:justify-center sm:gap-3">
                @if($socSchool)
                    <a href="{{ route('schools.show', $socSchool) }}" class="thc-btn-ghost justify-center sm:min-w-[10rem]">{{ $socName }}</a>
                @endif
                @if($cohsSchool)
                    <a href="{{ route('schools.show', $cohsSchool) }}" class="thc-btn-ghost justify-center sm:min-w-[10rem]">{{ $cohsName }}</a>
                @endif
                <a href="{{ route('pages.show', ['slug' => 'admissions']) }}" class="thc-btn-ghost justify-center sm:min-w-[10rem]">{{ __('Admissions') }}</a>
                <a href="{{ route('contact.show') }}" class="thc-btn-primary justify-center sm:min-w-[12rem]">{{ __('Contact us') }}</a>
            </div>
            <p class="mt-8 text-xs text-thc-text/55">
                <a href="{{ route('downloads.index') }}" class="font-medium text-thc-royal hover:underline">{{ __('Downloads & forms') }}</a>
                <span class="mx-2 text-thc-text/35" aria-hidden="true">·</span>
                <a href="{{ route('news.index') }}" class="font-medium text-thc-royal hover:underline">{{ __('News & events') }}</a>
                <span class="mx-2 text-thc-text/35" aria-hidden="true">·</span>
                <a href="{{ route('search') }}" class="font-medium text-thc-royal hover:underline">{{ __('Search') }}</a>
            </p>
        </div>
        <div class="mx-auto mt-14 max-w-2xl border-t border-thc-navy/10 pt-10 text-center">
            <p class="text-sm font-medium text-thc-navy">{{ config('tenwek.name') }}</p>
            <p class="mx-auto mt-2 max-w-md text-xs leading-relaxed text-thc-text/65">
                {{ config('tenwek.address.locality') }}, {{ config('tenwek.address.country_name') }}
                <span class="text-thc-text/40" aria-hidden="true"> · </span>
                <a href="{{ route('contact.show') }}" class="text-thc-royal hover:underline">{{ __('Full contact directory') }}</a>
            </p>
        </div>
    </section>
</div>
