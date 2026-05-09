@php
    use App\Support\Soc\SocLandingRepository;
    $L = $socLanding ?? config('tenwek.soc_landing');
    $hero = $L['hero'];
    $heroImg = $L['hero_image'];
    $aboutImg = $L['about_image'];
    $heroImageUrl = SocLandingRepository::publicMediaUrl($heroImg) ?? asset('banner-a.jpg');
    $aboutImageUrl = SocLandingRepository::publicMediaUrl($aboutImg) ?? asset('banner-a.jpg');
    $primaryCta = $hero['primary_cta'];
    $mapUrl = $L['map_embed_url'];
    $testimonials = $L['testimonials'];
    $contactBlock = $L['contact'];
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    {{-- Hero --}}
    <section
        class="relative flex min-h-[min(92svh,52rem)] flex-col justify-end overflow-hidden"
        aria-label="School of Chaplaincy hero"
    >
        <div class="pointer-events-none absolute inset-0 thc-hero-ken-bg bg-cover bg-center" style="background-image: url('{{ e($heroImageUrl) }}');" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-thc-navy via-thc-soc-teal/38 to-thc-maroon/48" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-0 opacity-[0.14] mix-blend-overlay" style="background-image: radial-gradient(circle at 20% 25%, rgba(255,255,255,0.45), transparent 45%), radial-gradient(circle at 85% 70%, rgba(21,104,116,0.4), transparent 42%);" aria-hidden="true"></div>

        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 pb-16 pt-28 sm:px-6 sm:pb-20 sm:pt-32 lg:px-8 lg:pb-24">
            <x-schools.soc.breadcrumbs :school="$school" variant="hero" class="mb-8" />
            <div data-reveal class="max-w-3xl">
                <p class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-white/95 backdrop-blur-sm">
                    {{ $hero['badge'] }}
                </p>
                <p class="mt-6 text-sm font-semibold uppercase tracking-[0.28em] text-white/80">{{ $hero['eyebrow'] }}</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold leading-[1.08] tracking-tight text-white sm:text-5xl lg:text-[3.25rem]">
                    {{ $hero['headline'] }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/92 sm:text-xl">
                    {{ $hero['subhead'] }}
                </p>
                <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-center">
                    <a href="{{ route($primaryCta['route'], $primaryCta['params']) }}" class="thc-btn-primary justify-center px-8 shadow-xl ring-1 ring-white/10">
                        {{ $primaryCta['label'] }}
                    </a>
                    <a href="#{{ $hero['secondary_cta']['hash'] }}" class="thc-btn-secondary justify-center px-8">
                        {{ $hero['secondary_cta']['label'] }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- About (Karibu) --}}
    <section id="about" class="scroll-mt-24 border-b border-thc-navy/8 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:grid lg:grid-cols-12 lg:gap-12 lg:px-8 lg:py-24">
            <div class="lg:col-span-5" data-reveal>
                <div class="relative aspect-[4/5] overflow-hidden rounded-2xl shadow-[var(--shadow-thc-card)] ring-1 ring-thc-navy/10 sm:aspect-[3/4] lg:sticky lg:top-28 lg:max-h-[min(85vh,36rem)] lg:min-h-[20rem]">
                    <div class="absolute inset-0 bg-cover bg-center transition duration-700 hover:scale-[1.02]" style="background-image: url('{{ e($aboutImageUrl) }}');"></div>
                    <div class="absolute inset-0 bg-gradient-to-tr from-thc-navy/35 via-transparent to-thc-maroon/25" aria-hidden="true"></div>
                </div>
            </div>
            <div class="mt-12 lg:col-span-7 lg:mt-0" data-reveal>
                <p class="thc-kicker">{{ $L['about']['kicker'] }}</p>
                <h2 class="mt-4 font-serif text-3xl font-semibold tracking-tight text-thc-navy sm:text-4xl">
                    {{ $L['about']['title'] }}
                </h2>
                <p class="mt-6 text-lg font-medium text-thc-royal">{{ $L['about']['lead'] }}</p>
                <div class="mt-6 space-y-4 text-base leading-relaxed text-thc-text/90 sm:text-lg">
                    @foreach($L['about']['paragraphs'] as $p)
                        <p>{{ $p }}</p>
                    @endforeach
                </div>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('schools.pages.show', [$school, 'about-us']) }}" class="thc-btn-ghost">Read more about us</a>
                    <a href="{{ route('schools.pages.show', [$school, 'admissions']) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-thc-maroon hover:underline">Admissions →</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission & Vision --}}
    <section id="mission-vision" class="scroll-mt-24 bg-gradient-to-b from-thc-navy/[0.04] to-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <p class="thc-kicker">Who we are</p>
                <h2 class="mt-4 font-serif text-3xl font-semibold text-thc-navy sm:text-4xl">Mission &amp; vision</h2>
                <p class="mt-4 text-lg text-thc-text/85">Anchors that shape every classroom, placement, and conversation.</p>
            </div>
            <div class="mt-12 grid gap-6 lg:grid-cols-2 lg:gap-8">
                <article
                    data-reveal
                    class="group relative overflow-hidden rounded-2xl border border-thc-navy/10 bg-white p-8 shadow-[var(--shadow-thc-card)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[var(--shadow-thc-card-hover)] sm:p-10"
                >
                    <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-gradient-to-br from-thc-maroon/15 to-transparent opacity-80 transition group-hover:scale-110" aria-hidden="true"></div>
                    <h3 class="relative font-serif text-xl font-semibold text-thc-maroon sm:text-2xl">{{ $L['vision']['title'] }}</h3>
                    <p class="relative mt-6 text-lg leading-relaxed text-thc-text/90">{{ $L['vision']['text'] }}</p>
                </article>
                <article
                    data-reveal
                    class="group relative overflow-hidden rounded-2xl border border-thc-navy/10 bg-white p-8 shadow-[var(--shadow-thc-card)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[var(--shadow-thc-card-hover)] sm:p-10"
                >
                    <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-gradient-to-br from-thc-soc-teal/18 to-transparent opacity-80 transition group-hover:scale-110" aria-hidden="true"></div>
                    <h3 class="relative font-serif text-xl font-semibold text-thc-soc-teal sm:text-2xl">{{ $L['mission']['title'] }}</h3>
                    <p class="relative mt-6 text-lg leading-relaxed text-thc-text/90">{{ $L['mission']['text'] }}</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Motto --}}
    <section id="motto" class="scroll-mt-24 relative overflow-hidden border-y border-thc-navy/10 bg-thc-navy text-white">
        <div class="pointer-events-none absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 50% 0%, rgba(21,104,116,0.4), transparent 55%), radial-gradient(circle at 10% 90%, rgba(0,86,179,0.35), transparent 50%);" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-4xl px-4 py-20 text-center sm:px-6 lg:py-28">
            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-white/70" data-reveal>{{ $L['motto']['kicker'] }}</p>
            <p class="mt-8 font-serif text-3xl font-medium leading-snug text-white sm:text-4xl lg:text-[2.75rem]" data-reveal>
                <span class="text-white/40" aria-hidden="true">“</span>{{ $L['motto']['text'] }}<span class="text-white/40" aria-hidden="true">”</span>
            </p>
        </div>
    </section>

    {{-- Testimonials --}}
    <section id="testimonials" class="scroll-mt-24 bg-white" aria-labelledby="soc-testimonials-heading">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="max-w-2xl" data-reveal>
                <p class="thc-kicker">{{ $testimonials['kicker'] }}</p>
                <h2 id="soc-testimonials-heading" class="mt-4 font-serif text-3xl font-semibold text-thc-navy sm:text-4xl">{{ $testimonials['title'] }}</h2>
            </div>

            @php $tCount = count($testimonials['items']); @endphp
            <div
                class="relative mt-12"
                x-data="{ active: 0, count: {{ $tCount }} }"
                data-reveal
            >
                <div class="overflow-hidden rounded-2xl border border-thc-navy/10 bg-gradient-to-br from-thc-soc-teal/[0.05] via-white to-white shadow-[var(--shadow-thc-card)]">
                    @foreach($testimonials['items'] as $ti => $t)
                        <div
                            x-show="active === {{ $ti }}"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="px-8 py-12 sm:px-12 sm:py-14"
                            role="tabpanel"
                            @if($ti > 0) style="display: none;" @endif
                        >
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-thc-soc-teal">{{ $t['role'] }}</p>
                            <blockquote class="mt-6 font-serif text-xl font-normal leading-relaxed text-thc-navy sm:text-2xl">
                                “{{ $t['quote'] }}”
                            </blockquote>
                            <footer class="mt-8 flex items-center gap-4">
                                @php
                                    $tImg = $t['image'] ?? null;
                                    $tImgUrl = $tImg ? (\App\Support\Soc\SocLandingRepository::publicMediaUrl($tImg) ?? asset($tImg)) : null;
                                @endphp
                                @if(filled($tImgUrl))
                                    <span class="relative h-12 w-12 shrink-0 overflow-hidden rounded-full border border-thc-navy/10 bg-thc-navy/[0.06] shadow-sm ring-1 ring-thc-navy/5" aria-hidden="true">
                                        <img src="{{ $tImgUrl }}" alt="" class="h-full w-full object-cover" width="48" height="48" loading="lazy" decoding="async">
                                    </span>
                                @else
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-thc-soc-teal to-thc-navy text-sm font-bold text-white" aria-hidden="true">{{ \Illuminate\Support\Str::substr($t['name'], 0, 1) }}</span>
                                @endif
                                <div>
                                    <cite class="not-italic text-base font-semibold text-thc-navy">{{ $t['name'] }}</cite>
                                    @if(filled($t['organization'] ?? null))
                                        <p class="mt-0.5 text-sm text-thc-text/75">{{ $t['organization'] }}</p>
                                    @endif
                                </div>
                            </footer>
                        </div>
                    @endforeach
                </div>

                @if($tCount > 1)
                    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex gap-2" role="tablist" aria-label="Choose testimonial">
                            @foreach($testimonials['items'] as $ti => $t)
                                <button
                                    type="button"
                                    class="h-2.5 rounded-full transition-all"
                                    :class="active === {{ $ti }} ? 'w-8 bg-thc-soc-teal' : 'w-2.5 bg-thc-navy/20 hover:bg-thc-navy/35'"
                                    @click="active = {{ $ti }}"
                                    :aria-selected="(active === {{ $ti }}).toString()"
                                    role="tab"
                                    aria-label="Show testimonial {{ $ti + 1 }}"
                                ></button>
                            @endforeach
                        </div>
                        <div class="flex gap-2">
                            <button
                                type="button"
                                class="rounded-full border border-thc-navy/15 px-4 py-2 text-sm font-semibold text-thc-navy transition hover:bg-thc-navy/5"
                                @click="active = (active - 1 + count) % count"
                            >Previous</button>
                            <button
                                type="button"
                                class="rounded-full border border-thc-navy/15 px-4 py-2 text-sm font-semibold text-thc-navy transition hover:bg-thc-navy/5"
                                @click="active = (active + 1) % count"
                            >Next</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Contact --}}
    <section id="contact" class="scroll-mt-24 border-t border-thc-navy/10 bg-gradient-to-b from-white to-thc-navy/[0.04] pb-0">
        <div class="mx-auto max-w-7xl px-4 pt-16 sm:px-6 lg:px-8 lg:pt-24">
            <div class="max-w-3xl lg:max-w-2xl" data-reveal>
                <p class="thc-kicker">{{ $contactBlock['kicker'] }}</p>
                <h2 class="mt-4 font-serif text-3xl font-semibold text-thc-navy sm:text-4xl">We would love to hear from you</h2>
                <p class="mt-4 text-lg text-thc-text/85">Admissions, programme questions, or prayer: reach the School of Chaplaincy office directly.</p>
            </div>

            <div class="mt-12 grid items-start gap-8 lg:grid-cols-12 lg:gap-10 xl:gap-14">
                <div class="lg:col-span-5" data-reveal>
                    <div class="rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-sm sm:p-8">
                        <div class="grid gap-8 sm:grid-cols-2 sm:gap-8 lg:grid-cols-1 lg:gap-10">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-thc-maroon">Our location</h3>
                                <ul class="mt-4 space-y-2 text-sm leading-relaxed text-thc-text/90">
                                    @foreach($contactBlock['location_lines'] as $line)
                                        <li>{{ $line }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="border-t border-thc-navy/10 pt-8 sm:border-l sm:border-t-0 sm:pl-8 sm:pt-0 lg:border-l-0 lg:border-t lg:pl-0 lg:pt-8">
                                <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-thc-maroon">Phone</h3>
                                <ul class="mt-4 space-y-2 text-sm font-medium text-thc-navy">
                                    @foreach($contactBlock['phones'] as $phone)
                                        <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', \Illuminate\Support\Str::before($phone, ',')) }}" class="hover:text-thc-royal hover:underline">{{ $phone }}</a></li>
                                    @endforeach
                                </ul>
                                <h3 class="mt-6 text-xs font-bold uppercase tracking-[0.18em] text-thc-maroon">Email</h3>
                                <p class="mt-3">
                                    <a href="mailto:{{ $contactBlock['email'] }}" class="text-sm font-semibold text-thc-royal hover:underline">{{ $contactBlock['email'] }}</a>
                                </p>
                                @if(count($contactBlock['office_hours_lines'] ?? []) > 0)
                                    <h3 class="mt-6 text-xs font-bold uppercase tracking-[0.18em] text-thc-maroon">Office hours</h3>
                                    <ul class="mt-3 space-y-1.5 text-sm text-thc-text/90">
                                        @foreach($contactBlock['office_hours_lines'] as $hourLine)
                                            <li>{{ $hourLine }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if(count($contactBlock['social_links'] ?? []) > 0)
                                    <h3 class="mt-6 text-xs font-bold uppercase tracking-[0.18em] text-thc-maroon">Social</h3>
                                    <ul class="mt-3 space-y-2 text-sm font-medium text-thc-navy">
                                        @foreach($contactBlock['social_links'] as $s)
                                            <li>
                                                <a href="{{ $s['url'] }}" class="text-thc-royal hover:underline" target="_blank" rel="noopener noreferrer">{{ $s['label'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7" data-reveal>
                    @if (session('status'))
                        <div class="mb-6 rounded-xl border border-thc-royal/25 bg-thc-royal/8 px-4 py-3 text-sm text-thc-navy">{{ session('status') }}</div>
                    @endif

                    <div class="rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-[var(--shadow-thc-card)] sm:p-8">
                        <h3 class="font-serif text-xl font-semibold text-thc-navy">Send a message</h3>
                        <p class="mt-2 text-sm text-thc-text/80">All fields marked required must be completed. Your message is protected the same way as our main contact form.</p>

                        <form method="post" action="{{ route('contact.store') }}" class="mt-8 space-y-5">
                            @csrf
                            <input type="hidden" name="school_id" value="{{ $school->id }}">
                            <input type="text" name="fax" tabindex="-1" autocomplete="off" class="absolute -left-[9999px] h-0 w-0 opacity-0" aria-hidden="true">

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label for="soc-contact-name" class="block text-sm font-medium text-thc-navy">Full name</label>
                                    <input type="text" name="name" id="soc-contact-name" value="{{ old('name') }}" required autocomplete="name" class="mt-1.5 w-full rounded-xl border border-thc-navy/12 px-4 py-3 text-sm transition focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="soc-contact-email" class="block text-sm font-medium text-thc-navy">Email</label>
                                    <input type="email" name="email" id="soc-contact-email" value="{{ old('email') }}" required autocomplete="email" class="mt-1.5 w-full rounded-xl border border-thc-navy/12 px-4 py-3 text-sm transition focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="soc-contact-phone" class="block text-sm font-medium text-thc-navy">Phone <span class="font-normal text-thc-text/60">(optional)</span></label>
                                    <input type="text" name="phone" id="soc-contact-phone" value="{{ old('phone') }}" autocomplete="tel" class="mt-1.5 w-full rounded-xl border border-thc-navy/12 px-4 py-3 text-sm transition focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="soc-contact-topic" class="block text-sm font-medium text-thc-navy">Topic <span class="font-normal text-thc-text/60">(optional)</span></label>
                                    <input type="text" name="topic" id="soc-contact-topic" value="{{ old('topic', 'School of Chaplaincy enquiry') }}" class="mt-1.5 w-full rounded-xl border border-thc-navy/12 px-4 py-3 text-sm transition focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                                    @error('topic')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="soc-contact-message" class="block text-sm font-medium text-thc-navy">Message</label>
                                    <textarea name="message" id="soc-contact-message" rows="5" required class="mt-1.5 w-full rounded-xl border border-thc-navy/12 px-4 py-3 text-sm transition focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">{{ old('message') }}</textarea>
                                    @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <button type="submit" class="thc-btn-primary w-full justify-center sm:w-auto sm:min-w-[12rem]">Send message</button>
                                <p class="text-xs text-thc-text/65">By submitting, you agree we may respond using the details provided.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($mapUrl)
            <div class="mx-auto mt-14 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
                <div class="overflow-hidden rounded-2xl border border-thc-navy/10 bg-thc-navy/[0.03] shadow-[var(--shadow-thc-card)] ring-1 ring-thc-navy/5">
                    <iframe
                        title="Map: Tenwek Hospital College area"
                        src="{{ $mapUrl }}"
                        class="block aspect-video min-h-[16rem] w-full border-0 sm:min-h-[18rem] lg:min-h-[22rem]"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        @endif

        <div class="h-12 sm:h-16 lg:h-20" aria-hidden="true"></div>
    </section>
</x-layouts.public>
