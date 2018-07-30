<?php

namespace Tests\Feature\Match;

use Tests\TestCase;
use App\Models\Event;
use Carbon\Carbon;
use Facades\MatchFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteMatchTest extends TestCase
{
    use RefreshDatabase;

    private $event;
    private $match;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['delete-match']);

        $this->event = factory(Event::class)->create(['date' => Carbon::now()]);
        $this->match = MatchFactory::forEvent($this->event)->create();
    }

    /** @test */
    public function users_who_have_permission_can_delete_a_match()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('event.matches.index', ['event' => $this->event->id]))
                        ->delete(route('event.matches.destroy', ['event' => $this->event->id, 'match' => $this->match->id]));

        $response->assertStatus(302);
        $this->assertSoftDeleted('matches', ['id' => $this->match->id, 'event_id' => $this->event->id, 'match_number' => $this->match->match_number]);
        $this->assertNotNull($this->match->fresh()->deleted_at);
        $response->assertRedirect(route('event.matches.index', ['event' => $this->event->id]));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_match()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('event.matches.index', ['event' => $this->event->id]))
                        ->delete(route('event.matches.destroy', ['event' => $this->event->id, 'match' => $this->match->id]));

        $response->assertStatus(403);
        $this->assertNull($this->match->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_a_match()
    {
        $response = $this->from(route('event.matches.index', ['event' => $this->event->id]))
                        ->delete(route('event.matches.destroy', ['event' => $this->event->id, 'match' => $this->match->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function returns_404_on_invalid_event_id()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('event.matches.index', ['event' => $this->event->id]))
                        ->delete(route('event.matches.destroy', ['event' => NULL, 'match' => $this->match->id]));

        $response->assertStatus(404);
    }

    /** @test */
    public function returns_404_on_invalid_match_id()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('event.matches.index', ['event' => $this->event->id]))
                        ->delete(route('event.matches.destroy', ['event' => $this->event->id, 'match' => 2]));

        $response->assertStatus(404);
    }
}
