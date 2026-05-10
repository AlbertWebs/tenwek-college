<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\SchoolEvent;
use App\Models\User;
use Database\Seeders\TenwekFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SchoolEventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_published_school_event_on_public_site(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $soc = School::query()->where('slug', 'soc')->firstOrFail();
        SchoolEvent::query()->create([
            'school_id' => $soc->id,
            'author_id' => null,
            'title' => 'Open day',
            'slug' => 'open-day',
            'excerpt' => 'Visit our campus.',
            'body' => '<p>Details here.</p>',
            'image_path' => null,
            'starts_at' => now()->addWeek(),
            'ends_at' => null,
            'location' => 'Main hall',
            'registration_url' => null,
            'published_at' => now()->subDay(),
            'seo_title' => null,
            'seo_description' => null,
        ]);

        $this->get(route('schools.events.index', $soc))->assertOk()->assertSee('Open day', false);
        $this->get(route('schools.events.show', [$soc, 'open-day']))->assertOk()->assertSee('Details here.', false);
    }

    public function test_draft_event_not_on_public_site(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $soc = School::query()->where('slug', 'soc')->firstOrFail();
        SchoolEvent::query()->create([
            'school_id' => $soc->id,
            'author_id' => null,
            'title' => 'Secret',
            'slug' => 'secret',
            'excerpt' => null,
            'body' => null,
            'image_path' => null,
            'starts_at' => now()->addWeek(),
            'ends_at' => null,
            'location' => null,
            'registration_url' => null,
            'published_at' => null,
            'seo_title' => null,
            'seo_description' => null,
        ]);

        $this->get(route('schools.events.index', $soc))->assertOk()->assertDontSee('Secret', false);
        $this->get(route('schools.events.show', [$soc, 'secret']))->assertNotFound();
    }

    public function test_soc_admin_can_create_event_with_image(): void
    {
        $this->seed(TenwekFoundationSeeder::class);
        $soc = School::query()->where('slug', 'soc')->firstOrFail();
        $user = User::query()->where('email', 'soc.admin@tenwekhospitalcollege.ac.ke')->firstOrFail();

        $this->actingAs($user)
            ->post(route('admin.soc.events.store'), [
                'title' => 'Graduation',
                'excerpt' => 'Ceremony',
                'body' => '<p>Hello</p>',
                'starts_at' => now()->addMonth()->format('Y-m-d\TH:i'),
                'published_at' => now()->format('Y-m-d\TH:i'),
                'image' => UploadedFile::fake()->image('grad.jpg', 800, 500),
            ])
            ->assertRedirect(route('admin.soc.events.index'));

        $event = SchoolEvent::query()->where('school_id', $soc->id)->firstOrFail();
        $this->assertSame('graduation', $event->slug);
        $this->assertNotNull($event->image_path);
        $this->assertStringStartsWith('soc/'.$soc->id.'/events/', $event->image_path);

        $this->actingAs($user)
            ->get(route('admin.soc.events.edit', $event))
            ->assertOk();
    }
}
