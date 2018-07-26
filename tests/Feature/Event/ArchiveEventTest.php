<?php

namespace Tests\Feature\Event;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArchiveEventTest extends TestCase
{
    use RefreshDatabase;

    private $event;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['archive-event']);

        $this->event = factory(Event::class)->create();
    }

    /** @test */
    public function users_who_have_permission_can_archive_an_event()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('events.index', ['event' => $this->event->id]))
                        ->patch(route('events.archive', ['event' => $this->event->id]));

        $response->assertStatus(302);
        $this->assertDatabaseHas('events', ['id' => $this->event->id, 'name' => $this->event->name, 'archived_at' => Carbon::now()]);
        $response->assertRedirect(route('events.index'));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_archive_an_event()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('events.index', ['event' => $this->event->id]))
                        ->patch(route('events.archive', ['event' => $this->event->id]));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_archive_an_event()
    {
        $response = $this
                    ->from(route('events.index', ['event' => $this->event->id]))
                    ->patch(route('events.archive', ['event' => $this->event->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
