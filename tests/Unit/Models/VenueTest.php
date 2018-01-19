<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Venue;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VenueTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_get_venues_past_events()
    {
        $venue = factory(Venue::class)->create();

        $pastEventA = factory(Event::class)->create(['venue_id' => $venue->id, 'date' => Carbon::now()->subWeek(1)]);
        $pastEventB = factory(Event::class)->create(['venue_id' => $venue->id, 'date' => Carbon::now()->subWeek(1)]);
        $pastEventC = factory(Event::class)->create(['venue_id' => $venue->id, 'date' => Carbon::now()->subWeek(1)]);
        $upcomingEvent = factory(Event::class)->create(['venue_id' => $venue->id, 'date' => Carbon::now()->addWeek(1)]);

        $pastEvents = $venue->pastEvents();

        $this->assertTrue($venue->hasPastEvents());
        $this->assertTrue($pastEvents->contains($pastEventA));
        $this->assertTrue($pastEvents->contains($pastEventB));
        $this->assertTrue($pastEvents->contains($pastEventC));
        $this->assertFalse($pastEvents->contains($upcomingEvent));
    }
}
