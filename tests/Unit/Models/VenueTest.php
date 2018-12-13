<?php

namespace Tests\Unit\Models;

use App\Models\Event;
use App\Models\Venue;
use Tests\IntegrationTestCase;

class VenueTest extends IntegrationTestCase
{
    /** @test */
    public function a_venue_has_a_name()
    {
        $venue = factory(Venue::class)->create(['name' => 'Venue Name']);

        $this->assertEquals('Venue Name', $venue->name);
    }

    /** @test */
    public function a_venue_has_an_address()
    {
        $venue = factory(Venue::class)->create(['address' => '123 Fake Street']);

        $this->assertEquals('123 Fake Street', $venue->address);
    }

    /** @test */
    public function a_venue_has_a_city()
    {
        $venue = factory(Venue::class)->create(['city' => 'Kansas City']);

        $this->assertEquals('Kansas City', $venue->city);
    }

    /** @test */
    public function a_venue_has_a_state()
    {
        $venue = factory(Venue::class)->create(['state' => 'MO']);

        $this->assertEquals('MO', $venue->state);
    }

    /** @test */
    public function a_venue_has_a_postcode()
    {
        $venue = factory(Venue::class)->create(['postcode' => '90210']);

        $this->assertEquals('90210', $venue->postcode);
    }
    
    /** @test */
    public function it_can_get_a_venues_past_events()
    {
        $venue = factory(Venue::class)->create();
        $pastEventA = factory(Event::class)->states('past')->create(['venue_id' => $venue->id]);
        $pastEventB = factory(Event::class)->states('past')->create(['venue_id' => $venue->id]);
        $upcomingEvent = factory(Event::class)->states('scheduled')->create(['venue_id' => $venue->id]);

        $pastEvents = $venue->pastEvents()->get();

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

        $scheduledEvents = $venue->scheduledEvents()->get();

        $this->assertTrue($venue->hasScheduledEvents());
        $this->assertTrue($scheduledEvents->contains($scheduledEventA));
        $this->assertTrue($scheduledEvents->contains($scheduledEventB));
        $this->assertFalse($scheduledEvents->contains($pastEvent));
    }
}
