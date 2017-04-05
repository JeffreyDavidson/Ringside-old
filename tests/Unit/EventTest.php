<?php

namespace Tests\Unit;

use App\Event;
use App\Match;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EventTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_event_have_many_matches()
    {
        $event = factory(Event::class)->create();

        factory(Match::class, 3)->create(['event_id' => $event->id]);

        $this->assertCount(3, $event->matches);
    }

    /** @test */
    public function more_than_one_match_for_an_event_cannot_have_the_same_match_number()
    {
        $event = factory(Match::class)->create();
        factory(Match::class)->create(['event_id' => $event->id, 'match_number' => 1]);

        try {
            factory(Match::class)->create(['event_id' => $event->id, 'match_number' => 1]);
        } catch (MatchesHaveSameMatchNumberAtEventException $e) {
            return;
        }


        $this->fail('More than one match have the same match number.');
    }

    /** @test */
    public function multiple_matches_can_be_added_to_an_event_at_once()
    {
        $event = factory(Event::class)->create();

        $matches = factory(Match::class, 3)->create();

//        dd($matches->toArray());

        $event->addMatches($matches->toArray());

        $this->assertCount(3, $event->matches()->count());
    }
}
