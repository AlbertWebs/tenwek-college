<div class="space-y-8">
    <h3 class="border-b border-thc-navy/10 pb-2 font-serif text-xl font-semibold text-thc-navy">Education background</h3>
    <p class="text-sm text-thc-text/80">Add at least one education record. Transcripts must be <strong class="font-semibold text-thc-navy">PDF</strong>, max <strong class="font-semibold text-thc-navy">5&nbsp;MB</strong> each.</p>

    @for($i = 0; $i < 5; $i++)
        <fieldset class="rounded-xl border border-thc-navy/10 p-5 sm:p-6">
            <legend class="px-1 text-sm font-semibold text-thc-navy">Education {{ $i + 1 }} @if($i === 0)<span class="text-red-600">*</span>@endif</legend>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="edu_{{ $i }}_inst" class="mb-1.5 block text-sm font-medium text-thc-navy">Institution name @if($i === 0)<span class="text-red-600">*</span>@endif</label>
                    <input
                        id="edu_{{ $i }}_inst"
                        type="text"
                        name="education[{{ $i }}][institution_name]"
                        value="{{ old('education.'.$i.'.institution_name') }}"
                        @if($i === 0) required @endif
                        maxlength="200"
                        class="cohs-app-input"
                    >
                </div>
                <div>
                    <label for="edu_{{ $i }}_start" class="mb-1.5 block text-sm font-medium text-thc-navy">Start year @if($i === 0)<span class="text-red-600">*</span>@endif</label>
                    <input
                        id="edu_{{ $i }}_start"
                        type="number"
                        name="education[{{ $i }}][start_year]"
                        value="{{ old('education.'.$i.'.start_year') }}"
                        @if($i === 0) required @endif
                        min="1950"
                        max="{{ date('Y') }}"
                        class="cohs-app-input"
                    >
                </div>
                <div>
                    <label for="edu_{{ $i }}_end" class="mb-1.5 block text-sm font-medium text-thc-navy">End year @if($i === 0)<span class="text-red-600">*</span>@endif</label>
                    <input
                        id="edu_{{ $i }}_end"
                        type="number"
                        name="education[{{ $i }}][end_year]"
                        value="{{ old('education.'.$i.'.end_year') }}"
                        @if($i === 0) required @endif
                        min="1950"
                        max="{{ date('Y') }}"
                        class="cohs-app-input"
                    >
                </div>
                <div class="sm:col-span-2">
                    <label for="edu_{{ $i }}_award" class="mb-1.5 block text-sm font-medium text-thc-navy">Certificate / diploma / degree attained @if($i === 0)<span class="text-red-600">*</span>@endif</label>
                    <input
                        id="edu_{{ $i }}_award"
                        type="text"
                        name="education[{{ $i }}][award]"
                        value="{{ old('education.'.$i.'.award') }}"
                        @if($i === 0) required @endif
                        maxlength="200"
                        class="cohs-app-input"
                    >
                </div>
                <div class="sm:col-span-2">
                    <label for="edu_{{ $i }}_transcript" class="mb-1.5 block text-sm font-medium text-thc-navy">Certificate / transcript (PDF) @if($i === 0)<span class="text-red-600">*</span>@endif</label>
                    <input
                        id="edu_{{ $i }}_transcript"
                        type="file"
                        name="education[{{ $i }}][transcript]"
                        accept=".pdf,application/pdf"
                        @if($i === 0) required @endif
                        class="cohs-app-file"
                    >
                </div>
            </div>
        </fieldset>
    @endfor
</div>
