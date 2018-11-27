<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use Tests\IntegrationTestCase;

class EditEventResultsTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-event-results']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_event_results_page()
    {
        $event = factory(Event::class)->states('past')->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('event-results.edit', $event->id));

        $response->assertSuccessful();
        $response->assertViewIs('events.results');
        $response->assertViewHas('event', function ($viewEvent) use ($event) {
            return $viewEvent->id === $event->id;
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_event_results_page()
    {
        $event = factory(Event::class)->states('past')->create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('event-results.edit', $event->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_event_results_page()
    {
        $event = factory(Event::class)->states('past')->create();

        $response = $this->get(route('event-results.edit', $event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
