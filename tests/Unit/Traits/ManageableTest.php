<?php

namespace Tests\Unit\Traits;

class Manageable
{

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

        $currentManagers = $wrestler->currentManagers()->get();

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

        $pastManagers = $wrestler->pastManagers()->get();

        $this->assertTrue($pastManagers->contains($pastManagerA));
        $this->assertTrue($pastManagers->contains($pastManagerB));
        $this->assertFalse($pastManagers->contains($currentManager));
    }
}