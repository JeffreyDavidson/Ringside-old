<?php

namespace Tests\Unit\Models\Roster;

use Carbon\Carbon;
use App\Traits\Hireable;
use App\Traits\Retirable;
use App\Traits\Statusable;
use App\Traits\Suspendable;
use App\Models\Roster\Manager;
use Tests\IntegrationTestCase;
use App\Presenters\Roster\ManagerPresenter;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManagerTest extends IntegrationTestCase
{
    /** @test */
    public function a_manager_has_a_first_name()
    {
        $manager = factory(Manager::class)->create(['first_name' => 'John']);

        $this->assertEquals('John', $manager->first_name);
    }

    /** @test */
    public function a_manager_has_a_last_name()
    {
        $manager = factory(Manager::class)->create(['last_name' => 'Smith']);

        $this->assertEquals('Smith', $manager->last_name);
    }

    /** @test */
    public function a_manager_has_an_is_active_field()
    {
        $manager = factory(Manager::class)->create(['is_active' => true]);

        $this->assertTrue($manager->is_active);
    }

    /** @test */
    public function a_manager_has_a_hired_at_date()
    {
        $manager = factory(Manager::class)->create(['hired_at' => Carbon::parse('2018-10-01')]);

        $this->assertEquals('2018-10-01', $manager->hired_at->toDateString());
    }

    /** @test */
    public function a_manager_uses_the_hireable_trait()
    {
        $this->assertTrue(in_array(Hireable::class, class_uses(Manager::class)));
    }

    /** @test */
    public function a_manager_uses_the_statusable_trait()
    {
        $this->assertTrue(in_array(Statusable::class, class_uses(Manager::class)));
    }

    /** @test */
    public function a_manager_uses_the_retirable_trait()
    {
        $this->assertTrue(in_array(Retirable::class, class_uses(Manager::class)));
    }

    /** @test */
    public function a_manager_uses_the_suspendable_trait()
    {
        $this->assertTrue(in_array(Suspendable::class, class_uses(Manager::class)));
    }

    /** @test */
    public function a_manager_uses_the_presentable_trait()
    {
        $this->assertTrue(in_array(Presentable::class, class_uses(Manager::class)));
    }

    /** @test */
    public function a_manager_uses_the_soft_deletes_trait()
    {
        $this->assertTrue(in_array(SoftDeletes::class, class_uses(Manager::class)));
    }

    /** @test */
    public function a_manager_uses_the_manager_presenter()
    {
        $manager = factory(Manager::class)->create();

        $this->assertInstanceOf(ManagerPresenter::class, $manager->present());
    }

    /** @test */
    public function a_manager_hired_at_date_is_added_to_dates_array()
    {
        $manager = factory(Manager::class)->create();

        $this->assertTrue(in_array('hired_at', $manager->getDates()));
    }

    /** @test */
    public function a_manager_is_active_field_is_boolean_type_and_added_to_casts_array()
    {
        $manager = factory(Manager::class)->create();

        $this->assertTrue($manager->hasCast('is_active', 'boolean'));
    }
}
