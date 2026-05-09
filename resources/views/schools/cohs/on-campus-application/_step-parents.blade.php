<div class="space-y-8">
    <h3 class="border-b border-thc-navy/10 pb-2 font-serif text-xl font-semibold text-thc-navy">Parent / guardian information</h3>
    <p class="text-sm text-thc-text/80">Add at least one parent or guardian. You may add up to five.</p>

    @for($i = 0; $i < 5; $i++)
        <fieldset class="rounded-xl border border-thc-navy/10 bg-thc-navy/[0.02] p-5 sm:p-6">
            <legend class="px-1 text-sm font-semibold text-thc-navy">Parent / guardian {{ $i + 1 }} @if($i === 0)<span class="text-red-600">*</span>@endif</legend>
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div class="sm:col-span-3">
                    <label for="parent_{{ $i }}_name" class="mb-1.5 block text-sm font-medium text-thc-navy">Full name @if($i === 0)<span class="text-red-600">*</span>@endif</label>
                    <input
                        id="parent_{{ $i }}_name"
                        type="text"
                        name="parents[{{ $i }}][full_name]"
                        value="{{ old('parents.'.$i.'.full_name') }}"
                        @if($i === 0) required @endif
                        maxlength="200"
                        class="cohs-app-input"
                    >
                </div>
                <div>
                    <label for="parent_{{ $i }}_relation" class="mb-1.5 block text-sm font-medium text-thc-navy">Relation to applicant @if($i === 0)<span class="text-red-600">*</span>@endif</label>
                    <input
                        id="parent_{{ $i }}_relation"
                        type="text"
                        name="parents[{{ $i }}][relation]"
                        value="{{ old('parents.'.$i.'.relation') }}"
                        @if($i === 0) required @endif
                        maxlength="120"
                        class="cohs-app-input"
                    >
                </div>
                <div class="sm:col-span-2">
                    <label for="parent_{{ $i }}_mobile" class="mb-1.5 block text-sm font-medium text-thc-navy">Mobile number @if($i === 0)<span class="text-red-600">*</span>@endif</label>
                    <input
                        id="parent_{{ $i }}_mobile"
                        type="text"
                        name="parents[{{ $i }}][mobile]"
                        value="{{ old('parents.'.$i.'.mobile') }}"
                        @if($i === 0) required @endif
                        maxlength="40"
                        class="cohs-app-input"
                    >
                </div>
            </div>
        </fieldset>
    @endfor
</div>
