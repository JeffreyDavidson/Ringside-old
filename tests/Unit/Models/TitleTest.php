<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Match;
use App\Models\Title;
use Facades\MatchFactory;
use App\Models\Championship;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;

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
    public function a_title_can_be_active()
    {
        $title = factory(Title::class)->create(['is_active' => true]);

        $this->assertTrue($title->isActive());
    }

    /** @test */
    public function a_title_can_be_introduced()
    {
        $title = factory(Title::class)->create(['introduced_at' => Carbon::parse('2018-10-31')]);

        $this->assertEquals('2018-10-31', $title->introduced_at->toDateString());
    }

    /** @test */
    public function a_title_has_many_champions()
    {
        $title = factory(Title::class)->create();
        factory(Championship::class)->create(['title_id' => $title->id]);

        $this->assertCount(1, $title->champions);
    }

    /** @test */
    public function a_title_is_held_by_a_wrestler()
    {
        $title = factory(Title::class)->create();
        $wrestler = factory(Wrestler::class)->create();
        factory(Championship::class)->create(['title_id' => $title->id, 'champion_id' => $wrestler->id]);

        $this->assertTrue($title->currentChampion->is($wrestler));
    }

    /** @test */
    public function a_title_that_is_lost_by_the_current_champion_is_now_the_previous_champion()
    {
        $title = factory(Title::class)->create();
        $currentChampion = factory(Wrestler::class)->create();
        $newChampion = factory(Wrestler::class)->create();
        factory(Championship::class)->create(['title_id' => $title->id, 'champion_id' => $currentChampion->id, 'won_on' => Carbon::parse('2018-10-01'), 'lost_on' => Carbon::parse('2018-10-08')]);
        factory(Championship::class)->create(['title_id' => $title->id, 'champion_id' => $newChampion->id, 'won_on' => Carbon::parse('2018-10-08')]);

        $this->assertTrue($title->fresh()->previousChampion->is($currentChampion));
        $this->assertTrue($title->fresh()->currentChampion->is($newChampion));
    }

    /** @test */
    public function a_title_without_a_current_champion_is_vacant()
    {
        $title = factory(Title::class)->create();

        $this->assertTrue($title->isVacant());
    }

    /** @test */
    public function it_can_get_active_titles()
    {
        $titleA = factory(Title::class)->states('active')->create();
        $titleB = factory(Title::class)->states('active')->create();
        $titleC = factory(Title::class)->states('inactive')->create();

        $activeTitles = Title::active()->get();

        $this->assertTrue($activeTitles->contains($titleA));
        $this->assertTrue($activeTitles->contains($titleB));
        $this->assertFalse($activeTitles->contains($titleC));
    }

    /** @test */
    public function it_can_get_inactive_titles()
    {
        $titleA = factory(Title::class)->states('inactive')->create();
        $titleB = factory(Title::class)->states('inactive')->create();
        $titleC = factory(Title::class)->states('active')->create();

        $inactiveTitles = Title::inactive()->get();

        $this->assertTrue($inactiveTitles->contains($titleA));
        $this->assertTrue($inactiveTitles->contains($titleB));
        $this->assertFalse($inactiveTitles->contains($titleC));
    }

    /** @test */
    public function it_can_get_retired_titles()
    {
        $titleA = factory(Title::class)->states('retired')->create();
        $titleB = factory(Title::class)->states('retired')->create();
        $titleC = factory(Title::class)->states('active')->create();

        $retiredTitles = Title::retired()->get();

        $this->assertTrue($retiredTitles->contains($titleA));
        $this->assertTrue($retiredTitles->contains($titleB));
        $this->assertFalse($retiredTitles->contains($titleC));
    }

    /** @test */
    public function an_inactive_title_can_be_activated()
    {
        $title = factory(Title::class)->states('inactive')->create();

        $title->activate();

        $this->assertTrue($title->isActive());
    }

    /** @test */
    public function an_active_title_can_be_deactivated()
    {
        $title = factory(Title::class)->states('active')->create();

        $title->deactivate();

        $this->assertFalse($title->isActive());
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_title_cannot_be_activated()
    {
        $title = factory(Title::class)->states('active')->create();

        $title->activate();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsInactiveException
     *
     * @test
     */
    public function an_inactive_title_cannot_be_deactivated()
    {
        $title = factory(Title::class)->states('inactive')->create();

        $title->deactivate();
    }

    /** @test */
    public function an_active_title_can_be_retired()
    {
        $title = factory(Title::class)->states('active')->create();

        $title->retire();

        $this->assertEquals(1, $title->retirements->count());
        $this->assertFalse($title->isActive());
        $this->assertTrue($title->isRetired());
        $this->assertNull($title->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_retired_title_can_be_unretired()
    {
        $title = factory(Title::class)->states('retired')->create();

        $title->unretire();

        $this->assertTrue($title->hasPastRetirements());
        $this->assertNotNull($title->retirements()->first()->ended_at);
        $this->assertTrue($title->isActive());
        $this->assertFalse($title->isRetired());
    }

    /**
     * @expectedException \App\Exceptions\ModelIsRetiredException
     *
     * @test
     */
    public function a_retired_title_cannot_be_retired()
    {
        $title = factory(Title::class)->states('retired')->create();

        $title->retire();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_title_cannot_be_unretired()
    {
        $title = factory(Title::class)->states('active')->create();

        $title->unretire();
    }

    public function it_can_get_a_titles_current_retirement()
    {
        $title = factory(Title::class)->create();

        $currentRetirement = factory(Retirement::class)->create(['retirable_id' => $title->id, 'retirable_type' => get_class($title), 'retired_at' => Carbon::parse('2018-10-08')]);
        $previousRetirement = factory(Retirement::class)->create(['retirable_id' => $title->id, 'retirable_type' => get_class($title), 'retired_at' => Carbon::parse('2018-10-01'), 'ended_at' => Carbon::parse('2018-10-06')]);
    
        $this->assertTrue($title->currentRetirement->is($currentRetirement));
        $this->assertCount(1, $title->pastRetirements);
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

        $scheduledMatches = $title->scheduledMatches()->get();

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

        $pastMatches = $title->pastMatches()->get();

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
