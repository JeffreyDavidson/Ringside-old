<?php

namespace Tests\Unit;

use App\Event;
use App\Match;
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
}
