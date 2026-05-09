<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocGalleryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_soc_gallery_renders_gallery_sections(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/gallery');

        $response->assertOk();
        $response->assertSee('Gallery', false);
        $response->assertSee('Our gallery', false);
        $response->assertSee('SOC life', false);
        $response->assertSee('banner-a.jpg', false);
    }
}
