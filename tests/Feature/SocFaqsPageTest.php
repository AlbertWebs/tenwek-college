<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocFaqsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_soc_faqs_renders_questions_and_comparison_table(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/faqs');

        $response->assertOk();
        $response->assertSee('FAQs', false);
        $response->assertSee('pastor', false);
        $response->assertSee('chaplain', false);
        $response->assertSee('Nova Pioneer', false);
        $response->assertSee('trimesters', false);
    }
}
