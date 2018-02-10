<?php

namespace Tests\Unit;

use EventFactory;
use MatchFactory;
use Tests\TestCase;
use App\Models\Venue;
use App\Models\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventTest extends TestCase
{
    use DatabaseMigrations;

    protected $event;

    public function setUp()
    {
        parent::setUp();

        $this->event = factory(Event::class)->create();
    }

    /** @test */
    public function an_event_can_have_many_matches()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->event->matches);
    }

    /** @test */
    public function an_event_belongs_to_a_venue()
    {
        $this->assertInstanceOf(Venue::class, $this->event->venue);
    }

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

    /** @test */
    public function an_event_can_get_the_last_match()
    {
        
    }
}
