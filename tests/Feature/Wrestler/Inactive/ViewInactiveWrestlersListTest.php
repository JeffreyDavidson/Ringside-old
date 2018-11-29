<?php

namespace Tests\Feature\Roster\Wrestler\Inactive;

use Tests\IntegrationTestCase;

class ViewInactiveWrestlersListTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-roster-members');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_inactive_wrestlers()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('inactive-wrestlers.index'));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.inactive');
        $response->assertViewHas('wrestlers');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_inactive_wrestlers()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('inactive-wrestlers.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_list_of_inactive_wrestlers()
    {
        $response = $this->get(route('inactive-wrestlers.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
