@php
    $L = $cohsLanding ?? config('tenwek.cohs_landing');
    $logoPath = $L['logo'] ?? config('tenwek.cohs_landing.logo', config('tenwek.brand_logo'));
    $cohsAppLogo = \App\Support\Cohs\CohsLandingRepository::publicMediaUrl(is_string($logoPath) ? $logoPath : null) ?? asset(is_string($logoPath) ? $logoPath : (string) config('tenwek.brand_logo'));
@endphp
<x-layouts.public :seo="$seo" landing-header="cohs" :school="$school">
    <div class="min-h-[60vh] bg-gradient-to-b from-thc-navy/[0.04] to-thc-surface pb-16 pt-8 sm:pt-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-6 text-sm text-thc-text/65" aria-label="Breadcrumb" data-reveal>
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ route('home') }}" class="hover:text-thc-royal">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ route('schools.show', $school) }}" class="hover:text-thc-royal">{{ $school->name }}</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="text-thc-navy">On-campus application</li>
                </ol>
            </nav>

            {{-- Portal shell styled like legacy OSMIS application header --}}
            <div class="overflow-hidden rounded-2xl border border-thc-navy/15 bg-white shadow-[var(--shadow-thc-card)] ring-1 ring-thc-navy/[0.06]" data-reveal x-data="cohsOnCampusWizard">
                <header class="border-b border-thc-navy/10 bg-gradient-to-r from-thc-navy to-thc-navy/95 px-5 py-8 text-center text-white sm:px-8">
                    <div class="mx-auto flex max-w-md flex-col items-center gap-3">
                        <img
                            src="{{ $cohsAppLogo }}"
                            alt="{{ $school->name }}"
                            class="h-12 w-auto max-w-[14rem] object-contain sm:h-14 sm:max-w-[16rem]"
                            width="280"
                            height="96"
                        >
                        <h1 class="font-serif text-2xl font-semibold tracking-tight sm:text-3xl">Tenwek Hospital College</h1>
                        <p class="text-sm text-white/85">
                            Phone: <a href="tel:+254728091900" class="font-medium underline decoration-white/40 hover:decoration-white">+254 728 091 900</a>
                            Ext 1315/1334
                            <span class="text-white/50">|</span>
                            Email:
                            <a href="mailto:collegeofhealthsciences@tenwekhosp.org" class="font-medium underline decoration-white/40 hover:decoration-white">collegeofhealthsciences@tenwekhosp.org</a>
                        </p>
                    </div>
                    <div class="mx-auto mt-6 max-w-2xl rounded-xl border border-white/15 bg-white/10 px-4 py-4 backdrop-blur-sm">
                        <h2 class="font-serif text-lg font-semibold leading-snug sm:text-xl">
                            Welcome to Tenwek Hospital College, School of Health Sciences
                        </h2>
                        <p class="mt-1 text-sm text-white/90">Online Application Portal</p>
                        <p class="mt-3 text-sm leading-relaxed text-white/85">
                            You’re one step closer to a brighter future. Apply with confidence. Learn with excellence. Apply now!
                        </p>
                    </div>
                </header>

                <div class="border-b border-thc-navy/10 bg-thc-navy/[0.03] px-5 py-4 sm:px-8">
                    <div class="flex items-center justify-between gap-3 text-sm font-semibold text-thc-navy">
                        <span>Step <span x-text="step"></span> of <span x-text="totalSteps"></span></span>
                        <span x-text="progress() + '%'"></span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-thc-navy/15" role="progressbar" :aria-valuenow="progress()" aria-valuemin="0" aria-valuemax="100">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-600 to-thc-royal transition-all duration-300" :style="`width: ${progress()}%`"></div>
                    </div>
                </div>

                @if(session('status'))
                    <div class="border-b border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-950 sm:px-8" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="border-b border-red-200 bg-red-50 px-5 py-4 text-red-950 sm:px-8">
                        <p class="font-semibold">Please correct the following:</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="px-5 py-8 sm:px-8 sm:py-10">
                    <form method="post" action="{{ route('cohs.on-campus-application.store') }}" enctype="multipart/form-data" class="space-y-8" x-ref="appForm">
                        @csrf

                        <div class="absolute -left-[9999px] h-px w-px overflow-hidden opacity-0" aria-hidden="true">
                            <label for="cohs-app-fax">Leave blank</label>
                            <input id="cohs-app-fax" type="text" name="fax" value="" tabindex="-1" autocomplete="off">
                        </div>

                        <div data-step="1" x-show="step === 1" x-cloak>
                            @include('schools.cohs.on-campus-application._step-personal', ['countries' => $countries, 'counties' => $counties])
                        </div>
                        <div data-step="2" x-show="step === 2" x-cloak>
                            @include('schools.cohs.on-campus-application._step-parents')
                        </div>
                        <div data-step="3" x-show="step === 3" x-cloak>
                            @include('schools.cohs.on-campus-application._step-education')
                        </div>
                        <div data-step="4" x-show="step === 4" x-cloak>
                            @include('schools.cohs.on-campus-application._step-programme', ['programmes' => $programmes, 'studyModes' => $studyModes, 'campuses' => $campuses])
                        </div>
                        <div data-step="5" x-show="step === 5" x-cloak>
                            @include('schools.cohs.on-campus-application._step-uploads')
                        </div>
                        <div data-step="6" x-show="step === 6" x-cloak>
                            @include('schools.cohs.on-campus-application._step-additional')
                        </div>
                        <div data-step="7" x-show="step === 7" x-cloak>
                            @include('schools.cohs.on-campus-application._step-summary')
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-thc-navy/10 pt-8 sm:flex-row sm:justify-between">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl border border-thc-navy/15 bg-white px-5 py-2.5 text-sm font-semibold text-thc-navy shadow-sm transition hover:bg-thc-royal/8"
                                x-show="step > 1"
                                @click="prev()"
                            >
                                Previous
                            </button>
                            <div class="flex flex-col gap-3 sm:ml-auto sm:flex-row">
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800"
                                    x-show="step < totalSteps"
                                    @click="next()"
                                >
                                    Next
                                </button>
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-thc-navy px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-thc-navy/90"
                                    x-show="step === totalSteps"
                                >
                                    Submit application
                                </button>
                            </div>
                        </div>
                    </form>

                    <p class="mt-8 text-center text-xs text-thc-text/55">
                        © {{ date('Y') }} Tenwek Hospital College. Application data is processed securely.
                    </p>
                </div>
            </div>

            <p class="mt-8 text-center text-sm text-thc-text/70">
                <a href="{{ route('schools.pages.show', [$school, 'application-forms']) }}" class="font-semibold text-thc-royal hover:underline">Application forms &amp; downloads</a>
                ·
                <a href="{{ route('cohs.off-campus-application.redirect') }}" class="font-semibold text-thc-royal hover:underline">Off-campus online application</a>
            </p>
        </div>
    </div>
</x-layouts.public>
