<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocStrategicPartnersPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_soc_strategic_partners_renders_partners(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/strategic-partners');

        $response->assertOk();
        $response->assertSee('Strategic Partners', false);
        $response->assertSee('Africa Gospel Church', false);
        $response->assertSee('Samaritan', false);
        $response->assertSee('World Gospel Mission', false);
        $response->assertSee('Friends of Tenwek', false);
    }
}
