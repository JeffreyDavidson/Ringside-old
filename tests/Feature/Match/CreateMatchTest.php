<?php

namespace Tests\Feature\Match;

use App\Models\Event;
use Tests\IntegrationTestCase;

class CreateMatchTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-match']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_match_page_for_a_scheduled_event()
    {
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('matches.create', $event->id));

        $response->assertSuccessful();
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_event_page_for_a_scheduled_event()
    {
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('matches.create', $event->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_event_page_for_a_scheduled_event()
    {
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->get(route('matches.create', $event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * TODO: Add functionality to make this pass.
     */
    public function users_who_have_permission_cannot_view_the_add_event_page_for_a_past_event()
    {
        $event = factory(Event::class)->states('past')->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('matches.create', $event->id));

        $response->assertStatus(403);
    }
}
