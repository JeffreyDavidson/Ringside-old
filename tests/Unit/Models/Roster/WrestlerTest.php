<?php

namespace Tests\Unit\Models\Roster;

use Carbon\Carbon;
use App\Traits\Hireable;
use App\Traits\Injurable;
use App\Traits\Retirable;
use App\Traits\Manageable;
use App\Traits\Statusable;
use App\Traits\Suspendable;
use App\Interfaces\Competitor;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;
use App\Traits\CompetitorTrait;
use Laracodes\Presenter\Traits\Presentable;
use App\Presenters\Roster\WrestlerPresenter;
use Illuminate\Database\Eloquent\SoftDeletes;

class WrestlerTest extends IntegrationTestCase
{
    /** @test */
    public function a_wrestler_has_a_name()
    {
        $wrestler = factory(Wrestler::class)->create(['name' => 'Wrestler Name']);

        $this->assertEquals('Wrestler Name', $wrestler->name);
    }

    /** @test */
    public function a_wrestler_has_a_slug()
    {
        $wrestler = factory(Wrestler::class)->create(['slug' => 'wrestler-slug']);

        $this->assertEquals('wrestler-slug', $wrestler->slug);
    }

    /** @test */
    public function a_wrestler_has_a_hometown()
    {
        $wrestler = factory(Wrestler::class)->create(['hometown' => 'Kansas City, MO']);

        $this->assertEquals('Kansas City, MO', $wrestler->hometown);
    }

    /** @test */
    public function a_wrestler_has_a_height()
    {
        $wrestler = factory(Wrestler::class)->create(['height' => 64]);

        $this->assertEquals(64, $wrestler->height);
    }

    /** @test */
    public function a_wrestler_has_a_weight()
    {
        $wrestler = factory(Wrestler::class)->create(['weight' => 330]);

        $this->assertEquals(330, $wrestler->weight);
    }

    /** @test */
    public function a_wrestler_has_a_signature_move()
    {
        $wrestler = factory(Wrestler::class)->create(['signature_move' => 'Wrestler Signature Move']);

        $this->assertEquals('Wrestler Signature Move', $wrestler->signature_move);
    }

    /** @test */
    public function a_wrestler_has_an_in_active_field()
    {
        $wrestler = factory(Wrestler::class)->create(['is_active' => true]);

        $this->assertTrue($wrestler->is_active);
    }

    /** @test */
    public function a_wrestler_has_a_hired_at_date()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2018-10-01')]);

        $this->assertEquals('2018-10-01', $wrestler->hired_at->toDateString());
    }

    /** @test */
    public function a_wrestler_implements_the_competitor_interface()
    {
        $this->assertTrue(in_array(Competitor::class, class_implements(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_competitor_trait()
    {
        $this->assertTrue(in_array(CompetitorTrait::class, class_uses(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_statusable_trait()
    {
        $this->assertTrue(in_array(Statusable::class, class_uses(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_injurable_trait()
    {
        $this->assertTrue(in_array(Injurable::class, class_uses(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_manageable_trait()
    {
        $this->assertTrue(in_array(Manageable::class, class_uses(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_suspendable_trait()
    {
        $this->assertTrue(in_array(Suspendable::class, class_uses(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_hireable_trait()
    {
        $this->assertTrue(in_array(Hireable::class, class_uses(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_retirable_trait()
    {
        $this->assertTrue(in_array(Retirable::class, class_uses(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_suspendeble_trait()
    {
        $this->assertTrue(in_array(Suspendable::class, class_uses(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_presentable_trait()
    {
        $this->assertTrue(in_array(Presentable::class, class_uses(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_soft_deletes_trait()
    {
        $this->assertTrue(in_array(SoftDeletes::class, class_uses(Wrestler::class)));
    }

    /** @test */
    public function a_wrestler_uses_the_wrestler_presenter()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertInstanceOf(WrestlerPresenter::class, $wrestler->present());
    }

    /** @test */
    public function a_wrestler_hired_at_date_is_added_to_dates_array()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertTrue(in_array('hired_at', $wrestler->getDates()));
    }

    /** @test */
    public function a_wrestler_is_active_field_is_boolean_type_and_added_to_casts_array()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertTrue($wrestler->hasCast('is_active', 'boolean'));
    }
}