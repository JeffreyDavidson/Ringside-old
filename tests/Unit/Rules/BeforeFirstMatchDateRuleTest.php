<?php

namespace Tests\Feature\Unit;

use App\Models\Event;
use App\Models\Match;
use App\Models\Wrestler;
use App\Rules\BeforeFirstMatchDate;
use EventFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatchFactory;
use Tests\TestCase;

class BeforeFirstMatchDateRuleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestler_with_a_match_after_they_were_hired_cannot_have_their_hired_at_date_after_their_first_match()
    {
        // $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler A', 'hired_at' => '2017-10-08']);
        // $match = MatchFactory::create([], [$wrestlerA]);
        // $event = EventFactory::create(['date' => '2017-10-09'], [$match], 5);
        $wrestler = factory(Wrestler::class)->create(['name' => 'Wrestler A', 'hired_at' => '2017-10-07']);
        $event = factory(Event::class)->create(['date' => '2017-10-12']);
        // $match = MatchFactory::create(['event_id' => $event->id], [$wrestlerA, $wrestlerB]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->wrestlers()->saveMany([$wrestler]);
        // dd($match->wrestlers);
        $validator = new BeforeFirstMatchDate($wrestler);

        $this->assertFalse($validator->passes('hired_at', '2017-10-14'));
    }

    /** @test */
    public function a_wrestler_with_a_match_and_a_hired_at_date_before_the_first_match_can_be_updated()
    {
        $wrestlerA = factory(Wrestler::class)->create(['hired_at' => '2017-10-07']);
        $wrestlerB = factory(Wrestler::class)->create();
        $event = factory(Event::class)->create(['date' => '2017-10-12']);
        // $match = MatchFactory::create(['event_id' => $event->id], [$wrestlerA, $wrestlerB]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->wrestlers()->saveMany([$wrestlerA, $wrestlerB]);

        $validator = new BeforeFirstMatchDate($wrestlerA);

        $this->assertTrue($validator->passes('hired_at', '2017-10-10'));
    }
}
