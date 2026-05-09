<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CohsOnCampusApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_on_campus_application_page_loads(): void
    {
        $this->seed();

        School::query()->where('slug', 'cohs')->update(['is_active' => true]);

        $response = $this->get(route('cohs.on-campus-application'));

        $response->assertOk();
        $response->assertSee('Tenwek Hospital College', false);
        $response->assertSee('Online Application Portal', false);
    }

    public function test_oncampus_link_redirects_to_application(): void
    {
        $this->seed();

        School::query()->where('slug', 'cohs')->update(['is_active' => true]);

        $response = $this->get(route('schools.pages.show', ['school' => 'cohs', 'pageSlug' => 'oncampus-link']));

        $response->assertRedirect(route('cohs.on-campus-application'));
    }
}
