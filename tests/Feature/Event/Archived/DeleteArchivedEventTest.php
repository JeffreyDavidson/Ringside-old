<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_an_archived_event()
    {
        $response = $this->actingAs($this->unauthorizedUser)
            ->from(route('archived-events.index'))
            ->delete(route('events.destroy', $this->event->id));

        $response->assertStatus(403);
        $this->assertNotSoftDeleted($this->event);
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
