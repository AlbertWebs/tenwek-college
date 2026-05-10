<x-layouts.public :seo="$seo" :school="$school" :landing-header="$school->slug">
    <div class="relative overflow-hidden pb-20 lg:pb-28">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[min(28rem,80vh)] bg-gradient-to-b from-thc-navy/[0.07] via-transparent to-transparent" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-7xl px-4 pt-12 sm:px-6 lg:px-8 lg:pt-16">
            <nav class="text-sm text-thc-text/70" aria-label="{{ __('Breadcrumb') }}">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ route('home') }}" class="font-medium text-thc-royal hover:underline">{{ __('Home') }}</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ route('schools.show', $school) }}" class="font-medium text-thc-royal hover:underline">{{ $school->name }}</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="font-medium text-thc-navy">{{ __('Events') }}</li>
                </ol>
            </nav>

            <header class="mx-auto mt-10 max-w-3xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-thc-teal">{{ $schoolLabel }}</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">
                    {{ __('Events') }}
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-relaxed text-thc-text/88">
                    {{ __('Gatherings, trainings, and milestones for our school community.') }}
                </p>
            </header>

            <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <article class="group flex flex-col overflow-hidden rounded-2xl border border-thc-navy/10 bg-white shadow-sm transition hover:border-thc-royal/25 hover:shadow-md">
                        @if ($event->imagePublicUrl())
                            <a href="{{ route('schools.events.show', [$school, $event->slug]) }}" class="relative block overflow-hidden">
                                <img
                                    src="{{ $event->imagePublicUrl() }}"
                                    alt=""
                                    class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-[1.02]"
                                    loading="lazy"
                                    width="640"
                                    height="400"
                                >
                            </a>
                        @else
                            <div class="aspect-[16/10] w-full bg-gradient-to-br from-thc-navy/[0.08] to-thc-royal/[0.06]" aria-hidden="true"></div>
                        @endif
                        <div class="flex flex-1 flex-col p-6">
                            <time class="text-xs font-semibold uppercase tracking-[0.12em] text-thc-text/60" datetime="{{ $event->starts_at->toIso8601String() }}">
                                {{ $event->starts_at->format('M j, Y · g:i a') }}
                            </time>
                            @if ($event->location)
                                <p class="mt-1 text-xs text-thc-text/65">{{ $event->location }}</p>
                            @endif
                            <h2 class="mt-4 font-serif text-xl font-semibold leading-snug text-thc-navy">
                                <a href="{{ route('schools.events.show', [$school, $event->slug]) }}" class="hover:text-thc-royal hover:underline">
                                    {{ $event->title }}
                                </a>
                            </h2>
                            @if ($event->excerpt)
                                <p class="mt-3 flex-1 text-sm leading-relaxed text-thc-text/85">
                                    {{ \Illuminate\Support\Str::limit($event->excerpt, 160) }}
                                </p>
                            @endif
                            <p class="mt-5">
                                <a href="{{ route('schools.events.show', [$school, $event->slug]) }}" class="text-sm font-semibold text-thc-teal hover:text-thc-royal">
                                    {{ __('Details') }} →
                                </a>
                            </p>
                        </div>
                    </article>
                @empty
                    <p class="col-span-full rounded-2xl border border-dashed border-thc-navy/15 bg-white py-16 text-center text-thc-text/70">
                        {{ __('No published events at the moment. Please check back soon.') }}
                    </p>
                @endforelse
            </div>

            <div class="mt-12">{{ $events->links() }}</div>
        </div>
    </div>
</x-layouts.public>
