<?php

use Tests\IntegrationTestCase;

class CreateTagTeamTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-roster-member']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_create_tag_team_page()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('tagteams.create'));

        $response->assertSuccessful();
        $response->assertViewIs('tagteams.create');
        $response->assertViewHas('tagteam');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_create_tagteam_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('tagteams.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_create_tagteam_page()
    {
        $response = $this->get(route('tagteams.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
