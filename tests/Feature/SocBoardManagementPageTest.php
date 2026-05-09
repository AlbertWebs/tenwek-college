<?php

namespace Tests\Feature;

use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocBoardManagementPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_soc_board_management_renders_leadership(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $response = $this->get('/soc/board-and-management-team');

        $response->assertOk();
        $response->assertSee('Board and Management Team', false);
        $response->assertSee('training subsidiary', false);
        $response->assertSee('Rev. Dr. Robert Langat', false);
        $response->assertSee('Benjamin Siele', false);
    }
}
