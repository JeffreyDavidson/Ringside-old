<?php

namespace Tests\Feature\Event\Past;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArchivePastEventTest extends TestCase
{
    use RefreshDatabase;

    private $event;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['archive-event']);

        $this->event = factory(Event::class)->states('past')->create();
    }

    /** @test */
    public function users_who_have_permission_can_archive_a_past_event()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('past-events.index'))
                        ->post(route('past-events.archive', $this->event));

        $response->assertStatus(302);
        $response->assertRedirect(route('past-events.index'));
        $this->assertDatabaseHas('events', ['id' => $this->event->id, 'name' => $this->event->name, 'archived_at' => now()->format('Y-m-d H:i:s')]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_archive_a_past_event()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('past-events.index'))
                        ->post(route('past-events.archive', $this->event));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_archive_a_past_event()
    {
        $response = $this->from(route('past-events.index'))
                        ->post(route('past-events.archive', $this->event));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
