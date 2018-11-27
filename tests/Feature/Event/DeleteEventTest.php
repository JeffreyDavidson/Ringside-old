<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use Tests\IntegrationTestCase;

class DeleteEventTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-event');
    }

    /** @test */
    public function users_who_have_permission_can_delete_a_past_event()
    {
        $event = factory(Event::class)->create();

        $response = $this->actingAs($this->authorizedUser)->delete(route('events.destroy', $event->id));

        $response->assertStatus(302);
        $this->assertSoftDeleted('events', ['id' => $this->event->id, 'name' => $this->event->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_past_event()
    {
        $event = factory(Event::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->delete(route('events.destroy', $event->id));

        $response->assertStatus(403);
        $this->assertNotSoftDeleted($event);
    }

    /** @test */
    public function guests_cannot_delete_a_past_event()
    {
        $event = factory(Event::class)->create();

        $response = $this->from(route('past-events.index'))->delete(route('events.destroy', $event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_scheduled_event_that_when_deleted_will_redirect_the_user_to_the_scheduled_events_page()
    {
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('scheduled-events.index'))->delete(route('events.destroy', $event->id));

        $response->assertRedirect(route('scheduled-events.index'));
    }

    /** @test */
    public function a_past_event_that_when_deleted_will_redirect_the_user_to_the_past_events_page()
    {
        $event = factory(Event::class)->states('past')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('past-events.index'))->delete(route('events.destroy', $event->id));

        $response->assertRedirect(route('past-events.index'));
    }

    /** @test */
    public function a_scheduled_event_that_when_deleted_will_redirect_the_user_to_the_scheduled_events_page()
    {
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('scheduled-events.index'))->delete(route('events.destroy', $event->id));

        $response->assertRedirect(route('scheduled-events.index'));
    }
}
