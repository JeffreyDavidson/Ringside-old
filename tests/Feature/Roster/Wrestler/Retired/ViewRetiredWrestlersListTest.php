<?php

namespace Tests\Feature\Roster\Wrestler\Retired;

use Tests\IntegrationTestCase;

class ViewRetiredWrestlersListTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-roster-members');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_retired_wrestlers()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->authorizedUser)->get(route('retired-wrestlers.index'));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.retired');
        $response->assertViewHas('wrestlers');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_retired_wrestlers()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('retired-wrestlers.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_list_of_retired_wrestlers()
    {
        $response = $this->get(route('retired-wrestlers.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
