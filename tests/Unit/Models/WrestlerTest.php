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

        $this->assertTrue($wrestlerA->isActive());
        $this->assertTrue($wrestlerB->isActive());
        $this->assertFalse($wrestlerC->isActive());
    }

    /** @test */
    public function it_can_get_active_wrestlers()
    {
        $activeWrestlerA = factory(Wrestler::class)->states('active')->create();
        $activeWrestlerB = factory(Wrestler::class)->states('active')->create();
        $inactiveWrestler = factory(Wrestler::class)->states('inactive')->create();

        $activeWrestlers = Wrestler::active()->get();

        $this->assertTrue($activeWrestlers->contains($activeWrestlerA));
        $this->assertTrue($activeWrestlers->contains($activeWrestlerB));
        $this->assertFalse($activeWrestlers->contains($inactiveWrestler));
    }

    /** @test */
    public function it_can_get_inactive_wrestlers()
    {
        $inactiveWrestlerA = factory(Wrestler::class)->states('inactive')->create();
        $inactiveWrestlerB = factory(Wrestler::class)->states('inactive')->create();
        $activeWrestler = factory(Wrestler::class)->states('active')->create();

        $inactiveWrestlers = Wrestler::inactive()->get();

        $this->assertTrue($inactiveWrestlers->contains($inactiveWrestlerA));
        $this->assertTrue($inactiveWrestlers->contains($inactiveWrestlerB));
        $this->assertFalse($inactiveWrestlers->contains($activeWrestler));
    }

    /** @test */
    public function it_can_activate_an_inactive_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $wrestler->activate();

        $this->assertTrue($wrestler->isActive());
    }

    /** @test */
    public function it_can_deactivate_an_active_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->deactivate();

        $this->assertFalse($wrestler->isActive());
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
        $this->assertFalse($wrestler->isActive());
        $this->assertTrue($wrestler->isRetired());
        $this->assertNull($wrestler->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_retired_wrestler_can_unretire()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->unretire();

        $this->assertNotNull($wrestler->retirements()->first()->ended_at);
        $this->assertTrue($wrestler->isActive());
        $this->assertFalse($wrestler->isRetired());
    }

    /** @test */
    public function it_can_get_retired_wrestlers()
    {
        $retiredWrestlerA = factory(Wrestler::class)->states('retired')->create();
        $retiredWrestlerB = factory(Wrestler::class)->states('retired')->create();
        $activeWrestler = factory(Wrestler::class)->states('active')->create();

        $retiredWrestlers = Wrestler::retired()->get();

        $this->assertTrue($retiredWrestlers->contains($retiredWrestlerA));
        $this->assertTrue($retiredWrestlers->contains($retiredWrestlerB));
        $this->assertFalse($retiredWrestlers->contains($activeWrestler));
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
        $suspendedWrestlerA = factory(Wrestler::class)->states('suspended')->create();
        $suspendedWrestlerB = factory(Wrestler::class)->states('suspended')->create();
        $activeWrestler = factory(Wrestler::class)->states('active')->create();

        $suspendedWrestlers = Wrestler::suspended()->get();

        $this->assertTrue($suspendedWrestlers->contains($suspendedWrestlerA));
        $this->assertTrue($suspendedWrestlers->contains($suspendedWrestlerB));
        $this->assertFalse($suspendedWrestlers->contains($activeWrestler));
    }

    /** @test */
    public function an_active_wrestler_can_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->suspend();

        $this->assertEquals(1, $wrestler->suspensions->count());
        $this->assertFalse($wrestler->isActive());
        $this->assertNull($wrestler->suspensions()->first()->ended_at);
        $this->assertTrue($wrestler->isSuspended());
    }

    /** @test */
    public function a_suspended_wrestler_can_be_reinstated()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->reinstate();

        $this->assertNotNull($wrestler->suspensions->last()->ended_at);
        $this->assertTrue($wrestler->isActive());
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
        $scheduledMatchA = MatchFactory::scheduled()->withWrestler($wrestler)->create();
        $scheduledMatchB = MatchFactory::scheduled()->withWrestler($wrestler)->create();
        $pastMatch = MatchFactory::past()->withWrestler($wrestler)->create();

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
        $pastMatchA = MatchFactory::past()->withWrestler($wrestler)->create();
        $pastMatchB = MatchFactory::past()->withWrestler($wrestler)->create();
        $scheduledMatch = MatchFactory::scheduled()->withWrestler($wrestler)->create();

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
     * @expectedException \App\Exceptions\ModelIsTitleChampionException
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
     * @expectedException \App\Exceptions\ModelNotTitleChampionException
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
        $currentChampionshipA = ChampionshipFactory::current()->forWrestler($wrestler)->create();
        $currentChampionshipB = ChampionshipFactory::current()->forWrestler($wrestler)->create();
        $pastChampionship = ChampionshipFactory::past()->forWrestler($wrestler)->create();

        $currentTitlesHeld = $wrestler->currentTitlesHeld;

        $this->assertTrue($currentTitlesHeld->contains('id', $currentChampionshipA->title_id));
        $this->assertTrue($currentTitlesHeld->contains('id', $currentChampionshipB->title_id));
        $this->assertFalse($currentTitlesHeld->contains('id', $pastChampionship->title_id));
    }

    /** @test */
    public function past_titles_held_returns_a_collection_of_past_titles()
    {
        $wrestler = factory(Wrestler::class)->create();
        $pastChampionshipA = ChampionshipFactory::past()->forWrestler($wrestler)->create();
        $pastChampionshipB = ChampionshipFactory::past()->forWrestler($wrestler)->create();
        $currentChampionship = ChampionshipFactory::current()->forWrestler($wrestler)->create();

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
        $this->assertFalse($wrestler->isActive());
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
        $this->assertTrue($wrestler->isActive());
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
        $injuredWrestlerA = factory(Wrestler::class)->states('injured')->create();
        $injuredWrestlerB = factory(Wrestler::class)->states('injured')->create();
        $activeWrestler = factory(Wrestler::class)->states('active')->create();

        $injuredWrestlers = Wrestler::injured()->get();

        $this->assertTrue($injuredWrestlers->contains($injuredWrestlerA));
        $this->assertTrue($injuredWrestlers->contains($injuredWrestlerB));
        $this->assertFalse($injuredWrestlers->contains($activeWrestler));
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
        $currentManagerA = ManagerFactory::current()->forWrestler($wrestler)->create();
        $currentManagerB = ManagerFactory::current()->forWrestler($wrestler)->create();
        $pastManager = ManagerFactory::past()->forWrestler($wrestler)->create();

        $currentManagers = $wrestler->currentManagers;

        $this->assertTrue($currentManagers->contains($currentManagerA));
        $this->assertTrue($currentManagers->contains($currentManagerB));
        $this->assertFalse($currentManagers->contains($pastManager));
    }

    /** @test */
    public function it_can_retrieve_a_wrestlers_past_managers()
    {
        $wrestler = factory(Wrestler::class)->create();

        $pastManagerA = ManagerFactory::past()->forWrestler($wrestler)->create();
        $pastManagerB = ManagerFactory::past()->forWrestler($wrestler)->create();
        $currentManager = ManagerFactory::current()->forWrestler($wrestler)->create();

        $pastManagers = $wrestler->pastManagers;

        $this->assertTrue($pastManagers->contains($pastManagerA));
        $this->assertTrue($pastManagers->contains($pastManagerB));
        $this->assertFalse($pastManagers->contains($currentManager));
    }
}