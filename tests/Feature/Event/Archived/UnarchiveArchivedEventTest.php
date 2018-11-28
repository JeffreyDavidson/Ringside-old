<?php

namespace Tests\Feature\Event\Archived;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnarchiveArchivedEventTest extends TestCase
{
    use RefreshDatabase;

    private $event;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('unarchive-event');

        $this->event = factory(Event::class)->states('archived')->create();
    }

    /** @test */
    public function users_who_have_permission_can_unarchive_an_archived_event()
    {
        $response = $this->actingAs($this->authorizedUser)
            ->from(route('archived-events.index'))
            ->delete(route('archived-events.unarchive', $this->event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('archived-events.index'));
        $this->assertFalse($this->event->fresh()->isArchived());
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_unarchive_an_archived_event()
    {
        $response = $this->actingAs($this->unauthorizedUser)
            ->from(route('archived-events.index'))
            ->delete(route('archived-events.unarchive', $this->event->id));

        $response->assertStatus(403);
        $this->assertTrue($this->event->fresh()->isArchived());
    }

    /** @test */
    public function guests_cannot_unarchive_an_archived_event()
    {
        $response = $this->from(route('archived-events.index'))
            ->delete(route('archived-events.unarchive', $this->event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
