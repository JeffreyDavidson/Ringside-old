<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Match;
use App\Models\Venue;
use App\Exceptions\MatchesHaveSameMatchNumberAtEventException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_event_have_many_matches()
    {
        $event = factory(Event::class)->create();

        $matches = factory(Match::class, 3)->create();

        $event->addMatches($matches);

        $this->assertCount(3, $event->matches);
    }

    /** @test */
    public function an_event_takes_place_at_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $event = factory(Event::class)->create(['venue_id' => $venue->id]);

        $this->assertEquals($venue->id, $event->venue_id);
    }

    /** @test */
    public function more_than_one_match_for_an_event_cannot_have_the_same_match_number()
    {
        $event = factory(Event::class)->create();

        $event->addMatches(factory(Match::class)->create(['match_number' => 1]));

        try {
            $event->addMatches(factory(Match::class)->create(['match_number' => 1]));

        } catch (MatchesHaveSameMatchNumberAtEventException $e) {
            return;
        }


        $this->fail('More than one match for an event can not have the same match number.');
    }

    /** @test */
    public function an_event_can_add_additional_matches()
    {
        $event = factory(Event::class)->create();

        $matches = factory(Match::class, 3)->create();

        $event->addMatches($matches);

        $moreMatches = factory(Match::class, 2)->create();

        $event->addMatches($moreMatches);

        $this->assertCount(5, $event->matches);
    }

    /** @test */
    public function event_date_can_be_formatted()
    {
        $event = factory(Event::class)->make(['date' => '2017-04-01 12:00:00']);

        $this->assertEquals('April 1st, 2017', $event->formatted_date);
    }
}
