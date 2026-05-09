<section class="space-y-6" aria-labelledby="step2-title">
    <h2 id="step2-title" class="font-serif text-xl font-semibold text-thc-navy sm:text-2xl">Education background & languages</h2>

    <div class="rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <p class="text-sm font-semibold text-thc-navy">Years of formal education</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            @include('schools.soc.register._text', ['name' => 'years_english', 'label' => 'English (years)', 'type' => 'number', 'autocomplete' => null])
            @include('schools.soc.register._text', ['name' => 'years_primary', 'label' => 'Primary (years)', 'type' => 'number', 'autocomplete' => null])
            @include('schools.soc.register._text', ['name' => 'years_secondary', 'label' => 'Secondary (years)', 'type' => 'number', 'autocomplete' => null])
            @include('schools.soc.register._text', ['name' => 'years_post_secondary', 'label' => 'Post-secondary (years)', 'type' => 'number', 'autocomplete' => null])
        </div>
    </div>

    @include('schools.soc.register._textarea', ['name' => 'languages_other', 'label' => 'Other languages spoken or written', 'rows' => 3])

    <fieldset class="space-y-3 rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <legend class="px-1 text-sm font-bold uppercase tracking-[0.12em] text-thc-maroon">Do you have any disability?</legend>
        <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="has_disability" value="yes" class="text-thc-maroon focus:ring-thc-royal" @checked(old('has_disability') === 'yes') required>
                Yes
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="has_disability" value="no" class="text-thc-maroon focus:ring-thc-royal" @checked(old('has_disability') === 'no')>
                No
            </label>
        </div>
        @error('has_disability')
            <p class="text-sm text-thc-maroon">{{ $message }}</p>
        @enderror
    </fieldset>

    @include('schools.soc.register._textarea', ['name' => 'disability_nature', 'label' => 'If yes, describe the nature of the disability', 'rows' => 2, 'required' => false])
</section>
