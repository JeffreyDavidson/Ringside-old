<?php

namespace Tests\Unit\Models;

use App\Models\Stipulation;
use Tests\IntegrationTestCase;
use Illuminate\Database\Eloquent\SoftDeletes;

class StipulationTest extends IntegrationTestCase
{
    /** @test */
    public function a_stipulation_uses_the_soft_deletes_trait()
    {
        $this->assertTrue(in_array(SoftDeletes::class, class_uses(Stipulation::class)));
    }
}
