<?php

namespace Tests\Unit;

use App\Exceptions\WrestlerAlreadyHasManagerException;
use App\Exceptions\WrestlerAlreadyHasTitleException;
use App\Exceptions\WrestlerAlreadyInjuredException;
use App\Exceptions\WrestlerAlreadyRetiredException;
use App\Exceptions\WrestlerAlreadySuspendedException;
use App\Exceptions\WrestlerNotHaveHiredManagerException;
use App\Exceptions\WrestlerNotInjuredException;
use App\Exceptions\WrestlerNotRetiredException;
use App\Exceptions\WrestlerNotSuspendedException;
use App\Exceptions\WrestlerNotTitleChampionException;
use App\Models\Manager;
use App\Models\Title;
use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WrestlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_can_win_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title, Carbon::now());

        $this->assertTrue($wrestler->isCurrentlyAChampion());
        $this->assertTrue($wrestler->hasTitle($title));
    }

    /** @test */
    public function a_wrestler_can_lose_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title, Carbon::yesterday());
        $wrestler->loseTitle($title, Carbon::now());

        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertTrue($wrestler->hasPreviousTitlesHeld());
            $this->assertEquals(1, $wrestler->previousTitlesHeld->count());
        });
    }

    /** @test */
    public function a_wrestler_can_have_multiple_titles_at_the_same_time()
    {
        $wrestler = factory(Wrestler::class)->create();
        $titleA = factory(Title::class)->create();
        $titleB = factory(Title::class)->create();

        $wrestler->winTitle($titleA, Carbon::now());
        $wrestler->winTitle($titleB, Carbon::now());

        $this->assertEquals(2, $wrestler->currentTitlesHeld()->count());
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

        $this->assertEquals(1, $wrestler->currentTitles->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotTitleChampionException
     *
     * @test
     */
    public function a_wrestler_does_not_have_a_title_cannot_lose_the_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->loseTitle($title, Carbon::now());

        $this->assertEquals(0, $wrestler->previousTitlesHeld->count());
    }

    /** @test */
    public function a_wrestler_can_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->suspend();

        $this->assertEquals(1, $wrestler->suspensions->count());
        $this->assertEquals(2, $wrestler->status());
        $this->assertNull($wrestler->suspensions()->first()->ended_at);
    }

    /** @test */
    public function a_wrestler_can_be_renewed()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->suspend();
        $wrestler->renew();

        $this->assertNotNull($wrestler->suspensions()->first()->ended_at);
        $this->assertEquals(1, $wrestler->status());
    }

    /** @test */
    public function a_wrestler_can_have_multiple_suspensions()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->suspend();
        $wrestler->renew();
        $wrestler->suspend();

        $this->assertTrue($wrestler->hasPreviousSuspensions());
        $this->assertEquals(1, $wrestler->previousSuspensions->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadySuspendedException
     *
     * @test
     */
    public function a_wrestler_who_is_suspended_cannot_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->create();
        $wrestler->suspend();

        $wrestler->suspend();

        $this->assertEquals(1, $wrestler->suspended->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotSuspendedException
     *
     * @test
     */
    public function a_wrestler_who_is_not_suspended_cannot_be_renewed()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->renew();

        $this->assertEquals(0, $wrestler->retirements->count());
    }

    /** @test */
    public function a_wrestler_can_retire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();

        $this->assertEquals(1, $wrestler->retirements->count());
        $this->assertEquals(2, $wrestler->status());
        $this->assertNull($wrestler->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_wrestler_can_unretire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();
        $wrestler->unretire();

        $this->assertNotNull($wrestler->retirements()->first()->ended_at);
        $this->assertEquals(1, $wrestler->status());
    }

    /** @test */
    public function a_wrestler_can_have_multiple_retirements()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();
        $wrestler->unretire();
        $wrestler->retire();

        $this->assertTrue($wrestler->hasPreviousRetirements());
        $this->assertEquals(1, $wrestler->previousRetirements->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadyRetiredException
     *
     * @test
     */
    public function a_wrestler_who_is_retired_cannot_retire_without_unretiring()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->retire();
        $wrestler->retire();

        $this->assertEquals(1, $wrestler->retirements->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotRetiredException
     *
     * @test
     */
    public function a_wrestler_who_is_not_retired_cannot_unretire()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->unretire();

        $this->assertEquals(0, $wrestler->retirements->count());
    }

    /** @test */
    public function a_wrestler_can_hire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager);

        $this->assertEquals(1, $wrestler->currentManagers()->count());
    }

    /** @test */
    public function a_wrestler_can_fire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager);
        $wrestler->fireManager($manager);

        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertTrue($wrestler->hasPreviousManagers());
            $this->assertEquals(1, $wrestler->previousManagers->count());
        });
    }

    /** @test */
    public function a_wrestler_can_have_multiple_managers()
    {
        $wrestler = factory(Wrestler::class)->create();
        $managerA = factory(Manager::class)->create();
        $managerB = factory(Manager::class)->create();

        $wrestler->hireManager($managerA);
        $wrestler->hireManager($managerB);

        $this->assertEquals(2, $wrestler->currentManagers()->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadyHasManagerException
     *
     * @test
     */
    public function a_wrestler_cannot_hire_a_manager_they_already_have()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();
        $wrestler->hireManager($manager);

        $wrestler->hireManager($manager);

        $this->assertEquals(1, $wrestler->currentManagers()->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotHaveHiredManagerException
     *
     * @test
     */
    public function a_wrestler_cannot_fire_a_manager_they_do_not_have()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->fireManager($manager);

        $this->assertEquals(0, $wrestler->previousManagers()->count());
    }

    /** @test */
    public function an_wrestler_can_be_injured()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->injure();

        $this->assertEquals(1, $wrestler->injuries->count());
        $this->assertEquals(2, $wrestler->status());
        $this->assertNull($wrestler->injuries()->first()->healed_at);
    }

    /** @test */
    public function an_injured_wrestler_can_be_healed()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->injure();
        $wrestler->heal();

        $this->assertNotNull($wrestler->injuries()->first()->healed_at);
        $this->assertEquals(1, $wrestler->status());
    }

    /** @test */
    public function a_wrestler_can_have_multiple_injuries()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->injure();
        $wrestler->heal();
        $wrestler->injure();

        $this->assertTrue($wrestler->hasPreviousInjuries());
        $this->assertEquals(1, $wrestler->previousInjuries->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadyInjuredException
     *
     * @test
     */
    public function an_injured_wrestler_cannot_be_injured()
    {
        $wrestler = factory(Wrestler::class)->create();
        $wrestler->injure();

        $wrestler->injure();

        $this->assertEquals(1, $wrestler->injuries->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotInjuredException
     *
     * @test
     */
    public function a_wrestler_who_is_not_injured_cannot_be_healed()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->heal();

        $this->assertEquals(0, $wrestler->injuries->count());
    }

    /** @test */
    public function a_wrestler_can_be_active()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 1]);

        $this->assertTrue($wrestler->isActive());
    }

    /** @test */
    public function a_wrestler_can_be_inactive()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 2]);

        $this->assertTrue($wrestler->isInactive());
    }

    /** @test */
    public function it_can_get_all_active_users()
    {
        factory(Wrestler::class, 3)->create(['status_id' => WrestlerStatus::ACTIVE]);

        $this->assertEquals(3, Wrestler::active()->get()->count());
    }

    /** @test */
    public function it_can_get_all_inactive_users()
    {
        factory(Wrestler::class, 3)->create(['status_id' => WrestlerStatus::INACTIVE]);

        $this->assertEquals(3, Wrestler::inactive()->get()->count());
    }

    /** @test */
    public function it_can_retrieve_all_injured_wrestlers()
    {
        factory(Wrestler::class, 3)->create(['status_id' => WrestlerStatus::INJURED]);

        $this->assertEquals(3, Wrestler::injured()->get()->count());
    }

    /** @test */
    public function it_can_retrieve_all_retired_wrestlers()
    {
        factory(Wrestler::class, 3)->create(['status_id' => WrestlerStatus::RETIRED]);

        $this->assertEquals(3, Wrestler::retired()->get()->count());
    }

    /** @test */
    public function it_can_retrieve_all_suspended_wrestlers()
    {
        factory(Wrestler::class, 3)->create(['status_id' => WrestlerStatus::SUSPENDED]);

        $this->assertEquals(3, Wrestler::suspended()->get()->count());
    }

    /** @test */
    public function it_can_change_a_users_active_status_to_inactive()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 1]);

        $wrestler->setStatusToInactive();

        $this->assertEquals(WrestlerStatus::INACTIVE, $wrestler->status());
    }

    /** @test */
    public function it_can_change_a_users_inactive_status_to_active()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => 2]);

        $wrestler->setStatusToActive();

        $this->assertEquals(WrestlerStatus::ACTIVE, $wrestler->status());
    }
}
