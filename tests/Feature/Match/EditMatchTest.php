<?php

namespace Tests\Feature\Match;

use Tests\TestCase;
use App\Models\Event;
use Carbon\Carbon;
use Facades\MatchFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditMatchTest extends TestCase
{
    use RefreshDatabase;

    private $event;
    private $match;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['edit-match', 'update-match']);

        $this->event = factory(Event::class)->create(['date' => Carbon::now()]);
        $this->match = MatchFactory::forEvent($this->event)->create();
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_match_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('matches.edit', ['event' => $this->event->id, 'match' => $this->match->id]));

        $response->assertSuccessful();
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_match_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('matches.edit', ['event' => $this->event->id, 'match' => $this->match->id]));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_match_page()
    {
        $response = $this->get(route('matches.edit', ['event' => $this->event->id, 'match' => $this->match->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function returns_404_on_invalid_event_id()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('matches.edit', ['event' => null, 'match' => $this->match->id]));

        $response->assertStatus(404);
    }

    /** @test */
    public function returns_404_on_invalid_match_id()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('matches.edit', ['event' => $this->event->id, 'match' => null]));

        $response->assertStatus(404);
    }
}
