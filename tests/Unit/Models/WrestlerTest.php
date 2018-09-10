<?php

namespace Tests\Unit\Models;

use App\Models\Wrestler;
use App\Models\Title;
use App\Models\Manager;
use App\Models\Event;
use App\Models\Match;
use Facades\ChampionshipFactory;
use Facades\MatchFactory;
use Facades\ManagerFactory;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WrestlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_wrestlers_hired_before_a_certain_date()
    {
        $wrestlerA = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2016-12-31')]);
        $wrestlerB = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2014-12-31')]);
        $wrestlerC = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2017-01-01')]);

        $hiredWrestlers = Wrestler::hiredBefore(Carbon::parse('2017-01-01'))->get();

        $this->assertTrue($hiredWrestlers->contains($wrestlerA));
        $this->assertTrue($hiredWrestlers->contains($wrestlerB));
        $this->assertFalse($hiredWrestlers->contains($wrestlerC));
    }

    /** @test */
    public function wrestlers_are_marked_as_active_depending_on_hired_date()
    {
        $wrestlerA = factory(Wrestler::class)->create(['hired_at' => Carbon::today()->subDays(2)]);
        $wrestlerB = factory(Wrestler::class)->create(['hired_at' => Carbon::today()]);
        $wrestlerC = factory(Wrestler::class)->create(['hired_at' => Carbon::today()->addDays(2)]);

        $this->assertTrue($wrestlerA->is_active);
        $this->assertTrue($wrestlerB->is_active);
        $this->assertFalse($wrestlerC->is_active);
    }

    /** @test */
    public function it_can_get_active_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->states('active')->create();
        $wrestlerB = factory(Wrestler::class)->states('active')->create();
        $wrestlerC = factory(Wrestler::class)->states('inactive')->create();

        $activeWrestlers = Wrestler::active()->get();

        $this->assertTrue($activeWrestlers->contains($wrestlerA));
        $this->assertTrue($activeWrestlers->contains($wrestlerB));
        $this->assertFalse($activeWrestlers->contains($wrestlerC));
    }

    /** @test */
    public function it_can_get_inactive_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->states('inactive')->create();
        $wrestlerB = factory(Wrestler::class)->states('inactive')->create();
        $wrestlerC = factory(Wrestler::class)->states('active')->create();

        $inactiveWrestlers = Wrestler::inactive()->get();

        $this->assertTrue($inactiveWrestlers->contains($wrestlerA));
        $this->assertTrue($inactiveWrestlers->contains($wrestlerB));
        $this->assertFalse($inactiveWrestlers->contains($wrestlerC));
    }

    /** @test */
    public function it_can_activate_an_inactive_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $wrestler->activate();

        $this->assertTrue($wrestler->is_active);
    }

    /** @test */
    public function it_can_deactivate_an_active_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->deactivate();

        $this->assertFalse($wrestler->is_active);
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_wrestler_cannot_be_activated()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->activate();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsInactiveException
     *
     * @test
     */
    public function an_inactive_wrestler_cannot_be_deactivated()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $wrestler->deactivate();
    }

    /** @test */
    public function an_active_wrestler_can_retire()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->retire();

        $this->assertEquals(1, $wrestler->retirements->count());
        $this->assertFalse($wrestler->is_active);
        $this->assertTrue($wrestler->isRetired());
        $this->assertNull($wrestler->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_retired_wrestler_can_unretire()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->unretire();

        $this->assertNotNull($wrestler->retirements()->first()->ended_at);
        $this->assertTrue($wrestler->is_active);
        $this->assertFalse($wrestler->isRetired());
    }

    /** @test */
    public function it_can_get_retired_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->states('retired')->create();
        $wrestlerB = factory(Wrestler::class)->states('retired')->create();
        $wrestlerC = factory(Wrestler::class)->states('active')->create();

        $retiredWrestlers = Wrestler::retired()->get();

        $this->assertTrue($retiredWrestlers->contains($wrestlerA));
        $this->assertTrue($retiredWrestlers->contains($wrestlerB));
        $this->assertFalse($retiredWrestlers->contains($wrestlerC));
    }

    /**
     * @expectedException \App\Exceptions\ModelIsRetiredException
     *
     * @test
     */
    public function a_retired_wrestler_cannot_retire()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->retire();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_wrestler_cannot_unretire()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->unretire();
    }

    /** @test */
    public function it_can_get_suspended_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->states('suspended')->create();
        $wrestlerB = factory(Wrestler::class)->states('suspended')->create();
        $wrestlerC = factory(Wrestler::class)->states('active')->create();

        $suspendedWrestlers = Wrestler::suspended()->get();

        $this->assertTrue($suspendedWrestlers->contains($wrestlerA));
        $this->assertTrue($suspendedWrestlers->contains($wrestlerB));
        $this->assertFalse($suspendedWrestlers->contains($wrestlerC));
    }

    /** @test */
    public function an_active_wrestler_can_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->suspend();

        $this->assertEquals(1, $wrestler->suspensions->count());
        $this->assertFalse($wrestler->is_active);
        $this->assertNull($wrestler->suspensions()->first()->ended_at);
        $this->assertTrue($wrestler->isSuspended());
    }

    /** @test */
    public function a_suspended_wrestler_can_be_reinstated()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->reinstate();

        $this->assertNotNull($wrestler->suspensions->last()->ended_at);
        $this->assertTrue($wrestler->is_active);
        $this->assertFalse($wrestler->isSuspended());
        $this->assertTrue($wrestler->hasPastSuspensions());
        $this->assertEquals(1, $wrestler->pastSuspensions->count());
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_wrestler_cannot_be_reinstated()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->reinstate();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsSuspendedException
     *
     * @test
     */
    public function a_suspended_wrestler_cannot_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->suspend();
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
    public function a_wrestler_without_matches_before_current_date_has_no_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertFalse($wrestler->hasPastMatches());
    }

    /** @test */
    public function it_can_retrieve_a_wrestlers_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();
        $event = factory(Event::class)->create(['date' => '2017-10-09']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
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
    public function a_wrestler_can_win_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title, Carbon::now());

        $this->assertTrue($wrestler->isCurrentlyAChampion());
        $this->assertTrue($wrestler->hasTitle($title));
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadyHasTitleException
     *
     * @test
     */
    public function a_wrestler_who_has_a_title_cannot_win_the_same_title_without_losing_it()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();
        $wrestler->winTitle($title, Carbon::yesterday());

        $wrestler->winTitle($title, Carbon::now());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotTitleChampionException
     *
     * @test
     */
    public function a_wrestler_who_does_not_have_a_current_title_cannot_lose_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->loseTitle($title, Carbon::now());
    }

    /** @test */
    public function current_titles_held_returns_a_collection_of_active_titles()
    {
        $wrestler = factory(Wrestler::class)->create();
        $currentChampionshipA = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::today()->subMonths(2))->create();
        $currentChampionshipB = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::yesterday())->create();
        $pastChampionship = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::today()->subDays(4))->lostOn(Carbon::yesterday())->create();

        $currentTitlesHeld = $wrestler->currentTitlesHeld;

        $this->assertTrue($currentTitlesHeld->contains('id', $currentChampionshipA->title_id));
        $this->assertTrue($currentTitlesHeld->contains('id', $currentChampionshipB->title_id));
        $this->assertFalse($currentTitlesHeld->contains('id', $pastChampionship->title_id));
    }

    /** @test */
    public function past_titles_held_returns_a_collection_of_past_titles()
    {
        $wrestler = factory(Wrestler::class)->create();
        $pastChampionshipA = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::today()->subMonths(2))->lostOn(Carbon::today()->subMonths(1))->create();
        $pastChampionshipB = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::today()->subWeeks(3))->lostOn(Carbon::today()->subWeeks(2))->create();
        $currentChampionship = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::yesterday())->create();

        $pastTitlesHeld = $wrestler->pastTitlesHeld;

        $this->assertTrue($pastTitlesHeld->contains('id', $pastChampionshipA->title_id));
        $this->assertTrue($pastTitlesHeld->contains('id', $pastChampionshipB->title_id));
        $this->assertFalse($pastTitlesHeld->contains('id', $currentChampionship->title_id));
    }

    /** @test */
    public function an_active_wrestler_can_be_injured()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->injure();

        $this->assertEquals(1, $wrestler->injuries->count());
        $this->assertFalse($wrestler->is_active);
        $this->assertNull($wrestler->injuries()->first()->healed_at);
        $this->assertTrue($wrestler->isInjured());
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_wrestler_cannot_recover_from_an_injury_without_being_injured()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->recover();
    }

    /** @test */
    public function an_injured_wrestler_can_recover_from_an_injury()
    {
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $wrestler->recover();

        $this->assertEquals(1, $wrestler->pastInjuries->count());
        $this->assertTrue($wrestler->is_active);
        $this->assertFalse($wrestler->isInjured());
    }

    /**
     * @expectedException \App\Exceptions\ModelIsInjuredException
     *
     * @test
     */
    public function an_injured_wrestler_cannot_be_injured_without_being_healed_first()
    {
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $wrestler->injure();
    }

    /** @test */
    public function it_can_get_injured_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->states('injured')->create();
        $wrestlerB = factory(Wrestler::class)->states('injured')->create();
        $wrestlerC = factory(Wrestler::class)->states('active')->create();

        $injuredWrestlers = Wrestler::injured()->get();

        $this->assertTrue($injuredWrestlers->contains($wrestlerA));
        $this->assertTrue($injuredWrestlers->contains($wrestlerB));
        $this->assertFalse($injuredWrestlers->contains($wrestlerC));
    }

    /** @test */
    public function a_wrestler_can_hire_an_active_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->states('active')->create();

        $wrestler->hireManager($manager, Carbon::yesterday());

        $this->assertEquals(1, $wrestler->currentManagers->count());
        $this->assertTrue($wrestler->currentManagers->contains($manager));
    }

    /**
     * @expectedException \App\Exceptions\ModelIsInactiveException
     *
     * @test
     */
    public function a_wrestler_cannot_hire_an_inactive_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->states('inactive')->create();

        $wrestler->hireManager($manager, Carbon::yesterday());
    }

    /**
     * @expectedException \App\Exceptions\ModelHasManagerException
     *
     * @test
     */
    public function a_wrestler_cannot_hire_a_manager_they_already_have()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();
        $wrestler->hireManager($manager, Carbon::yesterday());

        $wrestler->hireManager($manager, Carbon::today());
    }

    /** @test */
    public function a_wrestler_can_fire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager, Carbon::yesterday());
        $wrestler->fireManager($manager, Carbon::today());

        $this->assertFalse($wrestler->currentManagers->contains($manager));
        $this->assertTrue($wrestler->pastManagers->contains($manager));
    }

    /**
     * @expectedException \App\Exceptions\ManagerNotHiredException
     *
     * @test
     */
    public function a_wrestler_cannot_fire_a_manager_they_do_not_have()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->fireManager($manager, Carbon::today());
    }

    /** @test */
    public function it_can_retrieve_a_wrestlers_current_managers()
    {
        $wrestler = factory(Wrestler::class)->create();
        $currentManagerA = ManagerFactory::forWrestler($wrestler)->hiredOn(Carbon::today()->subMonths(5))->create();
        $currentManagerB = ManagerFactory::forWrestler($wrestler)->hiredOn(Carbon::today()->subMonths(2))->create();
        $pastManager = ManagerFactory::forWrestler($wrestler)->hiredOn(Carbon::today()->subWeeks(2))->firedOn(Carbon::yesterday())->create();

        $currentManagers = $wrestler->currentManagers;

        $this->assertTrue($currentManagers->contains($currentManagerA));
        $this->assertTrue($currentManagers->contains($currentManagerB));
        $this->assertFalse($currentManagers->contains($pastManager));
    }

    /** @test */
    public function it_can_retrieve_a_wrestlers_past_managers()
    {
        $wrestler = factory(Wrestler::class)->create();

        $pastManagerA = ManagerFactory::forWrestler($wrestler)->hiredOn(Carbon::today()->subMonths(5))->firedOn(Carbon::today()->subMonths(3))->create();
        $pastManagerB = ManagerFactory::forWrestler($wrestler)->hiredOn(Carbon::today()->subMonths(2))->firedOn(Carbon::today()->subWeeks(3))->create();
        $currentManager = ManagerFactory::forWrestler($wrestler)->hiredOn(Carbon::yesterday())->create();

        $pastManagers = $wrestler->pastManagers;

        $this->assertTrue($pastManagers->contains($pastManagerA));
        $this->assertTrue($pastManagers->contains($pastManagerB));
        $this->assertFalse($pastManagers->contains($currentManager));
    }
}
