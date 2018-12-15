<?php

namespace Tests\Unit\Models\Roster;

use App\Traits\Hireable;
use App\Traits\Retirable;
use App\Traits\Statusable;
use App\Traits\Suspendable;
use App\Models\Roster\Manager;
use Tests\IntegrationTestCase;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManagerTest extends IntegrationTestCase
{
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
    public function a_manager_uses_the_soft_deletes_trait()
    {
        $this->assertTrue(in_array(SoftDeletes::class, class_uses(Manager::class)));
    }
}
