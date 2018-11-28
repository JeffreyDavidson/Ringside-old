<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use App\Models\Injury;
use Tests\IntegrationTestCase;

class InjuryTest extends IntegrationTestCase
{
    /** @test */
    public function an_injury_can_be_healed()
    {
        $injury = factory(Injury::class)->create();

        $injury->heal();

        $this->assertEquals(Carbon::now()->toDateString(), $injury->healed_at->toDateString());
    }
}
