<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StipulationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_stipulation_has_many_matches()
    {
        $stipulation = factory(Stipulation::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $stipulation->matches);
    }
}
