<?php

namespace Tests\Feature\Stipulation;

use App\Models\Stipulation;
use Tests\IntegrationTestCase;

class EditStipulationTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-stipulation']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_stipulation_page()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('stipulations.edit', $stipulation->id));

        $response->assertSuccessful();
        $response->assertViewIs('stipulations.edit');
        $response->assertViewHas('stipulation');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_stipulation_page()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('stipulations.edit', $stipulation->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_stipulation_page()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->get(route('stipulations.edit', $stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
