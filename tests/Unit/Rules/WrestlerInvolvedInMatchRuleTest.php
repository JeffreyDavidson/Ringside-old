<?php

namespace Tests\Unit\Rules;

use App\Models\Event;
use App\Models\Roster\Wrestler;
use Facades\MatchFactory;
use Tests\IntegrationTestCase;
use App\Rules\WrestlerInvolvedInMatch;

class WrestlerInvolvedInMatchRuleTest extends IntegrationTestCase
{
    /** @test */
    public function it_checks_to_see_if_the_wrestler_was_involved_in_the_match()
    {
        $wrestlerInMatch = factory(Wrestler::class)->create();
        $wrestlerNotInMatch = factory(Wrestler::class)->create();
        $event = factory(Event::class)->create();
        $match = MatchFactory::forEvent($event)->withWrestler($wrestlerInMatch)->create();;

        $validator = new WrestlerInvolvedInMatch($event, $match->match_number);

        $this->assertTrue($validator->passes('', $wrestlerInMatch->id));
        $this->assertFalse($validator->passes('', $wrestlerNotInMatch->id));
    }
}
