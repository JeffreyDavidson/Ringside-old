<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Match;
use Facades\MatchFactory;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StipulationTest extends TestCase
{
    use RefreshDatabase;

    public function a_stipulation_has_a_name()
    {
        $stipulations = factory(Stipulation::class)->create(['name' => 'Some Stipulation']);

        $this->assertEquals('Some Stipulation', $stipulation->name);
    }

    public function a_stipulation_has_a_slug()
    {
        $stipulations = factory(Stipulation::class)->create(['slug' => 'some-stipulation']);

        $this->assertEquals('some-stipulation', $stipulation->slug);
    }

    /** @test */
    public function a_stipulation_has_many_matches()
    {
        $stipulation = factory(Stipulation::class)->create();
        $matchA = factory(Match::class)->create(['stipulation_id' => $stipulation]);

        $this->assertCount(1, $stipulation->matches);
    }

    /** @test */
    public function it_can_get_the_stipulations_first_match_date()
    {
        $stipulation = factory(Stipulation::class)->create();
        $eventA = factory(Event::class)->create(['date' => now()->subMonths(1)]);
        $eventB = factory(Event::class)->create(['date' => now()->subMonths(3)]);
        $eventC = factory(Event::class)->create(['date' => now()->subMonths(8)]);
        $matchA = MatchFactory::forEvent($eventA)->withStipulation($stipulation)->create();
        $matchB = MatchFactory::forEvent($eventB)->withStipulation($stipulation)->create();
        $matchC = MatchFactory::forEvent($eventC)->withStipulation($stipulation)->create();
        dd($stipulation->matches);
        $this->assertEquals($eventC->date, $stipulation->firstMatchDate());
    }

    /** @test */
    public function it_can_get_the_stipulations_past_matches()
    {
        $stipulation = factory(Stipulation::class)->create();
        $eventA = factory(Event::class)->create(['date' => now()->subMonths(1)]);
        $eventB = factory(Event::class)->create(['date' => now()->addMonths(3)]);
        $eventC = factory(Event::class)->create(['date' => now()->subMonths(8)]);
        $matchA = MatchFactory::forEvent($eventA)->withStipulation($stipulation)->create();
        $matchB = MatchFactory::forEvent($eventB)->withStipulation($stipulation)->create();
        $matchC = MatchFactory::forEvent($eventC)->withStipulation($stipulation)->create();
        
        $pastMatches = $stipulation->pastMatches;

        dd($pastMatches);

        $this->assertEquals($eventC->date, $stipulation->firstMatchDate());
    }

    /** @test */
    public function it_can_get_the_stipulations_scheduled_matches()
    {
        $stipulation = factory(Stipulation::class)->create();
        $eventA = factory(Event::class)->create(['date' => now()->subMonths(1)]);
        $eventB = factory(Event::class)->create(['date' => now()->addMonths(3)]);
        $eventC = factory(Event::class)->create(['date' => now()->subMonths(8)]);
        $matchA = MatchFactory::forEvent($eventA)->withStipulation($stipulation)->create();
        $matchB = MatchFactory::forEvent($eventB)->withStipulation($stipulation)->create();
        $matchC = MatchFactory::forEvent($eventC)->withStipulation($stipulation)->create();

        $pastMatches = $stipulation->pastMatches;

        dd($pastMatches);

        $this->assertEquals($eventC->date, $stipulation->firstMatchDate());
    }
}
