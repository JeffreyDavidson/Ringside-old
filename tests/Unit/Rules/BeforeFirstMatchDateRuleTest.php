<?php

namespace Tests\Feature\Unit;

use App\Models\Event;
use App\Models\Match;
use App\Models\Wrestler;
use App\Rules\BeforeFirstMatchDate;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BeforeFirstMatchDateRuleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestler_with_a_match_and_a_hired_at_date_after_the_first_match_cannot_be_updated()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-08']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addWrestler($wrestler);

        $validator = new BeforeFirstMatchDate($wrestler);

        $this->assertFalse($validator->passes('hired_at', '2017-10-10'));
        $this->assertEquals('The hired at date cannot be AFTER the wrestler\'s first match.', $validator->message());
    }

    /** @test */
    public function a_wrestler_with_a_match_and_a_hired_at_date_before_the_first_match_can_be_updated()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-07']);
        $event = factory(Event::class)->create(['date' => '2017-10-11']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addWrestler($wrestler);

        $validator = new BeforeFirstMatchDate($wrestler);

        $this->assertTrue($validator->passes('hired_at', '2017-10-10'));
    }
}
