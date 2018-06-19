<?php

namespace Tests\Unit\Presenters;

use stdClass;
use Tests\TestCase;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Models\Stipulation;
use App\Models\Match;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatchPresenterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_match_can_present_wrestlers_in_match()
    {
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler A']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler B']);
        $match = factory(Match::class)->create();
        $match->addWrestlers([
            0 => [$wrestlerA],
            1 => [$wrestlerB]
        ]);

        $this->assertEquals('Wrestler A vs. Wrestler B', $match->present()->wrestlers);
    }

    /** @test */
    public function a_match_can_present_multiple_wrestlers_on_the_same_side()
    {
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler A']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler B']);
        $wrestlerC = factory(Wrestler::class)->create(['name' => 'Wrestler C']);
        $wrestlerD = factory(Wrestler::class)->create(['name' => 'Wrestler D']);
        $match = factory(Match::class)->create();
        $match->addWrestlers([
            0 => [$wrestlerA, $wrestlerC],
            1 => [$wrestlerB, $wrestlerD]
        ]);

        $this->assertEquals('Wrestler A & Wrestler C vs. Wrestler B & Wrestler D', $match->present()->wrestlers);
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
    public function a_first_match_in_an_event_should_be_presented_as_the_opening_match()
    {
        $match = factory(Match::class)->create();
        $loop = new stdClass;
        $loop->first = true;
        $loop->last = false;

        $this->assertEquals('Opening Match', $match->present()->match_number($loop));
    }

    /** @test */
    public function the_last_match_in_an_event_should_be_presented_as_the_main_event()
    {
        $match = factory(Match::class)->create();
        $loop = new stdClass;
        $loop->first = false;
        $loop->last = true;

        $this->assertEquals('Main Event', $match->present()->match_number($loop));
    }

    /** @test */
    public function a_match_in_an_event_that_isnt_the_first_or_last_should_be_presented_correctly()
    {
        $match = factory(Match::class)->create(['match_number' => 2]);
        $loop = new stdClass;
        $loop->first = false;
        $loop->last = false;

        $this->assertEquals('Match #2', $match->present()->match_number($loop));
    }
}
