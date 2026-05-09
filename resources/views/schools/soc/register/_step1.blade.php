<section class="space-y-6" aria-labelledby="step1-title">
    <h2 id="step1-title" class="font-serif text-xl font-semibold text-thc-navy sm:text-2xl">Application & personal information</h2>
    <p class="text-sm text-thc-text/85">Read each section carefully before continuing. Fields marked <span class="text-thc-maroon">*</span> are required.</p>

    <fieldset class="space-y-3 rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <legend class="px-1 text-sm font-bold uppercase tracking-[0.12em] text-thc-maroon">Application for</legend>
        <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="application_type" value="certificate" class="text-thc-maroon focus:ring-thc-royal" @checked(old('application_type') === 'certificate') required>
                Certificate
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="application_type" value="diploma" class="text-thc-maroon focus:ring-thc-royal" @checked(old('application_type') === 'diploma')>
                Diploma
            </label>
        </div>
        @error('application_type')
            <p class="text-sm text-thc-maroon">{{ $message }}</p>
        @enderror
    </fieldset>

    <div class="grid gap-5 sm:grid-cols-2">
        @include('schools.soc.register._text', ['name' => 'last_name', 'label' => 'Last (family) name', 'autocomplete' => 'family-name'])
        @include('schools.soc.register._text', ['name' => 'middle_name', 'label' => 'Middle name', 'autocomplete' => 'additional-name'])
        @include('schools.soc.register._text', ['name' => 'first_name', 'label' => 'First name', 'autocomplete' => 'given-name'])
        @include('schools.soc.register._text', ['name' => 'date_of_birth', 'label' => 'Date of birth', 'type' => 'date', 'autocomplete' => 'bday'])
        @include('schools.soc.register._text', ['name' => 'citizenship', 'label' => 'Citizenship', 'autocomplete' => null])
        @include('schools.soc.register._country-select', ['name' => 'country_of_birth', 'label' => 'Country of birth', 'countries' => $countries])
        @include('schools.soc.register._text', ['name' => 'county_of_birth', 'label' => 'County / region of birth', 'autocomplete' => null])
        @include('schools.soc.register._text', ['name' => 'passport_or_id', 'label' => 'Passport or national ID number', 'autocomplete' => null])
    </div>

    <fieldset class="space-y-3 rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <legend class="px-1 text-sm font-bold uppercase tracking-[0.12em] text-thc-maroon">Sex</legend>
        <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="sex" value="female" class="text-thc-maroon focus:ring-thc-royal" @checked(old('sex') === 'female') required>
                Female
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="sex" value="male" class="text-thc-maroon focus:ring-thc-royal" @checked(old('sex') === 'male')>
                Male
            </label>
        </div>
        @error('sex')
            <p class="text-sm text-thc-maroon">{{ $message }}</p>
        @enderror
    </fieldset>
</section>
