<?php

namespace Tests\Unit;

use App\Models\Match;
use App\Models\Referee;
use App\Models\MatchType;
use App\Models\Stipulation;
use App\Models\Title;
use App\Models\Wrestler;
use App\Models\Event;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MatchTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function can_have_a_title()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $title = factory(Title::class)->create();

        $match->addTitle($title);

        $this->assertCount(1, $match->titles);
    }

    /** @test */
    function can_have_more_than_one_title()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $titles = factory(Title::class, 2)->create();

        $match->addTitles($titles);

        $this->assertCount(2, $match->titles);
    }

    /** @test */
    function must_have_a_referee()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $referee = factory(Referee::class)->create();

        $match->addReferee($referee);

        $this->assertCount(1, $match->referees);
    }

    /** @test */
    function can_have_more_than_one_referee()
    {
        $event = factory(Event::class)->create();
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $referees = factory(Referee::class, 2)->create();

        $match->addReferees($referees);

        $this->assertCount(2, $match->referees);
    }

    /** @test */
    function can_have_one_stipulation()
    {
        $event = factory(Event::class)->create();
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $stipulation = factory(Stipulation::class)->create();

        $match->addStipulation($stipulation);

        $this->assertCount(1, $match->stipulations);
    }

    /** @test */
    function can_have_more_than_one_stipulation()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $stipulations = factory(Stipulation::class, 2)->create();

        $match->addStipulations($stipulations);

        $this->assertCount(2, $match->stipulations);
    }

    /** @test */
    function can_add_more_than_one_wrestler()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $wrestlers = factory(Wrestler::class, 2)->create();

        $match->addWrestlers($wrestlers);

        $this->assertCount(2, $match->wrestlers);
    }

    /** @test */
    function can_add_one_wrestler()
    {
        $event = factory(Event::class)->create(['date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $wrestler = factory(Wrestler::class)->create();

        $match->addWrestler($wrestler);

        $this->assertCount(1, $match->wrestlers);
    }
}
