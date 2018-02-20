<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VenueTest extends TestCase
{
    use DatabaseMigrations;

    protected $venue;

    public function setUp()
    {
        parent::setUp();

        $this->venue = factory(Venue::class)->create();
    }

    /** @test */
    function a_venue_has_many_events()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->venue->events);
    }

    /** @test */
    public function it_can_get_a_venues_past_events()
    {
        $venue = factory(Venue::class)->create();

        $pastEventA = factory(Event::class)->create(['venue_id' => $venue->id, 'date' => Carbon::now()->subWeek(1)]);
        $pastEventB = factory(Event::class)->create(['venue_id' => $venue->id, 'date' => Carbon::now()->subWeek(1)]);
        $upcomingEvent = factory(Event::class)->create(['venue_id' => $venue->id, 'date' => Carbon::now()->addWeek(1)]);

        $pastEvents = $venue->pastEvents();

        $this->assertTrue($venue->hasPastEvents());
        $this->assertTrue($pastEvents->contains($pastEventA));
        $this->assertTrue($pastEvents->contains($pastEventB));
        $this->assertFalse($pastEvents->contains($upcomingEvent));
    }
}
