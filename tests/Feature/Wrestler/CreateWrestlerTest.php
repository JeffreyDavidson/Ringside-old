<?php

namespace Tests\Feature\Wrestler;

use Tests\IntegrationTestCase;

class CreateWrestlerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-wrestler']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_wrestler_page()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.create'));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.create');
        $response->assertViewHas('wrestler');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_wrestler_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('wrestlers.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_wrestler_page()
    {
        $response = $this->get(route('wrestlers.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
