<?php

namespace Tests\Feature\Event;

use Carbon\Carbon;
use Facades\EventFactory;
use Tests\IntegrationTestCase;

class EditEventTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-event']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_event_page_of_a_scheduled_event()
    {
        $event = EventFactory::states('scheduled')->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('events.edit', $event->id));

        $response->assertSuccessful();
        $response->assertViewIs('events.edit');
        $response->assertViewHas('event');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_event_page()
    {
        $event = EventFactory::create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('events.edit', $event->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_event_page()
    {
        $event = EventFactory::create();

        $response = $this->get(route('events.edit', $event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_event_that_has_a_date_in_the_past_cannot_be_edited()
    {
        $event = EventFactory::onDate(Carbon::yesterday())->create();
        dd($event);

        $response = $this->actingAs($this->authorizedUser)->get(route('events.edit', $event->id));

        $response->assertStatus(404);
    }
}
