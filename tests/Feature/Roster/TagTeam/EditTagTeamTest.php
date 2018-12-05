<?php

namespace Tests\Feature\Roster\TagTeam;

use Tests\TestCase;
use App\Models\Roster\TagTeam;
use Tests\IntegrationTestCase;

class EditTagTeamTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-roster-member']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_tag_team_page()
    {
        $tagteam = factory(TagTeam::class)->create();
        
        $response = $this->actingAs($this->authorizedUser)->get(route('tagteams.edit', $tagteam->id));

        $response->assertSuccessful();
        $response->assertViewIs('tagteams.edit');
        $response->assertViewHas('tagteam');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_tagteam_page()
    {
        $tagteam = factory(TagTeam::class)->create();
        
        $response = $this->actingAs($this->unauthorizedUser)->get(route('tagteams.edit', $tagteam->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_create_tagteam_page()
    {
        $tagteam = factory(TagTeam::class)->create();

        
        $response = $this->get(route('tagteams.create', $tagteam->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
