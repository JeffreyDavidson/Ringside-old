<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEventWithResultsTest extends TestCase
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
    public function users_who_have_permission_view_the_event_results_page()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('events.results', $this->event));

        $response->assertSuccessful();
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_event_form()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('events.results', $this->event));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_event_form()
    {
        $response = $this->get(route('events.create', $this->event));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }


}
