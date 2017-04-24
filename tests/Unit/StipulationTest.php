<?php

namespace Tests\Unit;

use App\Models\Stipulation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StipulationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_stipulation_can_belong_to_many_matches()
    {
        $stipulation = factory(Stipulation::class)->create();
        $match = factory(Match::class)->create(['stipulation_id' => $stipulation->id]);

        $this->assertInstanceOf(Stipulation::class, $match->stipulations->first());
        $this->assertEquals($stipulation->id, $match->stipulations->first()->id);
    }
}
