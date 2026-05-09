<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocAdmissionsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_soc_admissions_renders_requirements_and_resources(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/admissions');

        $response->assertOk();
        $response->assertSee('Admissions', false);
        $response->assertSee('Download school brochure', false);
        $response->assertSee('Application form', false);
        $response->assertSee('Online application', false);
        $response->assertSee('Admission requirements', false);
        $response->assertSee('Certificate in Chaplaincy', false);
        $response->assertSee('KCSE D', false);
        $response->assertSee('Diploma in Chaplaincy', false);
        $response->assertSee('January', false);
        $response->assertSee('May', false);
        $response->assertSee('September', false);
        $response->assertSee('/downloads?school=soc', false);
    }
}
