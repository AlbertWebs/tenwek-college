<?php

namespace App\Http\Requests;

use App\Support\SocRegistrationCountries;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocRegistrationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $countries = SocRegistrationCountries::all();

        return [
            'fax' => ['prohibited'],

            'application_type' => ['required', Rule::in(['certificate', 'diploma'])],
            'last_name' => ['required', 'string', 'max:120'],
            'middle_name' => ['required', 'string', 'max:120'],
            'first_name' => ['required', 'string', 'max:120'],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'citizenship' => ['required', 'string', 'max:120'],
            'country_of_birth' => ['required', Rule::in($countries)],
            'county_of_birth' => ['required', 'string', 'max:120'],
            'passport_or_id' => ['required', 'string', 'max:64'],
            'sex' => ['required', Rule::in(['female', 'male'])],

            'years_english' => ['required', 'integer', 'min:0', 'max:40'],
            'years_primary' => ['required', 'integer', 'min:0', 'max:40'],
            'years_secondary' => ['required', 'integer', 'min:0', 'max:40'],
            'years_post_secondary' => ['required', 'integer', 'min:0', 'max:40'],
            'languages_other' => ['required', 'string', 'max:500'],
            'has_disability' => ['required', Rule::in(['yes', 'no'])],
            'disability_nature' => ['nullable', 'required_if:has_disability,yes', 'string', 'max:500'],

            'postal_address' => ['required', 'string', 'max:500'],
            'postal_code' => ['required', 'string', 'max:32'],
            'city_town' => ['required', 'string', 'max:120'],
            'country_residence' => ['required', Rule::in($countries)],
            'telephone_home' => ['nullable', 'string', 'max:40'],
            'telephone_office' => ['nullable', 'string', 'max:40'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'mobile' => ['required', 'string', 'max:40'],
            'application_choice' => ['required', Rule::in(['first_choice', 'second_choice', 'third_choice'])],

            'parent_name' => ['required', 'string', 'max:200'],
            'parent_relation' => ['required', 'string', 'max:120'],
            'parent_address' => ['required', 'string', 'max:500'],
            'parent_telephone' => ['required', 'string', 'max:40'],
            'parent_email' => ['required', 'email:rfc', 'max:255'],
            'parent_mobile' => ['required', 'string', 'max:40'],

            'denomination' => ['required', Rule::in(['protestant', 'roman_catholic', 'hindu', 'muslim', 'other'])],
            'denomination_other' => ['nullable', 'required_if:denomination,other', 'string', 'max:200'],
            'pastors_status' => ['required', Rule::in(['ordained', 'to_be_ordained'])],

            'institution_1_name' => ['required', 'string', 'max:200'],
            'institution_1_area' => ['required', 'string', 'max:200'],
            'institution_1_from' => ['required', 'date'],
            'institution_1_to' => ['required', 'date', 'after_or_equal:institution_1_from'],
            'institution_1_award' => ['required', 'string', 'max:200'],
            'institution_2_name' => ['nullable', 'string', 'max:200'],
            'institution_2_area' => ['nullable', 'required_with:institution_2_name', 'string', 'max:200'],
            'institution_2_from' => ['nullable', 'required_with:institution_2_name', 'date'],
            'institution_2_to' => ['nullable', 'required_with:institution_2_name', 'date', 'after_or_equal:institution_2_from'],
            'institution_2_award' => ['nullable', 'required_with:institution_2_name', 'string', 'max:200'],
            'institution_3_name' => ['nullable', 'string', 'max:200'],
            'institution_3_area' => ['nullable', 'required_with:institution_3_name', 'string', 'max:200'],
            'institution_3_from' => ['nullable', 'required_with:institution_3_name', 'date'],
            'institution_3_to' => ['nullable', 'required_with:institution_3_name', 'date', 'after_or_equal:institution_3_from'],
            'institution_3_award' => ['nullable', 'required_with:institution_3_name', 'string', 'max:200'],

            'study_mode' => ['required', Rule::in(['full_time', 'school_holidays', 'odel'])],
            'heard_how' => ['required', Rule::in(['newspaper', 'family_friend', 'church', 'prospectus', 'tv', 'website', 'radio', 'exhibition', 'other'])],
            'heard_other' => ['nullable', 'required_if:heard_how,other', 'string', 'max:200'],
            'referral_student_name' => ['nullable', 'string', 'max:200'],
            'referral_admission_number' => ['nullable', 'string', 'max:80'],
            'referral_contact' => ['nullable', 'string', 'max:200'],
            'why_tenwek' => ['required', 'string', 'max:8000'],

            'agree_declaration' => ['accepted'],

            'bank_slip' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp'],
            'photograph' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,webp'],
            'certificates' => ['required', 'file', 'max:15360', 'mimes:pdf,jpg,jpeg,png,webp'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'application_type' => 'programme',
            'passport_or_id' => 'passport or ID number',
            'languages_other' => 'languages',
            'country_residence' => 'country',
            'institution_1_award' => 'certificate, diploma, or degree (institution 1)',
            'bank_slip' => 'bank slip for application fee',
            'photograph' => 'passport photograph',
            'certificates' => 'academic certificates',
        ];
    }
}
