<div class="space-y-8">
    <h3 class="border-b border-thc-navy/10 pb-2 font-serif text-xl font-semibold text-thc-navy">Personal information</h3>

    <div class="grid gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2 sm:grid sm:grid-cols-3 sm:gap-5">
            <div>
                <label for="first_name" class="mb-1.5 block text-sm font-medium text-thc-navy">First name <span class="text-red-600">*</span></label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required maxlength="120" autocomplete="given-name" class="cohs-app-input">
            </div>
            <div>
                <label for="middle_name" class="mb-1.5 block text-sm font-medium text-thc-navy">Middle name</label>
                <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}" maxlength="120" autocomplete="additional-name" class="cohs-app-input">
            </div>
            <div>
                <label for="last_name" class="mb-1.5 block text-sm font-medium text-thc-navy">Surname / last name <span class="text-red-600">*</span></label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required maxlength="120" autocomplete="family-name" class="cohs-app-input">
            </div>
        </div>

        <div>
            <label for="date_of_birth" class="mb-1.5 block text-sm font-medium text-thc-navy">Date of birth</label>
            <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="cohs-app-input">
        </div>
        <div>
            <label for="place_of_birth" class="mb-1.5 block text-sm font-medium text-thc-navy">Place of birth</label>
            <input id="place_of_birth" type="text" name="place_of_birth" value="{{ old('place_of_birth') }}" maxlength="200" class="cohs-app-input">
        </div>

        <div>
            <span class="mb-1.5 block text-sm font-medium text-thc-navy">Gender <span class="text-red-600">*</span></span>
            <div class="mt-2 flex flex-wrap gap-4">
                <label class="inline-flex items-center gap-2 text-sm text-thc-text">
                    <input type="radio" name="gender" value="female" class="text-thc-royal" @checked(old('gender') === 'female') required>
                    Female
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-thc-text">
                    <input type="radio" name="gender" value="male" class="text-thc-royal" @checked(old('gender') === 'male')>
                    Male
                </label>
            </div>
        </div>

        <div class="sm:col-span-2">
            <label for="country_of_residence" class="mb-1.5 block text-sm font-medium text-thc-navy">Country of residence <span class="text-red-600">*</span></label>
            <select id="country_of_residence" name="country_of_residence" required class="cohs-app-input">
                <option value="">Select country</option>
                @foreach($countries as $c)
                    <option value="{{ $c }}" @selected(old('country_of_residence') === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="birth_cert_no" class="mb-1.5 block text-sm font-medium text-thc-navy">Birth certificate / ID no. <span class="text-red-600">*</span></label>
            <input id="birth_cert_no" type="text" name="birth_cert_no" value="{{ old('birth_cert_no') }}" required maxlength="64" autocomplete="off" class="cohs-app-input">
        </div>
        <div>
            <label for="birth_cert_no_confirmation" class="mb-1.5 block text-sm font-medium text-thc-navy">Confirm birth certificate / ID no. <span class="text-red-600">*</span></label>
            <input id="birth_cert_no_confirmation" type="text" name="birth_cert_no_confirmation" value="{{ old('birth_cert_no_confirmation') }}" required maxlength="64" autocomplete="off" class="cohs-app-input">
        </div>

        <div>
            <label for="mobile" class="mb-1.5 block text-sm font-medium text-thc-navy">Mobile no. <span class="text-red-600">*</span></label>
            <input id="mobile" type="text" name="mobile" value="{{ old('mobile') }}" required maxlength="40" autocomplete="tel" class="cohs-app-input">
        </div>
        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-thc-navy">Email <span class="text-red-600">*</span></label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" class="cohs-app-input">
        </div>

        <div>
            <label for="marital_status" class="mb-1.5 block text-sm font-medium text-thc-navy">Marital status <span class="text-red-600">*</span></label>
            <select id="marital_status" name="marital_status" required class="cohs-app-input">
                <option value="">Select</option>
                @foreach(['married' => 'Married', 'single' => 'Single', 'widowed' => 'Widowed', 'divorced' => 'Divorced'] as $val => $lab)
                    <option value="{{ $val }}" @selected(old('marital_status') === $val)>{{ $lab }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-2">
            <label for="postal_address" class="mb-1.5 block text-sm font-medium text-thc-navy">Postal address <span class="text-red-600">*</span></label>
            <textarea id="postal_address" name="postal_address" rows="2" required maxlength="500" class="cohs-app-input">{{ old('postal_address') }}</textarea>
        </div>

        <div>
            <label for="postal_code" class="mb-1.5 block text-sm font-medium text-thc-navy">Postal code <span class="text-red-600">*</span></label>
            <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code') }}" required maxlength="32" class="cohs-app-input">
        </div>
        <div>
            <label for="county" class="mb-1.5 block text-sm font-medium text-thc-navy">County <span class="text-red-600">*</span></label>
            <select id="county" name="county" required class="cohs-app-input">
                <option value="">Select county</option>
                @foreach($counties as $c)
                    <option value="{{ $c }}" @selected(old('county') === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
