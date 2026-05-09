<section class="space-y-8" aria-labelledby="step5-title">
    <h2 id="step5-title" class="font-serif text-xl font-semibold text-thc-navy sm:text-2xl">Religious affiliation & education history</h2>

    <fieldset class="space-y-4 rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <legend class="px-1 text-sm font-bold uppercase tracking-[0.12em] text-thc-maroon">Religious affiliation</legend>
        <div class="grid gap-3 sm:grid-cols-2">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="denomination" value="protestant" class="text-thc-maroon focus:ring-thc-royal" @checked(old('denomination') === 'protestant') required>
                Protestant
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="denomination" value="roman_catholic" class="text-thc-maroon focus:ring-thc-royal" @checked(old('denomination') === 'roman_catholic')>
                Roman Catholic
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="denomination" value="hindu" class="text-thc-maroon focus:ring-thc-royal" @checked(old('denomination') === 'hindu')>
                Hindu
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="denomination" value="muslim" class="text-thc-maroon focus:ring-thc-royal" @checked(old('denomination') === 'muslim')>
                Muslim
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="denomination" value="other" class="text-thc-maroon focus:ring-thc-royal" @checked(old('denomination') === 'other')>
                Other
            </label>
        </div>
        @error('denomination')
            <p class="text-sm text-thc-maroon">{{ $message }}</p>
        @enderror
        @include('schools.soc.register._text', ['name' => 'denomination_other', 'label' => 'Other denomination (specify)', 'autocomplete' => null, 'required' => false])
    </fieldset>

    <fieldset class="space-y-3 rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <legend class="px-1 text-sm font-bold uppercase tracking-[0.12em] text-thc-maroon">Practising pastors</legend>
        <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="pastors_status" value="ordained" class="text-thc-maroon focus:ring-thc-royal" @checked(old('pastors_status') === 'ordained') required>
                Ordained
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="pastors_status" value="to_be_ordained" class="text-thc-maroon focus:ring-thc-royal" @checked(old('pastors_status') === 'to_be_ordained')>
                To be ordained
            </label>
        </div>
        @error('pastors_status')
            <p class="text-sm text-thc-maroon">{{ $message }}</p>
        @enderror
    </fieldset>

    @foreach([1, 2, 3] as $n)
        <div class="rounded-2xl border border-thc-navy/10 bg-thc-navy/[0.02] p-5 shadow-sm">
            <h3 class="font-semibold text-thc-navy">Prior education, institution {{ $n }} @if($n === 1)<span class="text-thc-maroon">*</span>@else<span class="text-sm font-normal text-thc-text/65">(optional)</span>@endif</h3>
            <p class="mt-1 text-xs text-thc-text/70">Do not list primary school. Institution 1 is required; add more if applicable.</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                @include('schools.soc.register._text', ['name' => "institution_{$n}_name", 'label' => 'Name of institution', 'autocomplete' => null, 'required' => $n === 1])
                @include('schools.soc.register._text', ['name' => "institution_{$n}_area", 'label' => 'Area of study', 'autocomplete' => null, 'required' => $n === 1])
                @include('schools.soc.register._text', ['name' => "institution_{$n}_from", 'label' => 'Duration from', 'type' => 'date', 'autocomplete' => null, 'required' => $n === 1])
                @include('schools.soc.register._text', ['name' => "institution_{$n}_to", 'label' => 'Duration to', 'type' => 'date', 'autocomplete' => null, 'required' => $n === 1])
                @include('schools.soc.register._text', ['name' => "institution_{$n}_award", 'label' => 'Certificate, diploma, or degree attained', 'autocomplete' => null, 'required' => $n === 1])
            </div>
        </div>
    @endforeach
</section>
