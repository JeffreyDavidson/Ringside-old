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
        $matchA = factory(Match::class)->create(['event_id' => $this->event->id, 'match_number' => 1]);
        $matchB = factory(Match::class)->create(['event_id' => $this->event->id, 'match_number' => 2]);
        $matchC = factory(Match::class)->create(['event_id' => $this->event->id, 'match_number' => 3]);

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

    /** @test */
    public function an_event_can_be_archived()
    {
        $event = factory(Event::class)->create();

        $event->archive();

        $this->assertNotNull($event->archived_at);
    }
}
