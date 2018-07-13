<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RefereeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_referees_hired_before_a_certain_date()
    {
        $refereeA = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2016-12-31')]);
        $refereeB = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2014-12-31')]);
        $refereeC = factory(Wrestler::class)->create(['hired_at' => Carbon::parse('2017-01-01')]);

        $hiredReferees = Wrestler::hiredBefore(Carbon::parse('2017-01-01'))->get();

        $this->assertTrue($hiredReferees->contains($refereeA));
        $this->assertTrue($hiredReferees->contains($refereeB));
        $this->assertFalse($hiredReferees->contains($refereeC));
    }
}