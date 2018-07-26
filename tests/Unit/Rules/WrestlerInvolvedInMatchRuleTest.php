<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Models\Wrestler;
use Facades\MatchFactory;
use App\Models\Event;
use App\Rules\WrestlerInvolvedInMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WrestlerInvolvedInMatchRuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_checks_to_see_if_the_wrestler_was_involved_in_the_match()
    {
        $wrestlerInMatch = factory(Wrestler::class)->create();
        $wrestlerNotInMatch = factory(Wrestler::class)->create();
        $event = factory(Event::class)->create();

        $match = MatchFactory::forEvent($event)->withWrestler($wrestlerInMatch)->create();

        $validator = new WrestlerInvolvedInMatch($match->match_number, $event);

        $this->assertTrue($validator->passes('', $wrestlerInMatch->id));
        $this->assertFalse($validator->passes('', $wrestlerNotInMatch->id));
    }
}
