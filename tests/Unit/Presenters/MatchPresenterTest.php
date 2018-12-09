<?php

namespace Tests\Unit\Presenters;

use stdClass;
use App\Models\Event;
use App\Models\Match;
use App\Models\Stipulation;
use App\Models\Roster\Referee;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;

class MatchPresenterTest extends IntegrationTestCase
{
    /** @test */
    public function a_match_can_present_wrestlers_in_match()
    {
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler A']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler B']);
        $match = factory(Match::class)->create();
        
        $match->addCompetitors([
            0 => [$wrestlerA->id],
            1 => [$wrestlerB->id]
        ]);

        $this->assertEquals('Wrestler A vs. Wrestler B', $match->present()->competitors);
    }

    /** @test */
    public function a_match_can_present_multiple_wrestlers_on_the_same_side()
    {
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler A']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler B']);
        $wrestlerC = factory(Wrestler::class)->create(['name' => 'Wrestler C']);
        $wrestlerD = factory(Wrestler::class)->create(['name' => 'Wrestler D']);
        $match = factory(Match::class)->create();

        $match->addCompetitors([
            0 => [$wrestlerA->id, $wrestlerC->id],
            1 => [$wrestlerB->id, $wrestlerD->id]
        ]);

        $this->assertEquals('Wrestler A & Wrestler C vs. Wrestler B & Wrestler D', $match->present()->competitors);
    }

    /** @test */
    public function a_match_can_present_a_single_referee_in_a_match()
    {
        $referee = factory(Referee::class)->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $match = factory(Match::class)->create();
        $match->addReferee($referee);

        $this->assertEquals('John Doe', $match->present()->referees);
    }

    /** @test */
    public function a_match_can_present_multiple_referees_in_a_match()
    {
        $refereeA = factory(Referee::class)->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $refereeB = factory(Referee::class)->create(['first_name' => 'Jane', 'last_name' => 'Scott']);
        $match = factory(Match::class)->create();
        $match->addReferees([$refereeA, $refereeB]);

        $this->assertEquals('John Doe & Jane Scott', $match->present()->referees);
    }

    /** @test */
    public function a_single_match_for_an_event_is_presented_as_the_main_event()
    {
        $event = factory(Event::class)->create();
        $match = factory(Match::class)->create(['event_id' => $event->id]);

        $this->assertEquals('Main Event', $match->fresh()->present()->match_number());
    }

    /** @test */
    public function match_numbers_in_an_event_should_be_presented_accordingly()
    {
        $event = factory(Event::class)->create();
        $matchA = factory(Match::class)->create(['event_id' => $event->id]);
        $matchB = factory(Match::class)->create(['event_id' => $event->id]);
        $matchC = factory(Match::class)->create(['event_id' => $event->id]);

        $this->assertEquals('Opening Match', $matchA->fresh()->present()->match_number());
        $this->assertEquals('Match #2', $matchB->fresh()->present()->match_number());
        $this->assertEquals('Main Event', $matchC->fresh()->present()->match_number());
    }
}
