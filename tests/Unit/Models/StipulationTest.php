<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Match;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StipulationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_stipulation_past_matches_only_shows_matches_from_the_past()
    {
        $eventA = factory(Event::class)->create(['date' => Carbon::yesterday()]);
        $eventB = factory(Event::class)->create(['date' => Carbon::tomorrow()]);
        $matchA = factory(Match::class)->create(['event_id' => $eventA->id]);
        $matchB = factory(Match::class)->create(['event_id' => $eventB->id]);
        $stipulation = factory(Stipulation::class)->create();

        $matchA->addStipulation($stipulation);
        $matchB->addStipulation($stipulation);

        $this->assertTrue($stipulation->hasPastMatches());
        $this->assertTrue($stipulation->pastMatches()->contains($matchA));
        $this->assertFalse($stipulation->pastMatches()->contains($matchB));
    }

    public function a_stipulation_first_match_date_only_shows_the_date_it_first_happened()
    {
        $eventA = factory(Event::class)->create(['date' => Carbon::yesterday()]);
        $eventB = factory(Event::class)->create(['date' => Carbon::today()]);
        $matchA = factory(Match::class)->create(['event_id' => $eventA->id]);
        $matchB = factory(Match::class)->create(['event_id' => $eventB->id]);
        $stipulation = factory(Stipulation::class)->create();

        $matchA->addStipulation($stipulation);
        $matchB->addStipulation($stipulation);

        $this->assertEquals(Carbon::yesterday(), $stipulation->firstMatchDate());
    }
}
