<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\Manager;
use ManagerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasManagersTraitTest extends TestCase
{
    use RefreshDatabase;

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

        $this->assertEquals(0, $wrestler->pastManagers()->count());
    }

    /** @test */
    public function wrestlers_current_managers_can_be_viewed_on_wrestler_bio()
    {
        $currentManagerA = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::today()->subMonths(5), NULL);
        $currentManagerB = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::today()->subMonths(2), NULL);
        $pastManager = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::today()->subWeeks(2), Carbon::today());

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->data('wrestler')->currentManagers->assertContains($currentManagerA);
        $response->data('wrestler')->currentManagers->assertContains($currentManagerB);
        $response->data('wrestler')->currentManagers->assertNotContains($pastManager);
    }

    /** @test */
    public function wrestlers_past_managers_can_be_viewed_on_wrestler_bio()
    {
        $pastManagerA = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::today()->subMonths(5), Carbon::today()->subMonths(3));
        $pastManagerB = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::today()->subMonths(2), Carbon::today()->subWeeks(3));
        $currentManager = ManagerFactory::createHiredTimeForWrestlerBetweenDates($this->wrestler, Carbon::yesterday(), NULL);

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->data('wrestler')->pastManagers->assertContains($pastManagerA);
        $response->data('wrestler')->pastManagers->assertContains($pastManagerA);
        $response->data('wrestler')->pastManagers->assertNotContains($currentManager);
    }
}