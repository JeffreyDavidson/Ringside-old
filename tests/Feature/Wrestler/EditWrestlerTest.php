<?php

namespace Tests\Feature\Roster\Wrestler;

use App\Models\Wrestler;
use Tests\IntegrationTestCase;

class EditWrestlerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-roster-member']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_wrestler_page()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('wrestlers.edit', $wrestler->id));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.edit');
        $response->assertViewHas('wrestler');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_wrestler_page()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('wrestlers.edit', $wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_wrestler_page()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->get(route('wrestlers.edit', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
