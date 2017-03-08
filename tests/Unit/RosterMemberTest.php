<?php

namespace Tests\Unit;

use App\RosterMember;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RosterMemberTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_get_formatted_height()
    {
        $rosterMember = factory(RosterMember::class)->make([
            'height' => '73'
        ]);

        $this->assertEquals('6\'1"', $rosterMember->formatted_height);
    }

}
