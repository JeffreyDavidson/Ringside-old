<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Match;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_event_have_many_matches()
    {
        $event = factory(Event::class)->create();
        factory(Match::class)->create(['event_id' => $event->id]);
        factory(Match::class)->create(['event_id' => $event->id]);
        factory(Match::class)->create(['event_id' => $event->id]);

        $this->assertCount(3, $event->matches);
    }

    /** @test */
    public function an_event_takes_place_at_a_venue()
    {
        $venue = factory(Venue::class)->create();
        $event = factory(Event::class)->create(['venue_id' => $venue->id]);

        $this->assertEquals($venue->id, $event->venue->id);
    }
}
