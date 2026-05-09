<section class="space-y-8" aria-labelledby="step6-title">
    <h2 id="step6-title" class="font-serif text-xl font-semibold text-thc-navy sm:text-2xl">Fees, study mode & how you heard about us</h2>

    <div class="rounded-2xl border border-thc-navy/10 bg-white p-6 shadow-sm">
        <h3 class="font-serif text-lg font-semibold text-thc-navy">Fee structure (reference)</h3>
        <p class="mt-2 text-sm text-thc-text/85">First-time admission costs for Kenyan students typically include:</p>
        <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-thc-text/90">
            <li>Application fee: <strong>KES 1,000</strong> (non-refundable)</li>
            <li>Student ID: <strong>KES 500</strong></li>
            <li>Caution money: <strong>KES 3,000</strong></li>
            <li>Tuition per trimester: Diploma about <strong>KES 27,000 to 29,000</strong>; Certificate about <strong>KES 16,500 to 17,500</strong> (confirm current figures on the <a href="{{ route('schools.pages.show', [$school, 'fee']) }}" class="font-semibold text-thc-royal hover:underline">fees page</a>).</li>
        </ol>
        <p class="mt-4 text-sm text-thc-text/85">On-campus accommodation is optional; if taken, budget roughly <strong>KES 2,000 per month</strong> (legacy figure from the former site; confirm with the registrar).</p>
    </div>

    <fieldset class="space-y-3 rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <legend class="px-1 text-sm font-bold uppercase tracking-[0.12em] text-thc-maroon">Preferred mode of study</legend>
        <div class="flex flex-col gap-3">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="study_mode" value="full_time" class="text-thc-maroon focus:ring-thc-royal" @checked(old('study_mode') === 'full_time') required>
                Full time
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="study_mode" value="school_holidays" class="text-thc-maroon focus:ring-thc-royal" @checked(old('study_mode') === 'school_holidays')>
                School holidays
            </label>
            <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                <input type="radio" name="study_mode" value="odel" class="text-thc-maroon focus:ring-thc-royal" @checked(old('study_mode') === 'odel')>
                Online / distance (ODEL)
            </label>
        </div>
        @error('study_mode')
            <p class="text-sm text-thc-maroon">{{ $message }}</p>
        @enderror
    </fieldset>

    <fieldset class="space-y-3 rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <legend class="px-1 text-sm font-bold uppercase tracking-[0.12em] text-thc-maroon">How did you learn about Tenwek Hospital College, School of Chaplaincy?</legend>
        <div class="grid gap-2 sm:grid-cols-2">
            @foreach([
                'newspaper' => 'Newspaper',
                'family_friend' => 'Family / friend',
                'church' => 'Church announcement',
                'prospectus' => 'College prospectus',
                'tv' => 'Television',
                'website' => 'Website',
                'radio' => 'Radio',
                'exhibition' => 'Exhibition',
                'other' => 'Other',
            ] as $val => $lab)
                <label class="inline-flex items-center gap-2 text-sm font-medium text-thc-navy">
                    <input type="radio" name="heard_how" value="{{ $val }}" class="text-thc-maroon focus:ring-thc-royal" @checked(old('heard_how') === $val) @if($loop->first) required @endif>
                    {{ $lab }}
                </label>
            @endforeach
        </div>
        @error('heard_how')
            <p class="text-sm text-thc-maroon">{{ $message }}</p>
        @enderror
        @include('schools.soc.register._text', ['name' => 'heard_other', 'label' => 'If other, specify', 'autocomplete' => null, 'required' => false])
    </fieldset>

    <div class="rounded-2xl border border-dashed border-thc-navy/20 bg-thc-royal/[0.04] p-5">
        <p class="text-sm font-semibold text-thc-navy">Referred by a current student? (optional)</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            @include('schools.soc.register._text', ['name' => 'referral_student_name', 'label' => "Student's name", 'autocomplete' => null, 'required' => false])
            @include('schools.soc.register._text', ['name' => 'referral_admission_number', 'label' => 'Admission number', 'autocomplete' => null, 'required' => false])
            @include('schools.soc.register._text', ['name' => 'referral_contact', 'label' => 'Contact details', 'autocomplete' => null, 'required' => false])
        </div>
    </div>

    @include('schools.soc.register._textarea', ['name' => 'why_tenwek', 'label' => 'Why do you wish to study at Tenwek Hospital College, School of Chaplaincy? (brief account)', 'rows' => 5])
</section>
