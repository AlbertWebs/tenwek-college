<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    <div class="soc-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <nav class="mb-6 text-sm text-thc-text/65" aria-label="Breadcrumb" data-reveal>
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ route('home') }}" class="transition hover:text-thc-royal hover:underline">Home</a></li>
                    <li class="text-thc-text/45" aria-hidden="true">/</li>
                    <li><a href="{{ route('schools.show', $school) }}" class="transition hover:text-thc-royal hover:underline">{{ $school->name }}</a></li>
                    <li class="text-thc-text/45" aria-hidden="true">/</li>
                    <li class="font-medium text-thc-navy">{{ $page->title }}</li>
                </ol>
            </nav>

            <header class="max-w-3xl" data-reveal>
                <p class="thc-kicker">Online application</p>
                <h1 class="mt-3 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">
                    Sign up for School of Chaplaincy courses
                </h1>
                <p class="mt-5 text-lg leading-relaxed text-thc-text/90">
                    Take your time with each step. Use <strong class="font-semibold text-thc-navy">Back</strong> whenever you need to review or change something, then continue when you are ready to submit.
                </p>
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14" x-data="socRegisterWizard">
        @if(session('status'))
            <div class="mb-8 rounded-2xl border border-emerald-600/25 bg-emerald-50 px-5 py-4 text-emerald-950" role="status" data-reveal>
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 rounded-2xl border border-thc-maroon/30 bg-thc-maroon/10 px-5 py-4 text-thc-navy" data-reveal>
                <p class="font-semibold text-thc-maroon">Please correct the following:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-8 rounded-2xl border border-thc-navy/10 bg-white p-4 shadow-sm" data-reveal>
            <div class="flex items-center justify-between gap-3 text-sm font-medium text-thc-navy">
                <span>Step <span x-text="step"></span> of <span x-text="totalSteps"></span></span>
                <span x-text="progress() + '%'"></span>
            </div>
            <div class="mt-2 h-2 overflow-hidden rounded-full bg-thc-navy/10" role="progressbar" :aria-valuenow="progress()" aria-valuemin="0" aria-valuemax="100">
                <div class="h-full rounded-full bg-gradient-to-r from-thc-maroon to-thc-royal transition-all duration-300" :style="`width: ${progress()}%`"></div>
            </div>
        </div>

        <form method="post" action="{{ route('soc.register.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="absolute -left-[9999px] h-px w-px overflow-hidden opacity-0" aria-hidden="true">
                <label for="register-fax">Leave blank</label>
                <input id="register-fax" type="text" name="fax" value="" tabindex="-1" autocomplete="off">
            </div>

            <div x-show="step === 1" x-cloak>
                @include('schools.soc.register._step1')
            </div>
            <div x-show="step === 2" x-cloak>
                @include('schools.soc.register._step2')
            </div>
            <div x-show="step === 3" x-cloak>
                @include('schools.soc.register._step3')
            </div>
            <div x-show="step === 4" x-cloak>
                @include('schools.soc.register._step4')
            </div>
            <div x-show="step === 5" x-cloak>
                @include('schools.soc.register._step5')
            </div>
            <div x-show="step === 6" x-cloak>
                @include('schools.soc.register._step6')
            </div>
            <div x-show="step === 7" x-cloak>
                @include('schools.soc.register._step7')
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-thc-navy/10 pt-8 sm:flex-row sm:justify-between">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-xl border border-thc-navy/15 bg-white px-5 py-2.5 text-sm font-semibold text-thc-navy shadow-sm transition hover:bg-thc-royal/8"
                    x-show="step > 1"
                    @click="prev()"
                >
                    Back
                </button>
                <div class="flex flex-col gap-3 sm:ml-auto sm:flex-row">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-xl bg-thc-maroon px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-thc-maroon/90"
                        x-show="step < totalSteps"
                        @click="next()"
                    >
                        Continue
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

        <p class="mt-10 text-center text-sm text-thc-text/70" data-reveal>
            <a href="{{ route('schools.pages.show', [$school, 'admissions']) }}" class="font-semibold text-thc-royal hover:underline">Admissions information</a>
            ·
            <a href="{{ route('schools.pages.show', [$school, 'fee']) }}" class="font-semibold text-thc-royal hover:underline">Fee structure</a>
        </p>
    </div>
</x-layouts.public>
