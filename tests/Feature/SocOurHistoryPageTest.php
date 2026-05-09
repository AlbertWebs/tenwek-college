<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocOurHistoryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_soc_our_history_renders_milestones(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/our-history');

        $response->assertOk();
        $response->assertSee('About School of Chaplaincy', false);
        $response->assertSee('Milestones', false);
        $response->assertSee('1991', false);
        $response->assertSee('L. Nelson Bell Chaplaincy Training School', false);
        $response->assertSee('TVET – CDACC', false);
    }
}
