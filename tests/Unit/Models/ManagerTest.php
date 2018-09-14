<?php

namespace Tests\Unit\Models;

use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_managers_hired_before_a_certain_date()
    {
        $managerA = factory(Manager::class)->create(['hired_at' => Carbon::parse('2016-12-31')]);
        $managerB = factory(Manager::class)->create(['hired_at' => Carbon::parse('2014-12-31')]);
        $managerC = factory(Manager::class)->create(['hired_at' => Carbon::parse('2017-01-01')]);

        $hiredManagers = Manager::hiredBefore(Carbon::parse('2017-01-01'))->get();

        $this->assertTrue($hiredManagers->contains($managerA));
        $this->assertTrue($hiredManagers->contains($managerB));
        $this->assertFalse($hiredManagers->contains($managerC));
    }

    /** @test */
    public function managers_are_marked_as_active_depending_on_hired_date()
    {
        $managerA = factory(Manager::class)->create(['hired_at' => Carbon::today()->subDays(2)]);
        $managerB = factory(Manager::class)->create(['hired_at' => Carbon::today()]);
        $managerC = factory(Manager::class)->create(['hired_at' => Carbon::today()->addDays(2)]);

        $this->assertTrue($managerA->isActive());
        $this->assertTrue($managerB->isActive());
        $this->assertFalse($managerC->isActive());
    }

    /** @test */
    public function it_can_get_active_managers()
    {
        $managerA = factory(Manager::class)->states('active')->create();
        $managerB = factory(Manager::class)->states('active')->create();
        $managerC = factory(Manager::class)->states('inactive')->create();

        $activeManagers = Manager::active()->get();

        $this->assertTrue($activeManagers->contains($managerA));
        $this->assertTrue($activeManagers->contains($managerB));
        $this->assertFalse($activeManagers->contains($managerC));
    }

    /** @test */
    public function it_can_get_inactive_managers()
    {
        $managerA = factory(Manager::class)->states('inactive')->create();
        $managerB = factory(Manager::class)->states('inactive')->create();
        $managerC = factory(Manager::class)->states('active')->create();

        $inactiveManagers = Manager::inactive()->get();

        $this->assertTrue($inactiveManagers->contains($managerA));
        $this->assertTrue($inactiveManagers->contains($managerB));
        $this->assertFalse($inactiveManagers->contains($managerC));
    }

    /** @test */
    public function an_inactive_manager_can_be_activated()
    {
        $manager = factory(Manager::class)->states('inactive')->create();

        $manager->activate();

        $this->assertTrue($manager->isActive());
    }

    /** @test */
    public function an_active_manager_can_be_deactivated()
    {
        $manager = factory(Manager::class)->states('active')->create();

        $manager->deactivate();

        $this->assertFalse($manager->isActive());
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_manager_cannot_be_activated()
    {
        $manager = factory(Manager::class)->states('active')->create();

        $manager->activate();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsInactiveException
     *
     * @test
     */
    public function an_inactive_manager_cannot_be_deactivated()
    {
        $manager = factory(Manager::class)->states('inactive')->create();

        $manager->deactivate();
    }

    /** @test */
    public function an_active_manager_can_retire()
    {
        $manager = factory(Manager::class)->states('active')->create();

        $manager->retire();

        $this->assertEquals(1, $manager->retirements->count());
        $this->assertFalse($manager->isActive());
        $this->assertTrue($manager->isRetired());
        $this->assertNull($manager->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_retired_manager_can_unretire()
    {
        $manager = factory(Manager::class)->states('retired')->create();

        $manager->unretire();

        $this->assertNotNull($manager->retirements()->first()->ended_at);
        $this->assertTrue($manager->isActive());
        $this->assertFalse($manager->isRetired());
    }

    /** @test */
    public function it_can_get_retired_managers()
    {
        $managerA = factory(Manager::class)->states('retired')->create();
        $managerB = factory(Manager::class)->states('retired')->create();
        $managerC = factory(Manager::class)->states('active')->create();

        $retiredManagers = Manager::retired()->get();

        $this->assertTrue($retiredManagers->contains($managerA));
        $this->assertTrue($retiredManagers->contains($managerB));
        $this->assertFalse($retiredManagers->contains($managerC));
    }

    /**
     * @expectedException \App\Exceptions\ModelIsRetiredException
     *
     * @test
     */
    public function a_retired_manager_cannot_retire()
    {
        $manager = factory(Manager::class)->states('retired')->create();

        $manager->retire();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_manager_cannot_unretire()
    {
        $manager = factory(Manager::class)->states('active')->create();

        $manager->unretire();
    }

    /** @test */
    public function a_manager_can_be_suspended()
    {
        $manager = factory(Manager::class)->create();

        $manager->suspend();

        $this->assertEquals(1, $manager->suspensions->count());
        $this->assertFalse($manager->isActive());
        $this->assertNull($manager->suspensions()->first()->ended_at);
        $this->assertTrue($manager->isSuspended());
    }

    /** @test */
    public function a_suspended_manager_can_be_reinstated()
    {
        $manager = factory(Manager::class)->states('suspended')->create();

        $manager->reinstate();

        $this->assertNotNull($manager->suspensions->last()->ended_at);
        $this->assertTrue($manager->isActive());
        $this->assertFalse($manager->isSuspended());
        $this->assertTrue($manager->hasPastSuspensions());
        $this->assertEquals(1, $manager->pastSuspensions->count());
    }

    /** @test */
    public function it_can_get_suspended_managers()
    {
        $managerA = factory(Manager::class)->states('suspended')->create();
        $managerB = factory(Manager::class)->states('suspended')->create();
        $managerC = factory(Manager::class)->states('active')->create();

        $suspendedManagers = Manager::suspended()->get();

        $this->assertTrue($suspendedManagers->contains($managerA));
        $this->assertTrue($suspendedManagers->contains($managerB));
        $this->assertFalse($suspendedManagers->contains($managerC));
    }

    /**
     * @expectedException \App\Exceptions\ModelIsSuspendedException
     *
     * @test
     */
    public function a_suspended_manager_cannot_be_suspended()
    {
        $manager = factory(Manager::class)->states('suspended')->create();

        $manager->suspend();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_manager_cannot_be_reinstated()
    {
        $manager = factory(Manager::class)->states('active')->create();

        $manager->reinstate();
    }
}
