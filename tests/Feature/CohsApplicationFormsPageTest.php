<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CohsApplicationFormsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_forms_page_loads_with_seeded_forms(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get(route('schools.pages.show', ['school' => 'cohs', 'pageSlug' => 'application-forms']));

        $response->assertOk();
        $response->assertSee('Application', false);
        $response->assertSee('APPLICATION FORM-CLINICAL- REV 2025', false);
        $response->assertSee('KENYA REGISTERED COMMUNITY HEALTH NURSING (KRCHN)', false);
        $response->assertSee('Download', false);
    }
}
