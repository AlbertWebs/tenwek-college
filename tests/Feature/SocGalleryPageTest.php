<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\School;
use App\Support\Soc\SocLandingRepository;
use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocGalleryPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SocLandingRepository::flushCache();
    }

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

    public function test_soc_gallery_includes_media_library_images(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $soc = School::query()->where('slug', 'soc')->firstOrFail();
        $path = 'soc/'.$soc->id.'/library/gallery-test.jpg';
        MediaAsset::query()->create([
            'user_id' => null,
            'school_id' => $soc->id,
            'disk' => 'public',
            'path' => $path,
            'original_filename' => 'gallery-test.jpg',
            'mime_type' => 'image/jpeg',
            'size_bytes' => 1000,
            'alt_text' => 'From media library',
        ]);

        $this->get('/soc/gallery')
            ->assertOk()
            ->assertSee('storage/'.$path, false)
            ->assertSee('From media library', false);
    }
}
