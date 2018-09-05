<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Injury;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InjuryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_injury_can_be_healed()
    {
        $injury = factory(Injury::class)->create();

        $injury->heal();

        $this->assertEquals(Carbon::now()->toDateString(), $injury->healed_at->toDateString());
    }
}
