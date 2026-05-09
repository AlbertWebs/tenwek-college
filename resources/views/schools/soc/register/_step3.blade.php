<section class="space-y-6" aria-labelledby="step3-title">
    <h2 id="step3-title" class="font-serif text-xl font-semibold text-thc-navy sm:text-2xl">Address & contact</h2>

    <div class="grid gap-5 sm:grid-cols-2">
        @include('schools.soc.register._textarea', ['name' => 'postal_address', 'label' => 'Postal address', 'rows' => 3])
        @include('schools.soc.register._text', ['name' => 'postal_code', 'label' => 'Postal code', 'autocomplete' => 'postal-code'])
        @include('schools.soc.register._text', ['name' => 'city_town', 'label' => 'City / town', 'autocomplete' => 'address-level2'])
        @include('schools.soc.register._country-select', ['name' => 'country_residence', 'label' => 'Country', 'countries' => $countries])
        @include('schools.soc.register._text', ['name' => 'telephone_home', 'label' => 'Telephone (home)', 'autocomplete' => 'tel', 'required' => false])
        @include('schools.soc.register._text', ['name' => 'telephone_office', 'label' => 'Telephone (office)', 'autocomplete' => 'tel', 'required' => false])
        @include('schools.soc.register._text', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email'])
        @include('schools.soc.register._text', ['name' => 'mobile', 'label' => 'Mobile (applicant)', 'autocomplete' => 'tel'])
    </div>

    <fieldset class="space-y-3 rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <legend class="px-1 text-sm font-bold uppercase tracking-[0.12em] text-thc-maroon">Application preference</legend>
        <p class="text-sm text-thc-text/80">Indicate whether this programme is your first, second, or third choice.</p>
        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="application_choice" value="first_choice" class="text-thc-maroon focus:ring-thc-royal" @checked(old('application_choice') === 'first_choice') required>
                First choice
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="application_choice" value="second_choice" class="text-thc-maroon focus:ring-thc-royal" @checked(old('application_choice') === 'second_choice')>
                Second choice
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="application_choice" value="third_choice" class="text-thc-maroon focus:ring-thc-royal" @checked(old('application_choice') === 'third_choice')>
                Third choice
            </label>
        </div>
        @error('application_choice')
            <p class="text-sm text-thc-maroon">{{ $message }}</p>
        @enderror
    </fieldset>
</section>
