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
                        ->from(route('matches.index', $this->event->id))
                        ->delete(route('matches.destroy', [$this->event->id, $this->match->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.index', ['event' => $this->event->id]));
        $this->assertSoftDeleted('matches', ['id' => $this->match->id, 'event_id' => $this->event->id, 'match_number' => $this->match->match_number]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_match()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('matches.index', [$this->event->id]))
                        ->delete(route('matches.destroy', [$this->event->id, $this->match->id]));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_delete_a_match()
    {
        $response = $this->from(route('matches.index', [$this->event->id]))
                        ->delete(route('matches.destroy', [$this->event->id, $this->match->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
