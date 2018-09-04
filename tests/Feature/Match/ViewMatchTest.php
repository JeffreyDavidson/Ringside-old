<?php

namespace Tests\Feature\Match;

use Tests\TestCase;
use App\Models\Event;
use Carbon\Carbon;
use Facades\MatchFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewMatchTest extends TestCase
{
    use RefreshDatabase;

    private $event;
    private $match;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['view-match']);

        $this->event = factory(Event::class)->create(['date' => Carbon::now()]);
        $this->match = MatchFactory::forEvent($this->event)->create();
    }

    /** @test */
    public function users_who_have_permission_can_view_a_match()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('event.matches.show', ['event' => $this->event->id, 'match' => $this->match->id]));

        $response->assertSuccessful();
        $response->assertViewIs('matches.show');
        $response->assertViewHas('match');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_match_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('event.matches.show', ['event' => $this->event->id, 'match' => $this->match->id]));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_match_page()
    {
        $response = $this->get(route('event.matches.show', ['event' => $this->event->id, 'match' => $this->match->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function returns_404_on_invalid_event_id()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('event.matches.show', ['event' => NULL, 'match' => $this->match->id]));

        $response->assertStatus(404);
    }

    /** @test */
    public function returns_404_on_invalid_match_id()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('event.matches.show', ['event' => $this->event->id, 'match' => 2]));

        $response->assertStatus(404);
    }
}
