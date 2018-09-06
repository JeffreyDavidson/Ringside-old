<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Venue;
use App\Models\Event;
use App\Models\Match;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_event_can_have_many_matches()
    {
        $event = factory(Event::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $event->matches);
    }

    /** @test */
    public function an_event_belongs_to_a_venue()
    {
        $event = factory(Event::class)->create();

        $this->assertInstanceOf(Venue::class, $event->venue);
    }

    /** @test */
    public function the_last_match_in_an_event_is_the_main_event()
    {
        $event = factory(Event::class)->create();
        $matchA = factory(Match::class)->create(['event_id' => $event->id]);
        $matchB = factory(Match::class)->create(['event_id' => $event->id]);
        $matchC = factory(Match::class)->create(['event_id' => $event->id]);
        $matchB->update(['match_number' => 999]);

        $mainEvent = $event->mainEvent;

        $this->assertEquals($matchB->id, $mainEvent->id);
    }

    /** @test */
    public function an_event_can_add_a_match()
    {
        $event = factory(Event::class)->create();
        $match = factory(Match::class)->create();

        $event->addMatch($match);

        $this->assertEquals($match->event_id, $event->id);
    }

    /** @test */
    public function an_event_can_be_archived()
    {
        $event = factory(Event::class)->create();

        $event->archive();

        $this->assertNotNull($event->archived_at);
    }
}
