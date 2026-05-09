@php
    $soc = $socLanding ?? config('tenwek.soc_landing');
    $F = $soc['fee'] ?? [];
    $S = $F['structure'] ?? [];
    $cols = $S['columns'] ?? [];
    $rows = $S['rows'] ?? [];
    $totals = $S['totals'] ?? [];
    $fmt = fn (?int $n) => $n === null ? '-' : 'Ksh '.number_format($n);
    $admissionsUrl = route('schools.pages.show', [$school, 'admissions']);
@endphp

<x-layouts.public :seo="$seo" landing-header="soc" :school="$school">
    <div class="soc-page-hero">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <x-schools.soc.breadcrumbs :school="$school" :current="$page->title" class="mb-8" data-reveal />

            <header class="max-w-3xl" data-reveal>
                <p class="thc-kicker">{{ $F['kicker'] ?? 'Fees' }}</p>
                <h1 class="mt-4 font-serif text-4xl font-semibold tracking-tight text-thc-navy sm:text-5xl">
                    {{ $page->title }}
                </h1>
                @if(filled($F['intro'] ?? null))
                    <p class="mt-6 text-lg leading-relaxed text-thc-text/90">{{ $F['intro'] }}</p>
                @endif
                <div class="mt-8 flex flex-wrap gap-3">
                    <a
                        href="{{ $admissionsUrl }}"
                        class="inline-flex items-center justify-center rounded-xl bg-thc-maroon px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-thc-maroon/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-thc-maroon"
                    >
                        Apply now
                    </a>
                    <a
                        href="{{ route('schools.pages.show', [$school, 'academic-programmes']) }}"
                        class="inline-flex items-center justify-center rounded-xl border border-thc-navy/15 bg-white px-5 py-2.5 text-sm font-semibold text-thc-navy shadow-sm transition hover:border-thc-royal/40 hover:bg-thc-royal/[0.04]"
                    >
                        View programmes
                    </a>
                </div>
            </header>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:flex lg:gap-12 lg:px-8 lg:py-16">
        <div class="min-w-0 flex-1 space-y-12 lg:space-y-14">
            {{-- Fee table --}}
            <section data-reveal aria-labelledby="fee-structure-heading">
                <h2 id="fee-structure-heading" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                    {{ $S['title'] ?? 'Fee structure' }}
                </h2>
                <div class="mt-6 overflow-hidden rounded-2xl border border-thc-navy/10 bg-white shadow-sm ring-1 ring-thc-navy/5">
                    <div class="-mx-px overflow-x-auto">
                        <table class="min-w-full border-collapse text-left text-sm">
                            <thead>
                                <tr class="bg-gradient-to-r from-thc-navy to-thc-navy/95 text-white">
                                    <th scope="col" class="px-4 py-3.5 font-semibold sm:px-5">S/No</th>
                                    <th scope="col" class="px-4 py-3.5 font-semibold sm:px-5">Description</th>
                                    <th scope="col" class="px-4 py-3.5 text-right font-semibold tabular-nums sm:px-5">{{ $cols['diploma'] ?? 'Diploma' }}</th>
                                    <th scope="col" class="px-4 py-3.5 text-right font-semibold tabular-nums sm:px-5">{{ $cols['certificate'] ?? 'Certificate' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-thc-navy/8">
                                @foreach($rows as $index => $row)
                                    <tr class="bg-white transition hover:bg-thc-royal/[0.04]">
                                        <td class="px-4 py-3 text-thc-text/70 tabular-nums sm:px-5">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-medium text-thc-navy sm:px-5">{{ $row['description'] ?? '' }}</td>
                                        <td class="px-4 py-3 text-right tabular-nums text-thc-text sm:px-5">{{ $fmt($row['diploma'] ?? null) }}</td>
                                        <td class="px-4 py-3 text-right tabular-nums text-thc-text sm:px-5">{{ $fmt($row['certificate'] ?? null) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-thc-royal/[0.08] font-semibold text-thc-navy">
                                    <td class="px-4 py-4 sm:px-5" colspan="2">Total (per trimester)</td>
                                    <td class="px-4 py-4 text-right tabular-nums sm:px-5">{{ $fmt($totals['diploma'] ?? null) }}</td>
                                    <td class="px-4 py-4 text-right tabular-nums sm:px-5">{{ $fmt($totals['certificate'] ?? null) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </section>

            {{-- One-time & optional --}}
            <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                @if(!empty($F['one_time']['items']))
                    <section
                        class="rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-sm sm:p-8"
                        data-reveal
                        aria-labelledby="one-time-heading"
                    >
                        <h2 id="one-time-heading" class="font-serif text-xl font-semibold text-thc-navy sm:text-2xl">
                            {{ $F['one_time']['title'] ?? 'One-time fees' }}
                        </h2>
                        <ul class="mt-5 divide-y divide-thc-navy/8" role="list">
                            @foreach($F['one_time']['items'] as $item)
                                <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                                    <span class="text-thc-text">{{ $item['label'] ?? '' }}</span>
                                    <span class="shrink-0 tabular-nums font-semibold text-thc-navy">{{ $fmt($item['amount'] ?? null) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if(filled($F['optional']['body'] ?? null))
                    <section
                        class="rounded-2xl border border-dashed border-thc-navy/20 bg-gradient-to-br from-thc-navy/[0.03] to-thc-royal/[0.05] p-6 sm:p-8"
                        data-reveal
                        aria-labelledby="optional-heading"
                    >
                        <h2 id="optional-heading" class="font-serif text-xl font-semibold text-thc-navy sm:text-2xl">
                            {{ $F['optional']['title'] ?? 'Optional fees' }}
                        </h2>
                        <p class="mt-4 leading-relaxed text-thc-text/90">{{ $F['optional']['body'] }}</p>
                    </section>
                @endif
            </div>

            {{-- Payment --}}
            <section class="space-y-6" data-reveal aria-labelledby="payment-heading">
                <h2 id="payment-heading" class="font-serif text-2xl font-semibold text-thc-navy sm:text-3xl">
                    How to pay
                </h2>
                @if(filled($F['payment_notice'] ?? null))
                    <p class="max-w-3xl text-base leading-relaxed text-thc-text/90">{{ $F['payment_notice'] }}</p>
                @endif

                <div class="grid gap-6 lg:grid-cols-2">
                    @php $bank = $F['bank'] ?? []; @endphp
                    @if(count(array_filter($bank)) > 0)
                        <div class="rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-sm sm:p-8">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-thc-maroon">Bank transfer</p>
                            <dl class="mt-5 space-y-3 text-sm">
                                @foreach([
                                    'account_name' => 'Account name',
                                    'bank_name' => 'Bank',
                                    'branch' => 'Branch',
                                    'account_number' => 'Account number',
                                    'bank_code' => 'Bank code',
                                    'branch_code' => 'Branch code',
                                    'swift' => 'SWIFT',
                                    'currency' => 'Currency',
                                ] as $key => $label)
                                    @if(filled($bank[$key] ?? null))
                                        <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-4">
                                            <dt class="shrink-0 text-thc-text/65 sm:w-36">{{ $label }}</dt>
                                            <dd class="font-semibold text-thc-navy">{{ $bank[$key] }}</dd>
                                    </div>
                                    @endif
                                @endforeach
                            </dl>
                        </div>
                    @endif

                    @php $mp = $F['mpesa'] ?? []; @endphp
                    @if(filled($mp['paybill'] ?? null))
                        <div class="relative overflow-hidden rounded-2xl border border-emerald-700/15 bg-gradient-to-br from-emerald-50/90 via-white to-white p-6 shadow-sm sm:p-8">
                            <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-emerald-500/10" aria-hidden="true"></div>
                            <p class="relative text-xs font-bold uppercase tracking-[0.18em] text-emerald-800">{{ $mp['title'] ?? 'M-Pesa' }}</p>
                            <ol class="relative mt-5 list-decimal space-y-3 pl-5 text-sm leading-relaxed text-thc-text">
                                <li>Go to <strong class="text-thc-navy">Pay Bill</strong> on your M-Pesa menu.</li>
                                <li>
                                    Business number:
                                    <span class="rounded-md bg-emerald-100/80 px-2 py-0.5 font-mono font-semibold text-emerald-900">{{ $mp['paybill'] }}</span>
                                </li>
                                <li>
                                    Account number:
                                    <span class="rounded-md bg-white px-2 py-0.5 font-mono font-semibold text-thc-navy ring-1 ring-thc-navy/10">{{ $mp['account'] ?? $bank['account_number'] ?? '' }}</span>
                                </li>
                                <li>Enter amount, M-Pesa PIN, then confirm.</li>
                            </ol>
                            @if(filled($mp['confirmation_note'] ?? null))
                                <p class="relative mt-5 rounded-xl border border-emerald-700/10 bg-white/80 px-4 py-3 text-sm text-thc-text/90">
                                    {{ $mp['confirmation_note'] }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </section>
        </div>

        <x-schools.soc.about-sidebar :school="$school" :page="$page" />
    </div>
</x-layouts.public>
