<?php

namespace Tests\Unit;

use App\Models\Match;
use App\Models\Stipulation;
use App\Models\MatchType;
use App\Models\Title;
use App\Models\Wrestler;
use App\Models\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MatchTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_match_must_have_a_type()
    {
        $type = factory(MatchType::class)->create();
        $match = factory(Match::class)->create(['match_type_id' => $type->id]);

        $this->assertEquals($match->type->id, $type->id);
    }

    /** @test */
    function matches_can_have_titles()
    {
        $match = factory(Match::class)->create();

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection', $match->titles
        );
    }

    /** @test */
    function matches_can_have_referees()
    {
        $match = factory(Match::class)->create();

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection', $match->referees
        );
    }

    /** @test */
    function matches_can_have_stipulations()
    {
        $match = factory(Match::class)->create();

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection', $match->stipulations
        );
    }

    /** @test */
    function matches_have_many_wrestlers()
    {
        $match = factory(Match::class)->create();

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection', $match->wrestlers
        );
    }

    /** @test */
    function a_match_is_apart_of_an_event()
    {
        $event = factory(Event::class)->create();
        $match = factory(Match::class)->create(['event_id' => $event->id]);

        $this->assertEquals($match->event_id, $event->id);
    }
}
