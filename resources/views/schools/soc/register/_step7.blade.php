@php
    $fc = 'mt-1 block w-full text-sm text-thc-text file:mr-4 file:rounded-lg file:border-0 file:bg-thc-royal file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-thc-navy';
@endphp

<section class="space-y-6" aria-labelledby="step7-title">
    <h2 id="step7-title" class="font-serif text-xl font-semibold text-thc-navy sm:text-2xl">Upload documents & submit</h2>
    <p class="text-sm text-thc-text/85">PDF or images (JPG, PNG, WebP) unless noted. Maximum size per file is shown below.</p>

    <div class="space-y-5 rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <div>
            <label for="bank_slip" class="block text-sm font-semibold text-thc-navy">Bank slip for application fee (KES 1,000) <span class="text-thc-maroon">*</span></label>
            <input type="file" name="bank_slip" id="bank_slip" accept=".pdf,.jpg,.jpeg,.png,.webp" required class="{{ $fc }} @error('bank_slip') ring-2 ring-thc-maroon/40 @enderror">
            @error('bank_slip')
                <p class="mt-1 text-sm text-thc-maroon">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="photograph" class="block text-sm font-semibold text-thc-navy">Recent colour passport photograph <span class="text-thc-maroon">*</span></label>
            <input type="file" name="photograph" id="photograph" accept=".jpg,.jpeg,.png,.webp" required class="{{ $fc }} @error('photograph') ring-2 ring-thc-maroon/40 @enderror">
            @error('photograph')
                <p class="mt-1 text-sm text-thc-maroon">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="certificates" class="block text-sm font-semibold text-thc-navy">Academic & professional certificates (A-level, O-level certificate, or transcript required) <span class="text-thc-maroon">*</span></label>
            <input type="file" name="certificates" id="certificates" accept=".pdf,.jpg,.jpeg,.png,.webp" required class="{{ $fc }} @error('certificates') ring-2 ring-thc-maroon/40 @enderror">
            @error('certificates')
                <p class="mt-1 text-sm text-thc-maroon">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <label class="flex items-start gap-3 rounded-2xl border border-thc-navy/10 bg-white p-5 shadow-sm">
        <input type="checkbox" name="agree_declaration" value="1" class="mt-1 text-thc-maroon focus:ring-thc-royal" @checked(old('agree_declaration')) required>
        <span class="text-sm leading-relaxed text-thc-text/90">
            I certify that all information given is true and accurate to the best of my knowledge. I understand that false information may lead to dismissal if admitted.
        </span>
    </label>
    @error('agree_declaration')
        <p class="text-sm text-thc-maroon">{{ $message }}</p>
    @enderror

    <p class="text-xs text-thc-text/65">By submitting this form you consent to the college processing your data for admissions purposes.</p>
</section>
