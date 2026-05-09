<?php

namespace Tests\Feature;

use App\Models\School;
use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CohsContactPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_cohs_contact_page_renders_with_office_details(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get(route('schools.pages.show', ['school' => 'cohs', 'pageSlug' => 'contact-us']));

        $response->assertOk();
        $response->assertSee('Contact', false);
        $response->assertSee('collegeofhealthsciences@tenwekhosp.org', false);
        $response->assertSee('Tenwek Hospital College of Health Sciences', false);
        $response->assertSee('0736 568 177', false);
    }

    public function test_cohs_contact_form_posts_with_school_id(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $cohs = School::query()->where('slug', 'cohs')->firstOrFail();

        $response = $this->post(route('contact.store'), [
            'name' => 'Test Applicant',
            'email' => 'test@example.com',
            'topic' => 'Admissions',
            'message' => '',
            'school_id' => $cohs->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('form_submissions', [
            'form_key' => 'contact',
            'school_id' => $cohs->id,
        ]);
    }
}
