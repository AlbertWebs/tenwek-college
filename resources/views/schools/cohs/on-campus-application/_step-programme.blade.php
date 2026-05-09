<div class="space-y-8">
    <h3 class="border-b border-thc-navy/10 pb-2 font-serif text-xl font-semibold text-thc-navy">Academic programme / course</h3>

    <div>
        <label for="programme" class="mb-1.5 block text-sm font-medium text-thc-navy">Programme / course applying for <span class="text-red-600">*</span></label>
        <select id="programme" name="programme" required class="cohs-app-input">
            <option value="">Select programme</option>
            @foreach($programmes as $val => $label)
                <option value="{{ $val }}" @selected(old('programme') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <span class="mb-1.5 block text-sm font-medium text-thc-navy">Preferred mode of study <span class="text-red-600">*</span></span>
        <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
            @foreach($studyModes as $val => $label)
                <label class="inline-flex items-center gap-2 rounded-lg border border-thc-navy/10 bg-white px-4 py-3 text-sm text-thc-text shadow-sm">
                    <input type="radio" name="study_mode" value="{{ $val }}" class="text-thc-royal" @checked(old('study_mode', 'full_time') === $val) @if($loop->first) required @endif>
                    {{ $label }}
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <label for="campus" class="mb-1.5 block text-sm font-medium text-thc-navy">Campus <span class="text-red-600">*</span></label>
        <select id="campus" name="campus" required class="cohs-app-input">
            <option value="">Select campus</option>
            @foreach($campuses as $val => $label)
                <option value="{{ $val }}" @selected(old('campus') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>
