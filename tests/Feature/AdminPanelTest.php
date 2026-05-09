<?php

namespace Tests\Feature;

use App\Models\Download;
use App\Models\SiteAdminSetting;
use App\Models\User;
use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_super_admin_dashboard_contains_shell_and_navigation(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $user = User::query()->where('email', 'admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('SOC CMS')
            ->assertSee('Users & roles')
            ->assertSee(route('search'), false);
    }

    public function test_soc_admin_sees_soc_cms_and_not_cohs_management_group(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $user = User::query()->where('email', 'soc.admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('SOC CMS', false)
            ->assertDontSee('College of Health Sciences', false);
    }

    public function test_cohs_admin_sees_cohs_block_and_not_soc_cms(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $user = User::query()->where('email', 'cohs.admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('COHS downloads', false)
            ->assertDontSee('SOC CMS', false);
    }

    public function test_authenticated_admin_downloads_index_returns_ok(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $user = User::query()->where('email', 'cohs.admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.downloads.index'))
            ->assertOk();
    }

    public function test_cohs_admin_can_open_admin_download_file_stream(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        Storage::fake('downloads');

        $path = 'cohs/application.pdf';
        Storage::disk('downloads')->put($path, '%PDF-1.4 test');

        $record = Download::query()->where('slug', 'cohs-application-form-clinical-rev-2025')->firstOrFail();
        $record->update([
            'file_path' => $path,
            'original_filename' => 'application.pdf',
            'mime' => 'application/pdf',
            'size_bytes' => 12,
            'extension' => 'pdf',
        ]);

        $user = User::query()->where('email', 'cohs.admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.downloads.file', $record))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_guest_cannot_open_admin_download_file(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        Storage::fake('downloads');
        $path = 'cohs/x.pdf';
        Storage::disk('downloads')->put($path, 'x');

        $record = Download::query()->where('slug', 'cohs-application-form-clinical-rev-2025')->firstOrFail();
        $record->update([
            'file_path' => $path,
            'original_filename' => 'x.pdf',
            'mime' => 'application/pdf',
            'size_bytes' => 1,
            'extension' => 'pdf',
        ]);

        $this->get(route('admin.downloads.file', $record))
            ->assertRedirect(route('login'));
    }

    public function test_soc_admin_cannot_open_cohs_download_file(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        Storage::fake('downloads');
        $path = 'cohs/x.pdf';
        Storage::disk('downloads')->put($path, 'x');

        $record = Download::query()->where('slug', 'cohs-application-form-clinical-rev-2025')->firstOrFail();
        $record->update([
            'file_path' => $path,
            'original_filename' => 'x.pdf',
            'mime' => 'application/pdf',
            'size_bytes' => 1,
            'extension' => 'pdf',
        ]);

        $user = User::query()->where('email', 'soc.admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.downloads.file', $record))
            ->assertForbidden();
    }

    public function test_super_admin_can_open_users_administration(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $user = User::query()->where('email', 'admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Users &amp; roles', false)
            ->assertSee('New user', false);
    }

    public function test_school_admin_cannot_open_users_administration(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $user = User::query()->where('email', 'cohs.admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_super_admin_can_save_global_seo_defaults(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $user = User::query()->where('email', 'admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->put(route('admin.global-seo.update'), [
                'default_meta_description' => 'Institution-wide test description for SEO.',
                'default_keywords' => 'Test, Tenwek',
                'default_og_image' => '',
                'default_robots' => '',
            ])
            ->assertRedirect(route('admin.global-seo.edit'));

        $this->assertSame(
            'Institution-wide test description for SEO.',
            SiteAdminSetting::query()->first()?->global_seo['default_meta_description']
        );
    }
}
