<?php

namespace Tests\Unit\Models;

use App\Models\Event;
use App\Models\Match;
use Facades\MatchFactory;
use App\Models\Stipulation;
use Tests\IntegrationTestCase;

class StipulationTest extends IntegrationTestCase
{
    /** @test */
    public function a_stipulation_has_a_name()
    {
        $stipulation = factory(Stipulation::class)->create(['name' => 'Some Stipulation']);

        $this->assertEquals('Some Stipulation', $stipulation->name);
    }

    /** @test */
    public function a_stipulation_has_a_slug()
    {
        $stipulation = factory(Stipulation::class)->create(['slug' => 'some-stipulation']);

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
    public function it_can_get_a_stipulations_first_match_date()
    {
        $stipulation = factory(Stipulation::class)->create();
        $eventA = factory(Event::class)->create(['date' => now()->subMonths(1)]);
        $eventC = factory(Event::class)->create(['date' => now()->subMonths(8)]);
        $eventB = factory(Event::class)->create(['date' => now()->subMonths(3)]);
        $matchA = MatchFactory::forEvent($eventA)->withStipulation($stipulation)->create();
        $matchB = MatchFactory::forEvent($eventB)->withStipulation($stipulation)->create();
        $matchC = MatchFactory::forEvent($eventC)->withStipulation($stipulation)->create();
 
        $this->assertEquals($eventC->date, $stipulation->first_match_date);
    }

    /** @test */
    public function it_can_get_a_stipulations_past_matches()
    {
        $stipulation = factory(Stipulation::class)->create();
        $pastEventA = factory(Event::class)->create(['date' => now()->subMonths(1)]);
        $pastEventB = factory(Event::class)->create(['date' => now()->subMonths(8)]);
        $scheduledEvent = factory(Event::class)->create(['date' => now()->addMonths(3)]);
        $matchA = MatchFactory::forEvent($pastEventA)->withStipulation($stipulation)->create();
        $matchB = MatchFactory::forEvent($pastEventB)->withStipulation($stipulation)->create();
        $matchC = MatchFactory::forEvent($scheduledEvent)->withStipulation($stipulation)->create();
        
        $pastMatches = $stipulation->pastMatches()->get();

        $this->assertTrue($stipulation->hasPastMatches());
        $this->assertTrue($pastMatches->contains($matchA));
        $this->assertTrue($pastMatches->contains($matchB));
        $this->assertFalse($pastMatches->contains($matchC));
    }

    /** @test */
    public function it_can_get_a_stipulations_scheduled_matches()
    {
        $stipulation = factory(Stipulation::class)->create();
        $scheduledEventA = factory(Event::class)->create(['date' => now()->addMonths(1)]);
        $scheduledEventB = factory(Event::class)->create(['date' => now()->addMonths(3)]);
        $pastEvent = factory(Event::class)->create(['date' => now()->subMonths(8)]);
        $matchA = MatchFactory::forEvent($scheduledEventA)->withStipulation($stipulation)->create();
        $matchB = MatchFactory::forEvent($scheduledEventB)->withStipulation($stipulation)->create();
        $matchC = MatchFactory::forEvent($pastEvent)->withStipulation($stipulation)->create();

        $scheduledMatches = $stipulation->scheduledMatches()->get();

        $this->assertTrue($scheduledMatches->contains($matchA));
        $this->assertTrue($scheduledMatches->contains($matchB));
        $this->assertFalse($scheduledMatches->contains($matchC));
    }
}
