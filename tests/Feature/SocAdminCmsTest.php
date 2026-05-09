<?php

namespace Tests\Feature;

use App\Models\FormSubmission;
use App\Models\School;
use App\Models\SocProgrammeGroup;
use App\Models\SocProgrammeItem;
use App\Models\User;
use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocAdminCmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_programme_groups_admin_requires_authentication(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $this->get(route('admin.soc.programme-groups.index'))
            ->assertRedirect(route('login'));
    }

    public function test_soc_admin_can_open_programme_groups_and_submissions(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $user = User::query()->where('email', 'soc.admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.soc.programme-groups.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('admin.soc.submissions.index'))
            ->assertOk();
    }

    public function test_import_programmes_command_populates_database(): void
    {
        $this->seed(TenwekFoundationSeeder::class);

        $this->artisan('soc:import-programmes')->assertSuccessful();

        $soc = School::query()->where('slug', 'soc')->firstOrFail();
        $this->assertGreaterThan(0, SocProgrammeGroup::query()->where('school_id', $soc->id)->count());
        $this->assertGreaterThan(0, SocProgrammeItem::query()->where('school_id', $soc->id)->count());
    }

    public function test_synthetic_programme_page_uses_item_seo_fields(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $soc = School::query()->where('slug', 'soc')->firstOrFail();
        $group = SocProgrammeGroup::query()->create([
            'school_id' => $soc->id,
            'heading' => 'Test group',
            'description' => null,
            'sort_order' => 0,
        ]);
        SocProgrammeItem::query()->create([
            'school_id' => $soc->id,
            'soc_programme_group_id' => $group->id,
            'slug' => 'custom-prog-seo',
            'title' => 'Custom programme',
            'badge' => null,
            'summary' => 'Summary text for SEO test.',
            'body' => null,
            'seo_title' => 'Custom SEO Title',
            'seo_description' => 'Custom meta description for programme.',
            'seo_keywords' => 'chaplaincy, test',
            'og_title' => 'Custom OG Title',
            'og_image_path' => null,
            'sort_order' => 0,
            'is_published' => true,
        ]);

        $response = $this->get(route('schools.pages.show', [$soc, 'custom-prog-seo']));
        $response->assertOk();
        $response->assertSee('Custom OG Title', false);
        $response->assertSee('chaplaincy, test', false);
    }

    public function test_submissions_show_lists_soc_submission(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $soc = School::query()->where('slug', 'soc')->firstOrFail();
        $submission = FormSubmission::query()->create([
            'form_key' => 'contact',
            'school_id' => $soc->id,
            'payload' => ['name' => 'Jane', 'message' => 'Hello'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test',
            'processed' => false,
        ]);
        $user = User::query()->where('email', 'soc.admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.soc.submissions.show', $submission))
            ->assertOk()
            ->assertSee('Jane', false);
    }
}
