<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocPrincipalMessagePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_soc_principal_message_renders(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/message-from-the-principal');

        $response->assertOk();
        $response->assertSee('Welcome to Tenwek Hospital College', false);
        $response->assertSee('Message from the principal', false);
        $response->assertSee('1 Corinthians 9:19', false);
        $response->assertSee('Explore admissions', false);
        $response->assertSee('Photograph coming soon', false);
    }
}
