<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Title;
use App\Models\Wrestler;
use App\Models\Event;
use App\Models\Match;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasMatchesTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_with_matches_before_the_current_date_has_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();
        $event = factory(Event::class)->create(['date' => '2017-10-09']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addWrestler($wrestler, 0);

        $this->assertTrue($wrestler->hasPastMatches());
        $this->assertEquals(1, $wrestler->pastMatches->count());
        $this->assertEquals('2017-10-09', $wrestler->firstMatchDate()->toDateString());
    }

    /** @test */
    public function a_wrestler_without_matches_before_current_date_has_no_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertFalse($wrestler->hasPastMatches());
    }

    /** @test */
    public function a_title_with_matches_before_current_date_has_past_matches()
    {
        $title = factory(Title::class)->create();
        $event = factory(Event::class)->create(['date' => '2017-10-09']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addTitle($title);

        $this->assertTrue($title->hasPastMatches());
        $this->assertEquals(1, $title->pastMatches->count());
        $this->assertEquals('2017-10-09', $title->firstMatchDate()->toDateString());
    }

    /** @test */
    public function a_title_without_matches_before_current_date_has_no_past_matches()
    {
        $title = factory(Title::class)->create();

        $this->assertFalse($title->hasPastMatches());
    }

        /** @test */
        public function wrestlers_currently_scheduled_matches_can_be_viewed_on_wrestler_bio()
        {
            $scheduledMatchA = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::tomorrow());
            $scheduledMatchB = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::today());
            $pastMatch = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::today()->subWeeks(2));

            $response = $this->actingAs($this->authorizedUser)
                            ->get(route('wrestlers.show', $this->wrestler->id));

            $response->data('wrestler')->scheduledMatches->assertContains($scheduledMatchA);
            $response->data('wrestler')->scheduledMatches->assertContains($scheduledMatchB);
            $response->data('wrestler')->scheduledMatches->assertNotContains($pastMatch);
        }

        /** @test */
        public function wrestlers_past_matches_can_be_viewed_on_wrestler_bio()
        {
            $pastMatchA = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::yesterday());
            $pastMatchB = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::today()->subWeeks(2));
            $scheduledMatch = MatchFactory::createForWrestlerOnDate($this->wrestler, Carbon::today());

            $response = $this->actingAs($this->authorizedUser)
                            ->get(route('wrestlers.show', $this->wrestler->id));

            $response->data('wrestler')->pastMatches->assertContains($pastMatchA);
            $response->data('wrestler')->pastMatches->assertContains($pastMatchB);
            $response->data('wrestler')->pastMatches->assertNotContains($scheduledMatch);
        }
}
