<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocLandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_soc_landing_returns_ok_with_core_sections(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc');

        $response->assertOk();
        $response->assertSee('College Home', false);
        $response->assertSee('Online Portal', false);
        $response->assertSee(config('tenwek.soc_landing.top_bar.email', 'soc@tenwekhosp.org'), false);
        $response->assertSee('About Us', false);
        $response->assertSee('Register Online', false);
        $response->assertSee('Karibu', false);
        $response->assertSee('Mission &amp; vision', false);
        $response->assertSee('To equip chaplains for wholistic service', false);
        $response->assertSee('Mrs. Hellen Tangus', false);
        $response->assertSee('Robert Sang', false);
        $response->assertSee('Send a message', false);
        $response->assertSee(config('tenwek.soc_landing.contact.email'), false);
    }
}
