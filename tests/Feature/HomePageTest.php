<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_returns_ok_with_landmarks_after_seed(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('official emblem', false);
        $response->assertSee('Welcome', false);
        $response->assertSee('Hospital College', false);
        $response->assertSee('Select a school', false);
        $response->assertSee('School of Chaplaincy', false);
        $response->assertSee(config('tenwek.institution_legal'), false);
    }
}
