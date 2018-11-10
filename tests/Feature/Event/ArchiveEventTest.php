<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArchiveEventTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['archive-event']);
    }

    /** @test */
    public function users_who_have_permission_can_archive_a_past_event()
    {
        $event = factory(Event::class)->states('past')->create();

        $response = $this->actingAs($this->authorizedUser)
            ->from(route('past-events.index'))
            ->post(route('archived-events.store', $this->event));

        $response->assertStatus(302);
        $response->assertRedirect(route('past-events.index'));
        $this->assertTrue($event->fresh()->isArchived());
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_archive_a_past_event()
    {
        $event = factory(Event::class)->states('past')->create();

        $response = $this->actingAs($this->unauthorizedUser)->from(route('past-events.index'))->post(route('archived-events.store', [
            'event_id' => $event->id,
        ]));

        $response->assertStatus(403);
        $this->assertFalse($event->fresh()->isArchived());
    }

    /** @test */
    public function guests_cannot_archive_a_past_event()
    {
        $event = factory(Event::class)->states('past')->create();

        $response = $this->from(route('past-events.index'))->post(route('archived-events.store', [
            'event_id' => $event->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_scheduled_event_cannot_be_archived()
    {
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->actingAs($this->authorizedUser)->post(route('archived-events.store', [
            'event_id' => $event->id,
        ]));

        $response->assertStatus(422);
    }

    /** @test */
    public function an_event_can_only_be_archived_once()
    {
        $event = factory(Event::class)->states('archived')->create();

        $response = $this->actingAs($this->authorizedUser)->post(route('archived-events.store', [
            'event_id' => $event->id,
        ]));

        $response->assertStatus(422);
    }

    /** @test */
    public function an_event_that_does_not_exist_cannot_be_archived()
    {
        $response = $this->actingAs($this->authorizedUser)->post(route('archived-events.store', [
            'event_id' => 999,
        ]));

        $response->assertStatus(404);
    }
}
