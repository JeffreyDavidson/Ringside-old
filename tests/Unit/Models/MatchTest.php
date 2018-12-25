<?php

namespace Tests\Unit\Models;

use App\Models\Event;
use App\Models\Match;
use App\Models\Title;
use App\Models\Stipulation;
use App\Models\Roster\Referee;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;

class MatchTest extends IntegrationTestCase
{
    protected $match;

    public function setUp()
    {
        parent::setUp();

        $this->match = factory(Match::class)->create();
    }

    /** @test */
    public function a_match_can_add_a_wrestler()
    {
        $match = factory(Match::class)->create();
        $wrestler = factory(Wrestler::class)->create();

        $match->addCompetitor($wrestler, 1);

        $this->assertCount(1, $match->competitors);
    }

    /** @test */
    public function a_match_can_add_multiple_wrestlers()
    {
        $match = factory(Match::class)->create();
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();

        $match->addCompetitors([[$wrestlerA], [$wrestlerB]]);

        $this->assertCount(2, $match->competitors);
    }

    /** @test */
    public function a_match_can_add_a_title()
    {
        $match = factory(Match::class)->create();
        $title = factory(Title::class)->make();

        $match->addTitle($title);

        $this->assertCount(1, $match->titles);
        $this->assertTrue($match->isTitleMatch());
    }

    /** @test */
    public function a_match_can_multiple_titles()
    {
        $match = factory(Match::class)->create();
        $titles = factory(Title::class, 2)->create();

        $match->addTitles($titles->pluck('id'));

        $this->assertCount(2, $match->titles);
        $this->assertTrue($match->isTitleMatch());
    }

    /** @test */
    public function a_match_can_add_a_stipulation()
    {
        $match = factory(Match::class)->create();
        $stipulation = factory(Stipulation::class)->create();

        $match->addStipulation($stipulation);

        $this->assertTrue($match->stipulation->is($stipulation));
    }

    /** @test */
    public function a_match_can_add_a_referee()
    {
        $match = factory(Match::class)->create();
        $referee = factory(Referee::class)->make();

        $match->addReferee($referee);

        $this->assertCount(1, $match->referees);
    }

    /** @test */
    public function a_match_can_add_multiple_referees()
    {
        $match = factory(Match::class)->create();
        $referees = factory(Referee::class, 2)->create();

        $match->addReferees($referees->pluck('id'));

        $this->assertCount(2, $match->referees);
    }

    /** @test */
    public function a_match_can_return_its_event_date()
    {
        $event = factory(Event::class)->create(['date' => '2018-02-01']);
        $match = factory(Match::class)->make(['event_id' => $event->id]);

        $this->assertEquals('2018-02-01', $match->date->toDateString());
    }

    /** @test */
    public function a_match_can_be_added_to_an_event()
    {
        $event = factory(Event::class)->create();
        $match = factory(Match::class)->create();

        $match->addToEvent($event);

        $this->assertEquals($match->id, $event->mainEvent->id);
    }

    /** @test */
    public function match_number_is_incremented_by_1_on_create()
    {
        $event = factory(Event::class)->create();
        $matchA = factory(Match::class)->create(['event_id' => $event->id]);
        $matchB = factory(Match::class)->create(['event_id' => $event->id]);
        $matchC = factory(Match::class)->create(['event_id' => $event->id]);

        $this->assertEquals(1, $matchA->match_number);
        $this->assertEquals(2, $matchB->match_number);
        $this->assertEquals(3, $matchC->match_number);
    }

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
