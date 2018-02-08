<?php

namespace Tests\Unit;

use EventFactory;
use MatchFactory;
use Tests\TestCase;
use App\Models\Venue;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_event_have_many_matches()
    {
        $event = EventFactory::create();
        MatchFactory::create(['event_id' => $event->id]);
        MatchFactory::create(['event_id' => $event->id]);
        MatchFactory::create(['event_id' => $event->id]);

        $this->assertCount(3, $event->matches);
    }

    /** @test */
    public function an_event_takes_place_at_a_venue()
    {
        $venue = factory(Venue::class)->create();
        $event = EventFactory::create(['venue_id' => $venue->id]);

        $this->assertEquals($venue->id, $event->venue->id);
    }
}
