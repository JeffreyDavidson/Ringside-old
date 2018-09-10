<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Title;
use App\Models\Event;
use App\Models\Match;
use Facades\MatchFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TitleTest extends TestCase
{
    use RefreshDatabase;

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
    public function an_inactive_title_can_be_activated()
    {
        $title = factory(Title::class)->states('inactive')->create();

        $title->activate();

        $this->assertTrue($title->is_active);
    }

    /** @test */
    public function an_active_title_can_be_deactivated()
    {
        $title = factory(Title::class)->states('active')->create();

        $title->deactivate();

        $this->assertFalse($title->is_active);
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
        $this->assertFalse($title->is_active);
        $this->assertTrue($title->isRetired());
        $this->assertNull($title->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_retired_title_can_be_unretired()
    {
        $title = factory(Title::class)->states('retired')->create();

        $title->unretire();

        $this->assertNotNull($title->retirements()->first()->ended_at);
        $this->assertTrue($title->is_active);
        $this->assertFalse($title->isRetired());
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
