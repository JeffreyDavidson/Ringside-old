<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use Tests\IntegrationTestCase;

class ViewEventTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-event');
    }

    /** @test */
    public function users_who_have_permission_can_view_an_event()
    {
        $event = factory(Event::class)->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('events.show', $event->id));

        $response->assertSuccessful();
        $response->assertViewIs('events.show');
        $response->assertViewHas('event');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_an_event()
    {
        $event = factory(Event::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('events.show', $event->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_an_event()
    {
        $event = factory(Event::class)->create();

        $response = $this->get(route('events.show', $event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
