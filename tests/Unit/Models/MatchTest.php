<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
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

        $this->match->addWrestlers([
            0 => [$wrestlerA],
            1 => [$wrestlerB]
        ]);

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
    public function a_match_can_set_a_winner()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $this->match->addWrestlers([[$wrestlerA], [$wrestlerB]]);

        $this->match->setWinner($wrestlerA, 'pinfall');

        $this->assertEquals($wrestlerA->id, $this->match->winner_id);
        $this->assertEquals($wrestlerB->id, $this->match->loser_id);
    }

    /** @test */
    public function a_title_champion_keeps_title_if_they_win_match()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();
        $title->setChampion($wrestlerA, Carbon::parse('2018-03-03'));

        $event = factory(Event::class)->create(['date' => '2018-03-05']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $wrestlerB = factory(Wrestler::class)->create();
        $match->addWrestlers([[$wrestlerA],[$wrestlerB]]);
        $match->addTitle($title);

        $match->setWinner($wrestlerA, 'dq');

        $this->assertEquals($wrestlerA, $title->currentChampion);
    }

    /** @test */
    public function a_title_changes_hands_when_a_non_champion_wins_a_title_match()
    {
        // $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler A']);
        // $title = factory(Title::class)->create();
        // $title->setChampion($wrestlerA, Carbon::parse('2018-03-03'));

        // $event = factory(Event::class)->create(['date' => Carbon::parse('2018-03-05')]);
        // $match = factory(Match::class)->create(['event_id' => $event->id]);
        // $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler B']);
        // $match->addWrestlers([
        //     0 => [$wrestlerA],
        //     1 => [$wrestlerB]
        // ]);
        // $match->addTitle($title);

        $match = factory(Match::class)->states('asTitleWithCurrentChampion')->create();
        $wrestlerA = $match->title->currentChampion;
        $wrestlerB = factory(Wrestler::class)->create();
        $match->addWrestlers([[$wrestlerA], [$wrestlerB]]);

        $match->setWinner($wrestlerB, 'pinfall');

        $title->refresh();
        $this->assertEquals($wrestlerB, $title->currentChampion);
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
        dd($match);

        $match->addToEvent($event);

        $this->assertEquals($match->id, $event->mainEvent->id);
    }
}
