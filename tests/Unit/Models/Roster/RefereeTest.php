<?php

namespace Tests\Unit\Models\Roster;

use Carbon\Carbon;
use App\Traits\Hireable;
use App\Traits\Retirable;
use App\Traits\Statusable;
use App\Traits\Suspendable;
use App\Models\Roster\Referee;
use Tests\IntegrationTestCase;
use App\Presenters\Roster\RefereePresenter;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefereeTest extends IntegrationTestCase
{
    /** @test */
    public function a_referee_has_a_first_name()
    {
        $referee = factory(Referee::class)->create(['first_name' => 'John']);

        $this->assertEquals('John', $referee->first_name);
    }

    /** @test */
    public function a_referee_has_a_last_name()
    {
        $referee = factory(Referee::class)->create(['last_name' => 'Smith']);

        $this->assertEquals('Smith', $referee->last_name);
    }

    /** @test */
    public function a_referee_has_an_is_active_field()
    {
        $referee = factory(Referee::class)->create(['is_active' => true]);

        $this->assertTrue($referee->is_active);
    }

    /** @test */
    public function a_referee_has_a_hired_at_date()
    {
        $referee = factory(Referee::class)->create(['hired_at' => Carbon::parse('2018-10-01')]);

        $this->assertEquals('2018-10-01', $referee->hired_at->toDateString());
    }

    /** @test */
    public function a_referee_has_a_full_name()
    {
        $referee = factory(Referee::class)->create(['first_name' => 'John', 'last_name' => 'Smith']);

        $this->assertEquals('John Smith', $referee->full_name);
    }

    /** @test */
    public function a_referee_uses_the_hireable_trait()
    {
        $this->assertTrue(in_array(Hireable::class, class_uses(Referee::class)));
    }

    /** @test */
    public function a_referee_uses_the_statusable_trait()
    {
        $this->assertTrue(in_array(Statusable::class, class_uses(Referee::class)));
    }

    /** @test */
    public function a_referee_uses_the_retirable_trait()
    {
        $this->assertTrue(in_array(Retirable::class, class_uses(Referee::class)));
    }

    /** @test */
    public function a_referee_uses_the_suspendable_trait()
    {
        $this->assertTrue(in_array(Suspendable::class, class_uses(Referee::class)));
    }

    /** @test */
    public function a_referee_uses_the_presentable_trait()
    {
        $this->assertTrue(in_array(Presentable::class, class_uses(Referee::class)));
    }

    /** @test */
    public function a_referee_uses_the_soft_deletes_trait()
    {
        $this->assertTrue(in_array(SoftDeletes::class, class_uses(Referee::class)));
    }

    /** @test */
    public function a_referee_uses_the_referee_presenter()
    {
        $referee = factory(Referee::class)->create();

        $this->assertInstanceOf(RefereePresenter::class, $referee->present());
    }

    /** @test */
    public function a_referee_hired_at_date_is_added_to_dates_array()
    {
        $referee = factory(Referee::class)->create();

        $this->assertTrue(in_array('hired_at', $referee->getDates()));
    }

    /** @test */
    public function a_referee_is_active_field_is_boolean_type_and_added_to_casts_array()
    {
        $referee = factory(Referee::class)->create();

        $this->assertTrue($referee->hasCast('is_active', 'boolean'));
    }
}
