<?php

namespace Tests\Feature\Stipulation;

use App\Models\Stipulation;
use Tests\IntegrationTestCase;

class CreateStipulationTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-stipulation']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_create_stipulation_page()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('stipulations.create'));

        $response->assertSuccessful();
        $response->assertViewIs('stipulations.create');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_create_stipulation_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('stipulations.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_create_stipulation_page()
    {
        $response = $this->get(route('stipulations.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
