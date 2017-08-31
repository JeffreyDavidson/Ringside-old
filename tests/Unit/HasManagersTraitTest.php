<?php

namespace Tests\Unit;

use App\Models\Manager;
use App\Models\Wrestler;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HasManagersTraitTest extends TestCase
{
    use DatabaseMigrations;

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
     * @test */
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
     * @test */
    public function a_wrestler_cannot_fire_a_manager_they_do_not_have()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->fireManager($manager);

        $this->assertEquals(0, $wrestler->previousManagers()->count());
    }
}
