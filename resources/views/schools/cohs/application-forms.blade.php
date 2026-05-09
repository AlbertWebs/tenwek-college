<x-layouts.public :seo="$seo" landing-header="cohs" :school="$school">
    <div class="cohs-page-hero relative overflow-hidden">
        <div class="pointer-events-none absolute -right-20 top-0 h-64 w-64 rounded-full bg-thc-royal/[0.12] blur-3xl" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <nav class="mb-8 text-sm text-thc-text/65" aria-label="Breadcrumb" data-reveal>
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ route('home') }}" class="transition hover:text-thc-royal">Home</a></li>
                    <li aria-hidden="true" class="text-thc-text/35">/</li>
                    <li><a href="{{ route('schools.show', $school) }}" class="transition hover:text-thc-royal">{{ $school->name }}</a></li>
                    <li aria-hidden="true" class="text-thc-text/35">/</li>
                    <li class="font-medium text-thc-navy">{{ $page->title }}</li>
                </ol>
            </nav>

            <header class="max-w-3xl" data-reveal>
                <p class="thc-kicker">College of Health Sciences</p>
                <h1 class="mt-3 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl lg:text-[3.25rem]">
                    Application <span class="italic text-thc-royal">forms</span>
                </h1>
                @if(filled($page->excerpt))
                    <p class="mt-5 text-lg leading-relaxed text-thc-text/88 sm:text-xl">{{ $page->excerpt }}</p>
                @else
                    <p class="mt-5 text-lg leading-relaxed text-thc-text/80 sm:text-xl">
                        Printable and downloadable materials for applicants and enrolled students.
                    </p>
                @endif
            </header>
        </div>
    </div>

    <section
        class="border-t border-thc-navy/8 bg-gradient-to-b from-white via-white to-thc-navy/[0.03] py-14 sm:py-16 lg:py-20"
        aria-labelledby="cohs-forms-list-heading"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                <div class="max-w-xl">
                    <h2 id="cohs-forms-list-heading" class="font-serif text-2xl font-semibold tracking-tight text-thc-navy sm:text-3xl">
                        Documents
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-thc-text/75 sm:text-base">
                        Select a form to download or open. Use “View details” for a summary and related files.
                    </p>
                </div>
                <a
                    href="{{ route('downloads.index', ['school' => 'cohs']) }}"
                    class="thc-btn-ghost inline-flex shrink-0 items-center justify-center self-start sm:self-auto"
                >
                    <svg class="h-4 w-4 text-thc-royal" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    All COHS downloads
                </a>
            </div>

            <ul class="mt-10 grid list-none gap-6 sm:grid-cols-2 lg:mt-12 lg:gap-8" role="list">
                @forelse($applicationForms as $form)
                    @php
                        $desc = filled($form->description)
                            ? \Illuminate\Support\Str::limit(\Illuminate\Support\Str::squish(strip_tags($form->description)), 220)
                            : null;
                        $url = $form->primaryDownloadUrl();
                    @endphp
                    <li class="min-w-0" data-reveal>
                        <article
                            class="group relative flex h-full flex-col overflow-hidden rounded-[var(--radius-thc-card)] border border-thc-navy/10 bg-white shadow-[var(--shadow-thc-card)] ring-1 ring-thc-navy/[0.04] transition duration-300 hover:-translate-y-0.5 hover:shadow-[var(--shadow-thc-card-hover)]"
                            aria-labelledby="cohs-form-heading-{{ $form->slug }}"
                        >
                            <div class="h-1 shrink-0 bg-gradient-to-r from-thc-navy via-thc-royal to-thc-navy/75" aria-hidden="true"></div>
                            <div class="flex flex-1 flex-col p-6 sm:p-8">
                                <div class="flex gap-4">
                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-thc-navy/[0.08] to-thc-royal/[0.12] text-thc-navy ring-1 ring-thc-navy/10"
                                        aria-hidden="true"
                                    >
                                        <svg class="h-6 w-6 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 id="cohs-form-heading-{{ $form->slug }}" class="font-serif text-lg font-semibold leading-snug text-thc-navy sm:text-xl">
                                                {{ $form->title }}
                                            </h3>
                                            @if(filled($form->extension))
                                                <span class="inline-flex items-center rounded-full bg-thc-navy/[0.07] px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.14em] text-thc-navy/90">
                                                    {{ strtoupper($form->extension) }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($desc)
                                            <p class="mt-3 text-sm leading-relaxed text-thc-text/80">{{ $desc }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-thc-navy/8 pt-6">
                                    @if($url)
                                        <a
                                            href="{{ $url }}"
                                            @if($form->primaryDownloadOpensNewTab()) target="_blank" rel="noopener noreferrer" @endif
                                            class="thc-btn-primary"
                                        >
                                            <svg class="h-4 w-4 shrink-0 opacity-95" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                        <a
                                            href="{{ route('downloads.show', $form) }}"
                                            class="inline-flex items-center gap-1.5 rounded-full px-4 py-2.5 text-sm font-semibold text-thc-royal transition hover:bg-thc-royal/10"
                                        >
                                            View details
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @else
                                        <p class="text-sm leading-relaxed text-thc-text/75">
                                            This document is being prepared. Please contact the College office.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </li>
                @empty
                    <li class="col-span-full flex justify-center px-0 sm:px-4" data-reveal>
                        <div class="w-full max-w-lg rounded-[var(--radius-thc-card)] border border-thc-navy/10 bg-white p-10 text-center shadow-[var(--shadow-thc-card)] sm:p-12">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-thc-navy/[0.06] text-thc-navy ring-1 ring-thc-navy/10" aria-hidden="true">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                                </svg>
                            </div>
                            <p class="mt-6 font-serif text-lg font-semibold text-thc-navy">No forms listed yet</p>
                            <p class="mt-3 text-sm leading-relaxed text-thc-text/80">
                                You can still explore all college downloads or get in touch for paper copies.
                            </p>
                            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                                <a href="{{ route('downloads.index', ['school' => 'cohs']) }}" class="thc-btn-primary justify-center">
                                    Browse downloads
                                </a>
                                <a href="{{ route('schools.pages.show', [$school, 'contact-us']) }}" class="thc-btn-ghost justify-center border-thc-navy/12">
                                    Contact the office
                                </a>
                            </div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </section>

    @if($applicationForms->isNotEmpty())
        <div class="border-t border-thc-navy/8 bg-thc-navy/[0.03] py-12 sm:py-14">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-center gap-4 px-4 text-center sm:flex-row sm:gap-8 sm:px-6 lg:px-8" data-reveal>
                <p class="text-sm font-medium text-thc-navy">Looking for something else?</p>
                <div class="flex flex-wrap items-center justify-center gap-4 text-sm">
                    <a href="{{ route('downloads.index', ['school' => 'cohs']) }}" class="font-semibold text-thc-royal underline decoration-thc-royal/30 underline-offset-4 transition hover:decoration-thc-royal">
                        Full downloads library
                    </a>
                    <span class="hidden text-thc-text/25 sm:inline" aria-hidden="true">·</span>
                    <a href="{{ route('schools.pages.show', [$school, 'contact-us']) }}" class="font-semibold text-thc-royal underline decoration-thc-royal/30 underline-offset-4 transition hover:decoration-thc-royal">
                        Contact us
                    </a>
                </div>
            </div>
        </div>
    @endif
</x-layouts.public>
