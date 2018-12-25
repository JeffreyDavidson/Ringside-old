<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Match;
use App\Models\Venue;
use Tests\IntegrationTestCase;

class EventTest extends IntegrationTestCase
{
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
        $event = factory(Event::class)->create(['archived_at' => Carbon::yesterday()]);

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
     * @expectedException \App\Exceptions\EventAlreadyArchivedException
     *
     * @test
     */
    public function an_archived_event_cannot_be_archived()
    {
        $event = factory(Event::class)->states('archived')->create();

        $event->archive();
    }

    /**
     * @expectedException \App\Exceptions\EventNotAlreadyArchivedException
     *
     * @test
     */
    public function an_event_that_is_not_archived_cannot_be_unarchived()
    {
        $event = factory(Event::class)->states('past')->create();

        $event->unarchive();
    }

    /** @test */
    public function event_date_can_be_formatted()
    {
        $event = factory(Event::class)->make(['date' => '2017-04-01 12:00:00']);

        $this->assertEquals('April 1st, 2017', $event->formatted_date);
    }

    /** @test */
    public function event_date_can_be_formatted_for_a_on_a_form()
    {
        $event = factory(Event::class)->make(['date' => '2017-04-01 12:00:00']);

        $this->assertEquals('04/01/2017', $event->formatted_form_date);
    }

    /** @test */
    public function event_time_formatted()
    {
        $event = factory(Event::class)->make(['date' => '2017-04-01 12:00:00']);

        $this->assertEquals('12:00pm', $event->time);
    }
}
