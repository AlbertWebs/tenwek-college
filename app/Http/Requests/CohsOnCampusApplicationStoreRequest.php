<?php

namespace App\Http\Requests;

use App\Support\CohsHealthSciencesApplicationOptions;
use App\Support\CohsKenyaCounties;
use App\Support\SocRegistrationCountries;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CohsOnCampusApplicationStoreRequest extends FormRequest
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
        $counties = CohsKenyaCounties::all();
        $rules = [
            'fax' => ['prohibited'],

            'parents' => ['required', 'array'],
            'education' => ['required', 'array'],

            'first_name' => ['required', 'string', 'max:120'],
            'middle_name' => ['nullable', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'date_of_birth' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'place_of_birth' => ['nullable', 'string', 'max:200'],
            'gender' => ['required', Rule::in(['female', 'male'])],
            'country_of_residence' => ['required', Rule::in($countries)],
            'birth_cert_no' => ['required', 'string', 'max:64'],
            'birth_cert_no_confirmation' => ['required', 'same:birth_cert_no'],
            'mobile' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'marital_status' => ['required', Rule::in(['married', 'single', 'widowed', 'divorced'])],
            'postal_address' => ['required', 'string', 'max:500'],
            'postal_code' => ['required', 'string', 'max:32'],
            'county' => ['required', Rule::in($counties)],

            'programme' => ['required', Rule::in(CohsHealthSciencesApplicationOptions::programmeValues())],
            'study_mode' => ['required', Rule::in(CohsHealthSciencesApplicationOptions::studyModeValues())],
            'campus' => ['required', Rule::in(CohsHealthSciencesApplicationOptions::campusValues())],

            'additional_information' => ['nullable', 'string', 'max:10000'],

            'proof_of_payment' => ['required', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,webp'],
            'profile_picture' => ['required', 'file', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ];

        $rules['parents.0.full_name'] = ['required', 'string', 'max:200'];
        $rules['parents.0.relation'] = ['required', 'string', 'max:120'];
        $rules['parents.0.mobile'] = ['required', 'string', 'max:40'];

        for ($i = 1; $i <= 4; $i++) {
            $rules['parents.'.$i.'.full_name'] = ['nullable', 'string', 'max:200'];
            $rules['parents.'.$i.'.relation'] = ['nullable', 'required_with:parents.'.$i.'.full_name', 'string', 'max:120'];
            $rules['parents.'.$i.'.mobile'] = ['nullable', 'required_with:parents.'.$i.'.full_name', 'string', 'max:40'];
        }

        $rules['education.0.institution_name'] = ['required', 'string', 'max:200'];
        $rules['education.0.start_year'] = ['required', 'integer', 'min:1950', 'max:'.(int) date('Y')];
        $rules['education.0.end_year'] = ['required', 'integer', 'min:1950', 'max:'.(int) date('Y'), 'gte:education.0.start_year'];
        $rules['education.0.award'] = ['required', 'string', 'max:200'];
        $rules['education.0.transcript'] = ['required', 'file', 'mimes:pdf', 'max:5120'];

        for ($i = 1; $i <= 4; $i++) {
            $rules['education.'.$i.'.institution_name'] = ['nullable', 'string', 'max:200'];
            $rules['education.'.$i.'.start_year'] = ['nullable', 'required_with:education.'.$i.'.institution_name', 'integer', 'min:1950', 'max:'.(int) date('Y')];
            $rules['education.'.$i.'.end_year'] = ['nullable', 'required_with:education.'.$i.'.institution_name', 'integer', 'min:1950', 'max:'.(int) date('Y'), 'gte:education.'.$i.'.start_year'];
            $rules['education.'.$i.'.award'] = ['nullable', 'required_with:education.'.$i.'.institution_name', 'string', 'max:200'];
            $rules['education.'.$i.'.transcript'] = ['nullable', 'required_with:education.'.$i.'.institution_name', 'file', 'mimes:pdf', 'max:5120'];
        }

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function (Validator $v): void {
            foreach (range(0, 4) as $i) {
                $inst = $this->input('education.'.$i.'.institution_name');
                if (filled($inst)) {
                    $s = $this->input('education.'.$i.'.start_year');
                    $e = $this->input('education.'.$i.'.end_year');
                    if ($s !== null && $e !== null && (int) $e < (int) $s) {
                        $v->errors()->add('education.'.$i.'.end_year', 'The end year must be greater than or equal to the start year.');
                    }
                }
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'birth_cert_no' => 'birth certificate or ID number',
            'birth_cert_no_confirmation' => 'confirmation of birth certificate or ID number',
            'proof_of_payment' => 'proof of payment',
            'profile_picture' => 'profile picture',
            'education.0.transcript' => 'academic transcript (first education entry)',
        ];
    }
}
