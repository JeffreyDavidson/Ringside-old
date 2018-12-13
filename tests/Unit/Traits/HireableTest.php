<?php

namespace Tests\Unit\Traits;

use Carbon\Carbon;
use App\Models\Roster\Wrestler;

class HireableTest
{
    /** @test */
    public function it_can_get_wrestlers_hired_before_a_certain_date()
    {
        $wrestlerA = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2016-12-31')]);
        $wrestlerB = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2014-12-31')]);
        $wrestlerC = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2017-01-01')]);

        $hiredWrestlers = Wrestler::hiredBefore(Carbon::parse('2017-01-01'))->get();

        $this->assertTrue($hiredWrestlers->contains($wrestlerA));
        $this->assertTrue($hiredWrestlers->contains($wrestlerB));
        $this->assertFalse($hiredWrestlers->contains($wrestlerC));
    }
}
