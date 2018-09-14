<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VenueTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_a_venues_past_events()
    {
        $venue = factory(Venue::class)->create();

        $pastEventA = factory(Event::class)->states('past')->create(['venue_id' => $venue->id]);
        $pastEventB = factory(Event::class)->states('past')->create(['venue_id' => $venue->id]);
        $upcomingEvent = factory(Event::class)->states('scheduled')->create(['venue_id' => $venue->id]);

        $pastEvents = $venue->pastEvents;

        $this->assertTrue($venue->hasPastEvents());
        $this->assertTrue($pastEvents->contains($pastEventA));
        $this->assertTrue($pastEvents->contains($pastEventB));
        $this->assertFalse($pastEvents->contains($upcomingEvent));
    }

    /** @test */
    public function it_can_get_a_venues_scheduled_events()
    {
        $venue = factory(Venue::class)->create();

        $scheduledEventA = factory(Event::class)->states('scheduled')->create(['venue_id' => $venue->id]);
        $scheduledEventB = factory(Event::class)->states('scheduled')->create(['venue_id' => $venue->id]);
        $pastEvent = factory(Event::class)->states('past')->create(['venue_id' => $venue->id]);

        $scheduledEvents = $venue->scheduledEvents;

        $this->assertTrue($venue->hasScheduledEvents());
        $this->assertTrue($scheduledEvents->contains($scheduledEventA));
        $this->assertTrue($scheduledEvents->contains($scheduledEventB));
        $this->assertFalse($scheduledEvents->contains($pastEvent));
    }
}
