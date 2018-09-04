<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Match;
use App\Models\Title;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Models\Stipulation;
use App\Models\MatchType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatchTest extends TestCase
{
    use RefreshDatabase;

    protected $match;

    public function setUp()
    {
        parent::setUp();

        $this->match = factory(Match::class)->create();
    }

    /** @test */
    public function a_match_has_many_competitors()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->match->competitors);
    }

    /** @test */
    public function a_match_belongs_to_an_event()
    {
        $this->assertInstanceOf(Event::class, $this->match->event);
    }

    /** @test */
    public function a_match_has_a_type()
    {
        $this->assertInstanceOf(MatchType::class, $this->match->type);
    }

    /** @test */
    public function a_match_can_have_many_titles()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->match->titles);
    }

    /** @test */
    public function a_match_can_have_many_referees()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->match->referees);
    }

    /** @test */
    public function a_match_can_have_a_stipulation()
    {
        $this->assertInstanceOf(Stipulation::class, $this->match->stipulation);
    }

    /** @test */
    public function a_match_can_add_a_wrestler()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->match->addWrestler($wrestler, 1);

        $this->assertCount(1, $this->match->wrestlers);
    }

    /** @test */
    public function a_match_can_add_multiple_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();

        $this->match->addWrestlers([[$wrestlerA], [$wrestlerB]]);

        $this->assertCount(2, $this->match->wrestlers);
    }

    /** @test */
    public function a_match_can_add_a_title()
    {
        $title = factory(Title::class)->make();

        $this->match->addTitle($title);

        $this->assertCount(1, $this->match->titles);
        $this->assertTrue($this->match->isTitleMatch());
    }

    /** @test */
    public function a_match_can_multiple_titles()
    {
        $titles = factory(Title::class, 2)->make();

        $this->match->addTitles($titles);

        $this->assertCount(2, $this->match->titles);
        $this->assertTrue($this->match->isTitleMatch());
    }

    /** @test */
    public function a_match_can_add_a_stipulation()
    {
        $stipulation = factory(Stipulation::class)->create();

        $this->match->addStipulation($stipulation);

        $this->assertTrue($this->match->stipulation->is($stipulation));
    }

    /** @test */
    public function a_match_can_add_a_referee()
    {
        $referee = factory(Referee::class)->make();

        $this->match->addReferee($referee);

        $this->assertCount(1, $this->match->referees);
    }

    /** @test */
    public function a_match_can_add_multiple_referees()
    {
        $referees = factory(Referee::class, 2)->make();

        $this->match->addReferees($referees);

        $this->assertCount(2, $this->match->referees);
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
