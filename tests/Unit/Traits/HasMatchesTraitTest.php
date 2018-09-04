<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Title;
use App\Models\Wrestler;
use App\Models\Event;
use App\Models\Match;
use Facades\MatchFactory;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasMatchesTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_retrieve_a_titles_scheduled_matches()
    {
        $title = factory(Title::class)->create();
        $eventA = factory(Event::class)->create(['date' => Carbon::tomorrow()]);
        $eventB = factory(Event::class)->create(['date' => Carbon::today()]);
        $eventC = factory(Event::class)->create(['date' => Carbon::today()->subWeeks(2)]);
        $scheduledMatchA = MatchFactory::forEvent($eventA)->withTitle($title)->create();
        $scheduledMatchB = MatchFactory::forEvent($eventB)->withTitle($title)->create();
        $pastMatch = MatchFactory::forEvent($eventC)->withTitle($title)->create();

        $scheduledMatches = $title->scheduledMatches;

        $this->assertTrue($scheduledMatches->contains($scheduledMatchA));
        $this->assertTrue($scheduledMatches->contains($scheduledMatchB));
        $this->assertFalse($scheduledMatches->contains($pastMatch));
    }

    /** @test */
    public function a_wrestler_without_matches_before_current_date_has_no_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertFalse($wrestler->hasPastMatches());
    }

    /** @test */
    public function it_can_retrieve_a_titles_past_matches()
    {
        $title = factory(Title::class)->create();
        $event = factory(Event::class)->create(['date' => '2017-10-09']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $eventA = factory(Event::class)->create(['date' => Carbon::yesterday()]);
        $eventB = factory(Event::class)->create(['date' => Carbon::today()->subWeeks(2)]);
        $eventC = factory(Event::class)->create(['date' => Carbon::tomorrow()]);
        $pastMatchA = MatchFactory::forEvent($eventA)->withTitle($title)->create();
        $pastMatchB = MatchFactory::forEvent($eventB)->withTitle($title)->create();
        $scheduledMatch = MatchFactory::forEvent($eventC)->withTitle($title)->create();

        $pastMatches = $title->pastMatches;

        $this->assertTrue($pastMatches->contains($pastMatchA));
        $this->assertTrue($pastMatches->contains($pastMatchB));
        $this->assertFalse($pastMatches->contains($scheduledMatch));
    }

    /** @test */
    public function a_title_without_matches_before_current_date_has_no_past_matches()
    {
        $title = factory(Title::class)->create();

        $this->assertFalse($title->hasPastMatches());
    }

    /** @test */
    public function it_can_retrieve_a_wrestlers_scheduled_matches()
    {
        $wrestler = factory(Wrestler::class)->create();
        $eventA = factory(Event::class)->create(['date' => Carbon::tomorrow()]);
        $eventB = factory(Event::class)->create(['date' => Carbon::today()]);
        $eventC = factory(Event::class)->create(['date' => Carbon::today()->subWeeks(2)]);
        $scheduledMatchA = MatchFactory::forEvent($eventA)->withWrestler($wrestler)->create();
        $scheduledMatchB = MatchFactory::forEvent($eventB)->withWrestler($wrestler)->create();
        $pastMatch = MatchFactory::forEvent($eventC)->withWrestler($wrestler)->create();

        $scheduledMatches = $wrestler->scheduledMatches;

        $this->assertTrue($scheduledMatches->contains($scheduledMatchA));
        $this->assertTrue($scheduledMatches->contains($scheduledMatchB));
        $this->assertFalse($scheduledMatches->contains($pastMatch));
    }

    /** @test */
    public function it_can_retrieve_a_wrestlers_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();
        $eventA = factory(Event::class)->create(['date' => Carbon::yesterday()]);
        $eventB = factory(Event::class)->create(['date' => Carbon::today()->subWeeks(2)]);
        $eventC = factory(Event::class)->create(['date' => Carbon::tomorrow()]);
        $pastMatchA = MatchFactory::forEvent($eventA)->withWrestler($wrestler)->create();
        $pastMatchB = MatchFactory::forEvent($eventB)->withWrestler($wrestler)->create();
        $scheduledMatch = MatchFactory::forEvent($eventC)->withWrestler($wrestler)->create();

        $pastMatches = $wrestler->pastMatches;

        $this->assertTrue($pastMatches->contains($pastMatchA));
        $this->assertTrue($pastMatches->contains($pastMatchB));
        $this->assertFalse($pastMatches->contains($scheduledMatch));
    }

    /** @test */
    public function a_wrestler_can_have_many_matches()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->matches);
    }
}
