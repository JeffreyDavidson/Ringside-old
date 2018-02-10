<?php

namespace Tests\Unit;

use EventFactory;
use MatchFactory;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Match;
use App\Models\Title;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Models\Stipulation;
use App\Models\MatchType;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MatchTest extends TestCase
{
    use DatabaseMigrations;

    protected $match;

    public function setUp()
    {
        parent::setUp();

        $this->match = factory(Match::class)->create();
    }

    /** @test */
    public function a_match_has_many_wrestlers()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->match->wrestlers);
    }

    /** @test */
    public function a_match_belongs_to_an_event()
    {
        $this->assertInstanceOf(Event::class, $this->match->event);
    }

    /** @test */
    public function a_match_has_has_a_type()
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
    public function a_match_can_have_many_stipulations()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->match->stipulations);
    }

    /** @test */
    public function a_match_can_add_a_wrestler()
    {
        $wrestler = factory(Wrestler::class)->make();

        $this->match->addWrestler($wrestler);

        $this->assertCount(1, $this->match->wrestlers);
    }

    /** @test */
    public function a_match_can_add_multiple_wrestlers()
    {
        $wrestlers = factory(Wrestler::class, 2)->make();

        $this->match->addWrestlers($wrestlers);

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
    public function a_match_can_add_one_stipulation()
    {
        $stipulation = factory(Stipulation::class)->make();

        $this->match->addStipulation($stipulation);

        $this->assertCount(1, $this->match->stipulations);
    }

    /** @test */
    public function a_match_can_add_multiple_stipulations()
    {
        $stipulations = factory(Stipulation::class, 2)->make();

        $this->match->addStipulations($stipulations);

        $this->assertCount(2, $this->match->stipulations);
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
    public function a_match_can_set_a_winner()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $this->match->addWrestlers([$wrestlerA, $wrestlerB]);

        $this->match->setWinner($wrestlerA);

        $this->assertEquals($wrestlerA->id, $this->match->winner_id);
        $this->assertEquals($wrestlerB->id, $this->match->loser_id);
    }

    /** @test */
    public function a_match_can_return_its_event_date()
    {
        $event = factory(Event::class)->create(['date' => '2018-02-01']);
        $match = factory(Match::class)->make(['event_id' => $event->id]);

        $this->assertEquals('2018-02-01', $match->date->toDateString());
    }

    /** @test */
    public function it_can_return_if_its_date_has_past()
    {
        $event = factory(Event::class)->create(['date' => Carbon::yesterday()]);
        $match = factory(Match::class)->make(['event_id' => $event->id]);

        $this->assertTrue($match->isPast());
    }

    /** @test */
    public function a_match_can_be_added_to_an_event()
    {
        $event = factory(Event::class)->create();
        $match = factory(Match::class)->create();

        $match->addToEvent($event);

        $this->assertEquals($event->id, $match->event_id);
        $this->assertEquals(1, $match->match_number);
    }
}
