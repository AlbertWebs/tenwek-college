<?php

namespace Tests\Feature;

use App\Models\FormSubmission;
use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SocRegistrationWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_renders_wizard(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/register');

        $response->assertOk();
        $response->assertSee('Online application', false);
        $response->assertSee('Sign up for School of Chaplaincy', false);
        $response->assertSee('Submit application', false);
    }

    public function test_register_stores_submission_with_uploads(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        Storage::fake('local');

        $base = [
            'application_type' => 'certificate',
            'last_name' => 'Otieno',
            'middle_name' => 'A',
            'first_name' => 'Mary',
            'date_of_birth' => '1998-05-15',
            'citizenship' => 'Kenyan',
            'country_of_birth' => 'Kenya',
            'county_of_birth' => 'Nairobi',
            'passport_or_id' => '12345678',
            'sex' => 'female',
            'years_english' => 12,
            'years_primary' => 8,
            'years_secondary' => 4,
            'years_post_secondary' => 0,
            'languages_other' => 'Swahili',
            'has_disability' => 'no',
            'postal_address' => 'P.O. Box 1',
            'postal_code' => '00100',
            'city_town' => 'Nairobi',
            'country_residence' => 'Kenya',
            'email' => 'mary@example.com',
            'mobile' => '+254700000000',
            'application_choice' => 'first_choice',
            'parent_name' => 'Parent Name',
            'parent_relation' => 'Mother',
            'parent_address' => 'P.O. Box 2',
            'parent_telephone' => '+254711000000',
            'parent_email' => 'parent@example.com',
            'parent_mobile' => '+254722000000',
            'denomination' => 'protestant',
            'pastors_status' => 'to_be_ordained',
            'institution_1_name' => 'Some High School',
            'institution_1_area' => 'Arts',
            'institution_1_from' => '2014-01-01',
            'institution_1_to' => '2017-12-01',
            'institution_1_award' => 'KCSE',
            'study_mode' => 'full_time',
            'heard_how' => 'website',
            'why_tenwek' => 'I am called to healthcare chaplaincy.',
            'agree_declaration' => '1',
            'bank_slip' => UploadedFile::fake()->create('slip.pdf', 120, 'application/pdf'),
            'photograph' => UploadedFile::fake()->image('photo.jpg', 320, 400),
            'certificates' => UploadedFile::fake()->create('certs.pdf', 200, 'application/pdf'),
        ];

        $response = $this->post('/soc/register', $base);

        $response->assertRedirect(route('soc.register'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('form_submissions', [
            'form_key' => 'soc_chaplaincy_registration',
        ]);

        $submission = FormSubmission::query()->where('form_key', 'soc_chaplaincy_registration')->first();
        $this->assertNotNull($submission);
        $this->assertSame('Mary', $submission->payload['first_name'] ?? null);
        $this->assertNotEmpty($submission->payload['bank_slip_path'] ?? null);
        Storage::disk('local')->assertExists($submission->payload['bank_slip_path']);
    }
}
