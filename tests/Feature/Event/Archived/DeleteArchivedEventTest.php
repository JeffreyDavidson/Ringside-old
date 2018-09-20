<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteArchivedEventTest extends TestCase
{
    use RefreshDatabase;

    private $event;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-event');

        $this->event = factory(Event::class)->states('archived')->create();
    }

    /** @test */
    public function users_who_have_permission_can_delete_an_archived_event()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('archived-events.index'))
                        ->delete(route('events.destroy', $this->event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('archived-events.index'));
        $this->assertSoftDeleted('events', ['id' => $this->event->id, 'name' => $this->event->name]);
        $this->assertNotNull($this->event->fresh()->deleted_at);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_an_archived_event()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('archived-events.index'))
                        ->delete(route('events.destroy', $this->event->id));

        $response->assertStatus(403);
        $this->assertNull($this->event->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_an_archived_event()
    {
        $response = $this->from(route('archived-events.index'))
                        ->delete(route('events.destroy', $this->event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
