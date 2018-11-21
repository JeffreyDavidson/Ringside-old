<?php

namespace Tests\Feature\Title;

use Tests\IntegrationTestCase;

class CreateTitleTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-title']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_create_title_page()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('titles.create'));

        $response->assertSuccessful();
        $response->assertViewIs('titles.create');
        $response->assertViewHas('title');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_create_title_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('titles.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_create_title_page()
    {
        $response = $this->get(route('titles.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
