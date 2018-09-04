<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\RosterMember;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RosterMemberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_roster_members_hired_before_a_certain_date()
    {
        $rosterMemberA = factory(RosterMember::class)->create(['hired_at' => Carbon::parse('2016-12-31')]);
        $rosterMemberB = factory(RosterMember::class)->create(['hired_at' => Carbon::parse('2014-12-31')]);
        $rosterMemberC = factory(RosterMember::class)->create(['hired_at' => Carbon::parse('2017-01-01')]);

        $hiredRosterMembers = RosterMember::hiredBefore(Carbon::parse('2017-01-01'))->get();

        $this->assertTrue($hiredRosterMembers->contains($rosterMemberA));
        $this->assertTrue($hiredRosterMembers->contains($rosterMemberB));
        $this->assertFalse($hiredRosterMembers->contains($rosterMemberC));
    }
}
