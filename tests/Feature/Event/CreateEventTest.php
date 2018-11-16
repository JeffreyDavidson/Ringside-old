<?php

namespace Tests\Feature\Event;

use Tests\IntegrationTestCase;

class CreateEventTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-event']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_event_page()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('events.create'));

        $response->assertSuccessful();
        $response->assertViewIs('events.create');
        $response->assertViewHas('event');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_event_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('events.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_event_page()
    {
        $response = $this->get(route('events.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
