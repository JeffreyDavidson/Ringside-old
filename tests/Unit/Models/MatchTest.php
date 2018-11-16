<?php

namespace Tests\Unit\Models;

use App\Models\Event;
use App\Models\Match;
use App\Models\Title;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Models\Stipulation;
use Tests\IntegrationTestCase;

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

        $match->addWrestler($wrestler, 1);

        $this->assertCount(1, $match->wrestlers);
    }

    /** @test */
    public function a_match_can_add_multiple_wrestlers()
    {
        $match = factory(Match::class)->create();
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();

        $match->addWrestlers([[$wrestlerA->id], [$wrestlerB->id]]);

        $this->assertCount(2, $match->wrestlers);
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
}
