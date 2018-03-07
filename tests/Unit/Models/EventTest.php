<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Venue;
use App\Models\Event;
use App\Models\Match;
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
    public function an_event_can_get_the_main_event_match()
    {
        $matchA = factory(Match::class)->create(['event_id' => $this->event->id]);
        $matchB = factory(Match::class)->create(['event_id' => $this->event->id]);
        $matchC = factory(Match::class)->create(['event_id' => $this->event->id]);

        $mainEvent = $this->event->mainEvent;

        $this->assertEquals($matchC->id, $mainEvent->id);
    }

    /** @test */
    public function an_event_can_add_a_match()
    {
        $match = factory(Match::class)->create();

        $this->event->addMatch($match);

        $this->assertEquals($match->event_id, $this->event->id);
    }
}
