<?php

namespace Tests\Feature;

use App\RosterMember;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewRosterMemberBioTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_view_a_roster_member_bio()
    {
        $rosterMember = factory(RosterMember::class)->create([
            'name' => 'Wrestler 1',
            'hometown' => 'Kansas City, Missouri',
            'height' => 73,
            'weight' => 251,
            'signature_move' => 'Powerbomb'
        ]);

        $this->visit('roster-members/'.$rosterMember->id);

        $this->see('Wrestler 1');
        $this->see('Kansas City, Missouri');
        $this->see('6\'1"');
        $this->see('251 lbs.');
        $this->see('Powerbomb');
    }
}
