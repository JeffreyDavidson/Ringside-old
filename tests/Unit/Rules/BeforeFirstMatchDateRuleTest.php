<?php

namespace Tests\Unit\Rules;

use App\Models\Event;
use App\Models\Title;
use App\Models\Roster\Wrestler;
use Facades\MatchFactory;
use Tests\IntegrationTestCase;
use App\Rules\BeforeFirstMatchDate;

class BeforeFirstMatchDateRuleTest extends IntegrationTestCase
{
    /** @test */
    public function a_wrestler_with_a_match_after_they_were_hired_cannot_have_their_hired_at_date_after_their_first_match()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-07']);
        $event = factory(Event::class)->create(['date' => '2017-10-10']);
        $match = MatchFactory::forEvent($event)->withWrestler($wrestler)->create();

        $validator = new BeforeFirstMatchDate($wrestler);

        $this->assertFalse($validator->passes('hired_at', '2017-10-14'));
    }

    /** @test */
    public function a_wrestler_with_a_match_and_a_hired_at_date_before_the_first_match_can_be_updated()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-10-07']);
        $event = factory(Event::class)->create(['date' => '2017-10-12']);
        $match = MatchFactory::forEvent($event)->withWrestler($wrestler)->create();

        $validator = new BeforeFirstMatchDate($wrestler);

        $this->assertTrue($validator->passes('hired_at', '2017-10-10'));
    }

    /** @test */
    public function a_title_with_a_match_after_it_was_introduced_cannot_have_their_introduced_at_date_after_their_first_match_date()
    {
        $title = factory(Title::class)->create(['introduced_at' => '2017-10-07']);
        $event = factory(Event::class)->create(['date' => '2017-10-09']);
        $match = MatchFactory::forEvent($event)->withTitle($title)->create();

        $validator = new BeforeFirstMatchDate($title);

        $this->assertFalse($validator->passes('introduced_at', '2017-10-14'));
    }

    /** @test */
    public function a_title_with_a_match_and_a_introduced_at_date_before_the_first_match_can_be_updated()
    {
        $title = factory(Title::class)->create(['introduced_at' => '2017-10-07']);
        $event = factory(Event::class)->create(['date' => '2017-10-12']);
        $match = MatchFactory::forEvent($event)->withTitle($title)->create();

        $validator = new BeforeFirstMatchDate($title);

        $this->assertTrue($validator->passes('introduced_at', '2017-10-10'));
    }
}
