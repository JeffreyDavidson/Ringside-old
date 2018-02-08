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
    public function a_match_can_have_a_title_being_competed_for()
    {
        $match = factory(Match::class)->create();
        $title = factory(Title::class)->create();

        $match->addTitle($title);

        $this->assertCount(1, $match->titles);
        $this->assertTrue($match->isTitleMatch());
    }

    /** @test */
    public function can_have_more_than_one_title()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $titles = factory(Title::class, 2)->create();

        $match->addTitles($titles);

        $this->assertCount(2, $match->titles);
    }

    /** @test */
    public function must_have_a_referee()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $referee = factory(Referee::class)->create();

        $match->addReferee($referee);

        $this->assertCount(1, $match->referees);
    }

    /** @test */
    public function can_have_more_than_one_referee()
    {
        $event = factory(Event::class)->create();
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $referees = factory(Referee::class, 2)->create();

        $match->addReferees($referees);

        $this->assertCount(2, $match->referees);
    }

    /** @test */
    public function can_have_one_stipulation()
    {
        $event = factory(Event::class)->create();
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $stipulation = factory(Stipulation::class)->create();

        $match->addStipulation($stipulation);

        $this->assertCount(1, $match->stipulations);
    }

    /** @test */
    public function can_have_more_than_one_stipulation()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $stipulations = factory(Stipulation::class, 2)->create();

        $match->addStipulations($stipulations);

        $this->assertCount(2, $match->stipulations);
    }

    /** @test */
    public function can_add_more_than_one_wrestler()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $wrestlers = factory(Wrestler::class, 2)->create();

        $match->addWrestlers($wrestlers);

        $this->assertCount(2, $match->wrestlers);
    }

    /** @test */
    public function can_add_one_wrestler()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $wrestler = factory(Wrestler::class)->create();

        $match->addWrestler($wrestler);

        $this->assertCount(1, $match->wrestlers);
    }

    /** @test */
    public function a_winner_can_be_set_for_a_title_match()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();
        $event = EventFactory::create();
        $match = MatchFactory::create(['event_id' => $event->id], [$wrestlerA, $wrestlerB], [], [$title], []);

        $match->setWinner($wrestlerA);

        $this->assertEquals($wrestlerA->id, $match->winner_id);
        $this->assertEquals($wrestlerB->id, $match->loser_id);
        $this->assertEquals($title->fresh()->champions->first()->id, $wrestlerA->id);
    }

    /** @test */
    public function a_winner_can_be_set_for_a_nontitle_match()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $match = factory(Match::class)->create();
        $match->addWrestlers([$wrestlerA, $wrestlerB]);

        $match->setWinner($wrestlerA);

        $this->assertEquals($wrestlerA->id, $match->winner_id);
        $this->assertEquals($wrestlerB->id, $match->loser_id);
    }
}
