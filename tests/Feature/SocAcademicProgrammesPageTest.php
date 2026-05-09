<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\School;
use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocAcademicProgrammesPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_academic_programmes_index_lists_linked_courses(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/academic-programmes');

        $response->assertOk();
        $response->assertSee('Our courses', false);
        $response->assertSee('Certificate in Chaplaincy', false);
        $response->assertSee('Diploma in Chaplaincy', false);
        $response->assertSee('Healthcare Chaplaincy', false);
        $response->assertSee(route('schools.pages.show', ['school' => 'soc', 'pageSlug' => 'certificate-in-chaplaincy']), false);
    }

    public function test_individual_programme_page_renders(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/certificate-in-chaplaincy');

        $response->assertOk();
        $response->assertSee('Certificate in Chaplaincy', false);
        $response->assertSee('KCSE D', false);
        $response->assertSee('Back to academic programmes', false);
    }

    public function test_individual_programme_page_works_when_database_row_missing(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $socId = School::query()->where('slug', 'soc')->value('id');
        Page::query()->where('school_id', $socId)->where('slug', 'certificate-in-chaplaincy')->delete();

        $response = $this->get('/soc/certificate-in-chaplaincy');

        $response->assertOk();
        $response->assertSee('Certificate in Chaplaincy', false);
        $response->assertSee('Apply online', false);
    }
}
