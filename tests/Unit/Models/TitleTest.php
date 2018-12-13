<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Match;
use App\Models\Title;
use App\Traits\Retirable;
use Facades\MatchFactory;
use App\Traits\Statusable;
use App\Models\Championship;
use App\Models\Roster\TagTeam;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TitleTest extends IntegrationTestCase
{
    /** @test */
    public function a_title_has_a_name()
    {
        $title = factory(Title::class)->create(['name' => 'Some Title']);

        $this->assertEquals('Some Title', $title->name);
    }

    /** @test */
    public function a_title_has_a_slug()
    {
        $title = factory(Title::class)->create(['slug' => 'some-slug']);

        $this->assertEquals('some-slug', $title->slug);
    }

    /** @test */
    public function a_title_has_an_is_active_field()
    {
        $title = factory(Title::class)->create(['is_active' => true]);

        $this->assertTrue($title->is_active);
    }

    /** @test */
    public function a_title_has_an_introduced_at_date()
    {
        $title = factory(Title::class)->create(['introduced_at' => Carbon::parse('2018-10-31')]);

        $this->assertEquals('2018-10-31', $title->introduced_at->toDateString());
    }

    /** @test */
    public function a_title_uses_the_statusable_trait()
    {
        $this->assertTrue(in_array(Statusable::class, class_uses(Title::class)));
    }

    /** @test */
    public function a_title_uses_the_retirable_trait()
    {
        $this->assertTrue(in_array(Retirable::class, class_uses(Title::class)));
    }

    /** @test */
    public function a_title_uses_the_presentable_trait()
    {
        $this->assertTrue(in_array(Presentable::class, class_uses(Title::class)));
    }

    /** @test */
    public function a_title_uses_the_soft_deletes_trait()
    {
        $this->assertTrue(in_array(SoftDeletes::class, class_uses(Title::class)));
    }

    /** @test */
    public function a_title_has_many_champions()
    {
        $title = factory(Title::class)->create();
        $championship = factory(Championship::class)->create(['title_id' => $title->id]);

        $this->assertCount(1, $title->championships);
    }

    /** @test */
    public function a_title_can_be_held_by_a_wrestler()
    {
        $title = factory(Title::class)->create();
        $wrestler = factory(Wrestler::class)->create();
        factory(Championship::class)->create(['title_id' => $title->id, 'champion_id' => $wrestler->id]);

        $this->assertTrue($title->currentChampion->is($wrestler));
    }

    /** @test */
    public function a_title_can_be_held_by_a_tag_team()
    {
        $title = factory(Title::class)->create();
        $tagteam = factory(TagTeam::class)->create();
        factory(Championship::class)->create(['title_id' => $title->id, 'champion_id' => $tagteam->id]);

        $this->assertTrue($title->currentChampion->is($tagteam));
    }

    /** @test */
    public function a_title_that_is_lost_by_the_current_champion_is_now_the_previous_champion()
    {
        $title = factory(Title::class)->create();
        $formerChampion = factory(Wrestler::class)->create();
        $newChampion = factory(Wrestler::class)->create();
        factory(Championship::class)->create(['title_id' => $title->id, 'champion_id' => $formerChampion->id, 'won_on' => Carbon::parse('2018-10-01'), 'lost_on' => Carbon::parse('2018-10-08')]);
        factory(Championship::class)->create(['title_id' => $title->id, 'champion_id' => $newChampion->id, 'won_on' => Carbon::parse('2018-10-08')]);

        $this->assertTrue($title->fresh()->previousChampion->is($formerChampion));
        $this->assertTrue($title->fresh()->currentChampion->is($newChampion));
    }

    /** @test */
    public function a_title_without_a_current_champion_is_vacant()
    {
        $title = factory(Title::class)->create();

        $this->assertTrue($title->isVacant());
    }

    /** @test */
    public function it_can_get_a_titles_first_match_date()
    {
        $title = factory(Title::class)->create();
        $eventA = factory(Event::class)->create(['date' => now()->subMonths(1)]);
        $eventC = factory(Event::class)->create(['date' => now()->subMonths(8)]);
        $eventB = factory(Event::class)->create(['date' => now()->subMonths(3)]);
        $matchA = MatchFactory::forEvent($eventA)->withTitle($title)->create();
        $matchB = MatchFactory::forEvent($eventB)->withTitle($title)->create();
        $matchC = MatchFactory::forEvent($eventC)->withTitle($title)->create();

        $this->assertEquals($eventC->date, $title->first_match_date);
    }

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
}
