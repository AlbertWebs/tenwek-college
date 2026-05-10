@php
    $telHref = fn (?string $display): string => 'tel:'.preg_replace('/[^\d+]/', '', (string) $display);
    $socContact = config('tenwek.soc_landing.contact', []);
    $cohsContact = config('tenwek.cohs_landing.contact_page', []);
    $dir = config('tenwek.contact_directory', []);
    $hospitalMeta = config('tenwek.contact_directory.hospital', []);
    $ctcMeta = config('tenwek.contact_directory.ctc', []);
    $schools = \App\Models\School::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
@endphp

<x-layouts.public :seo="$seo">
    <div class="border-b border-thc-navy/8 bg-gradient-to-b from-thc-navy/[0.04] via-white to-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
            <p class="thc-kicker">{{ __('Contact') }}</p>
            <h1 class="mt-3 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">{{ __('Reach Tenwek') }}</h1>
            <p class="mt-5 max-w-3xl text-lg leading-relaxed text-thc-text/90">
                {{ $dir['intro'] ?? __('We are glad to help with admissions, programmes, patient services, and partnerships.') }}
            </p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:grid lg:grid-cols-12 lg:gap-12 lg:px-8 lg:py-16">
        {{-- Directory --}}
        <div class="space-y-10 lg:col-span-7">
            <section aria-labelledby="contact-college-heading">
                <h2 id="contact-college-heading" class="text-xs font-bold uppercase tracking-[0.2em] text-thc-text/55">{{ __('Tenwek Hospital College') }}</h2>
                <div class="mt-5 space-y-5">
                    {{-- General --}}
                    <div class="thc-card-surface overflow-hidden border-l-4 border-l-thc-royal p-6">
                        <h3 class="font-serif text-lg font-semibold text-thc-navy">{{ __('College main office') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-thc-text/85">{{ __('General enquiries, partnerships, and messages that are not specific to one school.') }}</p>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Email') }}</dt>
                                <dd class="mt-1">
                                    <a href="mailto:{{ config('tenwek.email_public') }}" class="font-medium text-thc-royal hover:underline">{{ config('tenwek.email_public') }}</a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Phone') }}</dt>
                                <dd class="mt-1">
                                    <a href="{{ $telHref(config('tenwek.phone')) }}" class="font-medium text-thc-royal hover:underline">{{ config('tenwek.phone') }}</a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Postal address') }}</dt>
                                <dd class="mt-1 text-thc-text/88">{{ config('tenwek.address.street') }}, {{ config('tenwek.address.locality') }}, {{ config('tenwek.address.country_name') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- School of Chaplaincy --}}
                    <div class="thc-card-surface overflow-hidden border-l-4 border-l-thc-maroon p-6">
                        <h3 class="font-serif text-lg font-semibold text-thc-navy">{{ __('School of Chaplaincy') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-thc-text/85">{{ __('Chaplaincy programmes, admissions, and school-specific questions.') }}</p>
                        <dl class="mt-4 space-y-3 text-sm">
                            @if(! empty($socContact['email']))
                                <div>
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Email') }}</dt>
                                    <dd class="mt-1">
                                        <a href="mailto:{{ $socContact['email'] }}" class="font-medium text-thc-royal hover:underline">{{ $socContact['email'] }}</a>
                                    </dd>
                                </div>
                            @endif
                            @if(! empty($socContact['phones']))
                                <div>
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Phone') }}</dt>
                                    <dd class="mt-1 space-y-1">
                                        @foreach($socContact['phones'] as $phone)
                                            <div>
                                                <a href="{{ $telHref($phone) }}" class="font-medium text-thc-royal hover:underline">{{ $phone }}</a>
                                            </div>
                                        @endforeach
                                    </dd>
                                </div>
                            @endif
                            @if(! empty($socContact['location_lines']))
                                <div>
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Location') }}</dt>
                                    <dd class="mt-1 space-y-0.5 text-thc-text/88">
                                        @foreach($socContact['location_lines'] as $line)
                                            <div>{{ $line }}</div>
                                        @endforeach
                                    </dd>
                                </div>
                            @endif
                        </dl>
                        <a href="{{ route('schools.show', ['school' => 'soc']) }}#contact" class="mt-4 inline-flex text-sm font-semibold text-thc-royal hover:underline">{{ __('School website & contact form') }} →</a>
                    </div>

                    {{-- COHS --}}
                    <div class="thc-card-surface overflow-hidden border-l-4 border-l-thc-teal p-6">
                        <h3 class="font-serif text-lg font-semibold text-thc-navy">{{ __('College of Health Sciences') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-thc-text/85">{{ __('Nursing and clinical medicine programmes, admissions, and college office.') }}</p>
                        <dl class="mt-4 space-y-3 text-sm">
                            @if(! empty($cohsContact['email']))
                                <div>
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Email') }}</dt>
                                    <dd class="mt-1">
                                        <a href="mailto:{{ $cohsContact['email'] }}" class="font-medium text-thc-royal hover:underline">{{ $cohsContact['email'] }}</a>
                                    </dd>
                                </div>
                            @endif
                            @if(! empty($cohsContact['phone_rows']))
                                @foreach($cohsContact['phone_rows'] as $row)
                                    <div>
                                        <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ $row['label'] ?? __('Phone') }}</dt>
                                        <dd class="mt-1 space-y-1">
                                            @foreach($row['numbers'] ?? [] as $num)
                                                <div>
                                                    <a href="{{ $telHref($num['tel'] ?? $num['display'] ?? '') }}" class="font-medium text-thc-royal hover:underline">{{ $num['display'] ?? $num['tel'] }}</a>
                                                </div>
                                            @endforeach
                                        </dd>
                                    </div>
                                @endforeach
                            @endif
                            @if(! empty($cohsContact['address_lines']))
                                <div>
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Postal address') }}</dt>
                                    <dd class="mt-1 space-y-0.5 text-thc-text/88">
                                        @foreach($cohsContact['address_lines'] as $line)
                                            <div>{{ $line }}</div>
                                        @endforeach
                                    </dd>
                                </div>
                            @endif
                        </dl>
                        <a href="{{ route('schools.pages.show', ['school' => 'cohs', 'pageSlug' => 'contact-us']) }}" class="mt-4 inline-flex text-sm font-semibold text-thc-royal hover:underline">{{ __('COHS contact page') }} →</a>
                    </div>
                </div>
            </section>

            <section class="border-t border-thc-navy/10 pt-10" aria-labelledby="contact-hospital-heading">
                <h2 id="contact-hospital-heading" class="text-xs font-bold uppercase tracking-[0.2em] text-thc-text/55">{{ __('Hospital & speciality centre') }}</h2>
                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div class="thc-card-surface overflow-hidden border-l-4 border-l-thc-navy p-6">
                        <h3 class="font-serif text-lg font-semibold text-thc-navy">{{ config('tenwek.hospital.name') }}</h3>
                        @if(! empty($hospitalMeta['tagline']))
                            <p class="mt-2 text-sm leading-relaxed text-thc-text/85">{{ $hospitalMeta['tagline'] }}</p>
                        @endif
                        <dl class="mt-4 space-y-3 text-sm">
                            @if(! empty($hospitalMeta['phone']))
                                <div>
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Phone') }}</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $telHref($hospitalMeta['phone']) }}" class="font-medium text-thc-royal hover:underline">{{ $hospitalMeta['phone'] }}</a>
                                    </dd>
                                </div>
                            @endif
                            @if(filled($hospitalMeta['email'] ?? null))
                                <div>
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Email') }}</dt>
                                    <dd class="mt-1">
                                        <a href="mailto:{{ $hospitalMeta['email'] }}" class="font-medium text-thc-royal hover:underline break-all">{{ $hospitalMeta['email'] }}</a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                        <a href="{{ config('tenwek.hospital.url') }}" class="mt-4 inline-flex text-sm font-semibold text-thc-royal hover:underline" rel="noopener noreferrer">{{ __('Hospital website') }} →</a>
                    </div>

                    <div class="thc-card-surface overflow-hidden border-l-4 border-l-thc-navy/60 p-6 sm:border-l-thc-royal">
                        <h3 class="font-serif text-lg font-semibold text-thc-navy">{{ config('tenwek.ctc.name') }}</h3>
                        @if(! empty($ctcMeta['tagline']))
                            <p class="mt-2 text-sm leading-relaxed text-thc-text/85">{{ $ctcMeta['tagline'] }}</p>
                        @endif
                        <dl class="mt-4 space-y-3 text-sm">
                            @if(! empty($ctcMeta['phone']))
                                <div>
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Phone') }}</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $telHref($ctcMeta['phone']) }}" class="font-medium text-thc-royal hover:underline">{{ $ctcMeta['phone'] }}</a>
                                    </dd>
                                </div>
                            @endif
                            @if(filled($ctcMeta['email'] ?? null))
                                <div>
                                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-thc-text/50">{{ __('Email') }}</dt>
                                    <dd class="mt-1">
                                        <a href="mailto:{{ $ctcMeta['email'] }}" class="font-medium text-thc-royal hover:underline break-all">{{ $ctcMeta['email'] }}</a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                        <a href="{{ config('tenwek.ctc.url') }}" class="mt-4 inline-flex text-sm font-semibold text-thc-royal hover:underline" rel="noopener noreferrer">{{ __('Cardiothoracic Centre website') }} →</a>
                    </div>
                </div>
            </section>
        </div>

        {{-- Form --}}
        <div class="mt-12 lg:col-span-5 lg:mt-0">
            <div class="lg:sticky lg:top-28">
                <div class="rounded-2xl border border-thc-navy/12 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="font-serif text-xl font-semibold text-thc-navy">{{ __('Send a message') }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-thc-text/85">{{ __('We read every submission. Optionally direct your note to a school so the right team can respond.') }}</p>

                    @if (session('status'))
                        <div class="mb-6 mt-6 rounded-xl border border-thc-royal/25 bg-thc-royal/8 px-4 py-3 text-sm text-thc-navy">{{ session('status') }}</div>
                    @endif

                    <form method="post" action="{{ route('contact.store') }}" class="mt-6 space-y-4">
                        @csrf
                        <input type="text" name="fax" tabindex="-1" autocomplete="off" class="absolute -left-[9999px] h-0 w-0 opacity-0" aria-hidden="true">

                        @if($schools->isNotEmpty())
                            <div>
                                <label for="contact-school" class="block text-sm font-medium text-thc-text">{{ __('Related to') }} <span class="font-normal text-thc-text/65">({{ __('optional') }})</span></label>
                                <select name="school_id" id="contact-school" class="mt-1 w-full rounded-xl border border-thc-navy/12 bg-white px-3 py-2.5 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                                    <option value="">{{ __('General college office') }}</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" @selected(old('school_id') == $school->id)>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                                @error('school_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        @endif

                        <div>
                            <label for="name" class="block text-sm font-medium text-thc-text">{{ __('Full name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name" class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-thc-text">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-thc-text">{{ __('Phone') }} <span class="font-normal text-thc-text/65">({{ __('optional') }})</span></label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" autocomplete="tel" class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="topic" class="block text-sm font-medium text-thc-text">{{ __('Topic') }} <span class="font-normal text-thc-text/65">({{ __('optional') }})</span></label>
                            <input type="text" name="topic" id="topic" value="{{ old('topic') }}" class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                            @error('topic')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-thc-text">{{ __('Message') }} <span class="font-normal text-thc-text/65">({{ __('optional') }})</span></label>
                            <textarea name="message" id="message" rows="5" class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="w-full rounded-full bg-thc-royal py-3 text-sm font-semibold text-white transition hover:bg-thc-navy">{{ __('Send message') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
