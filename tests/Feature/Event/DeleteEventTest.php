<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteEventTest extends TestCase
{
    use RefreshDatabase;

    private $event;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-event');

        $this->event = factory(Event::class)->create();
    }

    /** @test */
    public function users_who_have_permission_can_delete_a_event()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('events.index'))
                        ->delete(route('events.destroy', $this->event->id));

        $response->assertStatus(302);
        $this->assertSoftDeleted('events', ['id' => $this->event->id, 'name' => $this->event->name]);
        $this->assertNotNull($this->event->fresh()->deleted_at);
        $response->assertRedirect(route('events.index'));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_event()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('events.index'))
                        ->delete(route('events.destroy', $this->event->id));

        $response->assertStatus(403);
        $this->assertNull($this->event->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_a_event()
    {
        $response = $this->from(route('events.index'))
                        ->delete(route('events.destroy', $this->event->id));

        $response->assertStatus(302);
        $this->assertNull($this->event->deleted_at);
        $response->assertRedirect(route('login'));
    }
}
