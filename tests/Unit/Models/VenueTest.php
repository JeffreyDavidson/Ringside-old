<?php

namespace Tests\Unit;

use App\Models\Venue;
use App\Models\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VenueTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_get_events_that_have_taken_place_at_a_venue()
    {
        $venue = factory(Venue::class)->create();
        factory(Event::class)->create(['venue_id' => $venue->id]);
        factory(Event::class)->create(['venue_id' => $venue->id]);
        factory(Event::class)->create(['venue_id' => $venue->id]);

        $this->assertCount(3, $venue->events);
    }
}
