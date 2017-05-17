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
    public function a_match_must_have_a_type()
    {
        $type = factory(MatchType::class)->create();
        $match = factory(Match::class)->create(['match_type_id' => $type->id]);

        $this->assertEquals($match->type->id, $type->id);
    }

    /** @test */
    public function a_match_can_have_titles_competed_in_it()
    {
        $titles = factory(Title::class, 2)->create();
        $match = factory(Match::class)->create();

        $match->addTitles($titles);

        $this->assertCount(2, $match->titles);
    }

    /** @test */
    public function a_match_can_have_stipulations_added_to_it()
    {
        $stipulations = factory(Stipulation::class, 2)->create();
        $match = factory(Match::class)->create();

        $match->addStipulations($stipulations);

        $this->assertCount(2, $match->stipulations);
    }

    /** @test */
    public function a_match_must_contain_at_least_two_wrestlers()
    {
        $wrestlers = factory(Wrestler::class, 2)->create();
        $match = factory(Match::class)->create();

        $wrestlers->each(function($wrestler) use ($match) {
			$match->addWrestler($wrestler);
		});

        $this->assertCount(2, $match->wrestlers);
    }

    /** @test */
    public function a_match_is_apart_of_an_event()
    {
        $event = factory(Event::class)->create();
        $match = factory(Match::class)->create(['event_id' => $event->id]);

        $this->assertEquals($match->event_id, $event->id);
    }
}