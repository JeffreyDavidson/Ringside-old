<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Match;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_last_match_in_an_event_is_the_main_event()
    {
        $event = factory(Event::class)->create();
        $matchA = factory(Match::class)->create(['event_id' => $event->id]);
        $matchB = factory(Match::class)->create(['event_id' => $event->id]);
        $matchC = factory(Match::class)->create(['event_id' => $event->id]);
        $matchB->update(['match_number' => 999]);

        $mainEvent = $event->mainEvent;

        $this->assertEquals($matchB->id, $mainEvent->id);
    }

    /** @test */
    public function an_event_that_has_a_date_before_todays_date_is_a_past_event()
    {
        $event = factory(Event::class)->create(['date' => Carbon::yesterday()]);

        $this->assertTrue($event->isPast());
    }

    /** @test */
    public function an_event_that_has_an_archived_date_is_archived()
    {
        $event = factory(Event::class)->states('archived')->create();

        $this->assertTrue($event->isArchived());
    }

    /** @test */
    public function a_scheduled_event_can_add_a_match()
    {
        $event = factory(Event::class)->states('scheduled')->create();
        $match = factory(Match::class)->create();

        $event->addMatch($match);

        $this->assertEquals($match->event_id, $event->id);
    }

    /** @test */
    public function a_past_event_can_be_archived()
    {
        $event = factory(Event::class)->states('past')->create();

        $event->archive();

        $this->assertNotNull($event->archived_at);
    }

    /**
     * @expectedException \App\Exceptions\EventIsScheduledException
     *
     * @test
     */
    public function a_scheduled_event_cannot_be_archived()
    {
        $event = factory(Event::class)->states('scheduled')->create();

        $event->archive();
    }

    /**
     * @expectedException \App\Exceptions\EventIsArchivedException
     *
     * @test
     */
    public function an_archived_event_cannot_be_archived()
    {
        $event = factory(Event::class)->states('archived')->create();

        $event->archive();
    }
}
