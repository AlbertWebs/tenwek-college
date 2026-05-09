<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocFeePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_soc_fee_page_renders_fee_structure_and_payment_details(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/fee');

        $response->assertOk();
        $response->assertSee('Fees', false);
        $response->assertSee('2021 fee structure', false);
        $response->assertSee('Tuition fees', false);
        $response->assertSee('29,000', false);
        $response->assertSee('17,500', false);
        $response->assertSee('Kenya Commercial Bank', false);
        $response->assertSee('522522', false);
        $response->assertSee('0716178653', false);
    }
}
