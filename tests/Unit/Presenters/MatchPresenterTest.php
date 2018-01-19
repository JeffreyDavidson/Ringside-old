<?php

namespace Tests\Unit;

use stdClass;
use EventFactory;
use MatchFactory;
use Tests\TestCase;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MatchPresenterTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_match_can_present_wrestlers_in_match()
    {
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler A']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler B']);
        $event = EventFactory::create(['date' => '2017-10-09']);
        $match = MatchFactory::create(['event_id' => $event->id], [$wrestlerA, $wrestlerB], [], []);

        $this->assertEquals('Wrestler A vs. Wrestler B', $match->present()->wrestlers);
    }

    /** @test */
    public function a_match_can_present_a_single_referee_in_a_match()
    {
        $referee = factory(Referee::class)->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $event = EventFactory::create(['date' => '2017-10-09']);
        $match = MatchFactory::create(['event_id' => $event->id], [], [$referee], [], []);

        $this->assertEquals('John Doe', $match->present()->referees);
    }

    /** @test */
    public function a_match_can_present_multiple_referees_in_a_match()
    {
        $refereeA = factory(Referee::class)->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $refereeB = factory(Referee::class)->create(['first_name' => 'Jane', 'last_name' => 'Scott']);
        $event = EventFactory::create(['date' => '2017-10-09']);
        $match = MatchFactory::create(['event_id' => $event->id], [], [$refereeA, $refereeB], [], []);

        $this->assertEquals('John Doe, Jane Scott', $match->present()->referees);
    }

    /** @test */
    public function a_match_can_present_a_single_stipulation_in_a_match()
    {
        $stipulation = factory(Stipulation::class)->create(['name' => 'Cage Match']);
        $event = EventFactory::create(['date' => '2017-10-09']);
        $match = MatchFactory::create(['event_id' => $event->id], [], [], [], [$stipulation]);

        $this->assertEquals('Cage Match', $match->present()->stipulations);
    }

    /** @test */
    public function a_match_can_present_multiple_stipulations_in_a_match()
    {
        $stipulationA = factory(Stipulation::class)->create(['name' => 'Cage Match']);
        $stipulationB = factory(Stipulation::class)->create(['name' => 'Ladder Match']);
        $event = EventFactory::create(['date' => '2017-10-09']);
        $match = MatchFactory::create(['event_id' => $event->id], [], [], [], [$stipulationA, $stipulationB]);

        $this->assertEquals('Cage Match, Ladder Match', $match->present()->stipulations);
    }

    /** @test */
    public function a_first_match_in_an_event_should_be_presented_as_the_opening_match()
    {
        $match = MatchFactory::create();
        $loop = new stdClass;
        $loop->first = true;
        $loop->last = false;

        $this->assertEquals('Opening Match', $match->present()->match_number($loop));
    }

    /** @test */
    public function the_last_match_in_an_event_should_be_presented_as_the_opening_match()
    {
        $match = MatchFactory::create();
        $loop = new stdClass;
        $loop->first = false;
        $loop->last = true;

        $this->assertEquals('Main Event', $match->present()->match_number($loop));
    }

    /** @test */
    public function a_match_in_an_event_that_isnt_the_first_or_last_should_be_presented_correctly()
    {
        $match = MatchFactory::create(['match_number' => 2]);
        $loop = new stdClass;
        $loop->first = false;
        $loop->last = false;

        $this->assertEquals('Match #2', $match->present()->match_number($loop));
    }
}
