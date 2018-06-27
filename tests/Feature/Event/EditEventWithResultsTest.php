<?php

namespace Tests\Feature\Event;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditEventWithResultsTest extends TestCase
{
    use RefreshDatabase;

    private $event;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['edit-event-results', 'update-event-results']);

        $this->event = factory(Event::class)->create();
    }

    /** @test */
    public function users_who_have_permission_can_view_the_event_results_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('results.edit', $this->event));

        $response->assertSuccessful();
        $response->assertViewIs('events.results');
        $response->assertViewHas('event');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_event_results_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('results.edit', $this->event));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_event_results_page()
    {
        $response = $this->get(route('results.edit', $this->event));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
