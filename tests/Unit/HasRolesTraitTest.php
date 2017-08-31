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
    public function a_wrestler_has_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager);

        $this->assertTrue($wrestler->hasAManager());
    }

    /** @test */
    public function a_wrestler_has_previous_managers()
    {
        $wrestler = factory(Wrestler::class)->create();
        $managerA = factory(Manager::class)->create();
        $managerB = factory(Manager::class)->create();

        $wrestler->hireManager($managerA);
        $wrestler->fireManager($managerA);
        $wrestler->hireManager($managerB);
        $wrestler->fireManager($managerB);

        $this->assertTrue($wrestler->hasPreviousManagers->count());
    }

    /** @test */
    public function a_wrestler_can_hire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager);

        $this->assertEquals(1, $wrestler->currentManagers->count());
        $this->assertNull($wrestler->managers()->first()->fired_on);
    }

    /** @test */
    public function a_wrestler_can_fire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager, Carbon::parse('last week'));
        $wrestler->fireManager($manager, Carbon::parse('yesterday'));
        //dd($wrestler->previousManagers->first()->hired_on);
        //dd($wrestler->previousManagers);

        $this->assertNotNull($wrestler->previousManagers->first()->fired_on);
    }

    /** @test */
    public function a_wrestler_can_have_multiple_managers()
    {
        $wrestler = factory(Wrestler::class)->create();
        $managerA = factory(Manager::class)->create();
        $managerB = factory(Manager::class)->create();

        $wrestler->hireManager($managerA);
        $wrestler->hireManager($managerB);

        $this->assertEquals(2, $wrestler->currentManagers->count());
    }
}
