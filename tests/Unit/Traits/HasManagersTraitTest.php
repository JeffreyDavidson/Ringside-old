<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\Manager;
use Facades\ManagerFactory;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasManagersTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_can_hire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager, Carbon::yesterday());

        $this->assertEquals(1, $wrestler->currentManagers()->count());
    }

    /** @test */
    public function a_wrestler_can_fire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager, Carbon::yesterday());
        $wrestler->fireManager($manager, Carbon::today());

        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertTrue($wrestler->hasPastManagers());
            $this->assertEquals(1, $wrestler->pastManagers->count());
        });
    }

    /** @test */
    public function a_wrestler_can_have_multiple_managers()
    {
        $wrestler = factory(Wrestler::class)->create();
        $managerA = factory(Manager::class)->create();
        $managerB = factory(Manager::class)->create();

        $wrestler->hireManager($managerA, Carbon::yesterday());
        $wrestler->hireManager($managerB, Carbon::yesterday());

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
        $wrestler->hireManager($manager, Carbon::yesterday());

        $wrestler->hireManager($manager, Carbon::today());

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

        $wrestler->fireManager($manager, Carbon::today());

        $this->assertEquals(0, $wrestler->pastManagers()->count());
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