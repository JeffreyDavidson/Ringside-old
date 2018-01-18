<?php

namespace Tests\Feature\Unit;

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
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-07']);
        $event = EventFactory::create(['date' => '2017-10-09']);
        $match = MatchFactory::create(['event_id' => $event->id], [$wrestler]);

        $validator = new BeforeFirstMatchDate($wrestler);

        $this->assertFalse($validator->passes('hired_at', '2017-10-14'));
    }

    /** @test */
    public function a_wrestler_with_a_match_and_a_hired_at_date_before_the_first_match_can_be_updated()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-07']);
        $event = EventFactory::create(['date' => '2017-10-12']);
        $match = MatchFactory::create(['event_id' => $event->id], [$wrestler]);

        $validator = new BeforeFirstMatchDate($wrestler);

        $this->assertTrue($validator->passes('hired_at', '2017-10-10'));
    }
}
