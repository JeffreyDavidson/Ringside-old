<?php

namespace Tests\Unit;

use App\Models\Stipulation;
use App\Models\Match;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StipulationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function stipulations_belong_to_matches()
    {
        $stipulation = factory(Stipulation::class)->create();

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection', $stipulation->matches
        );
    }
}
