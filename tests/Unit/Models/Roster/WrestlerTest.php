<?php

namespace Tests\Unit\Models\Roster;

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
use Illuminate\Database\Eloquent\SoftDeletes;

class WrestlerTest extends IntegrationTestCase
{
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
    public function a_wrestler_uses_the_soft_deletes_trait()
    {
        $this->assertTrue(in_array(SoftDeletes::class, class_uses(Wrestler::class)));
    }
}