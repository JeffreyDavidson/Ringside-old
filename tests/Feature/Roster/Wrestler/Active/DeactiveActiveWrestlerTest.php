<?php

namespace Tests\Feature\Roster\Wrestler\Active;

use App\Models\Roster\Wrestler;
use Tests\IntegrationTestCase;

class DeactivateActiveWrestlerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('deactivate-roster-member');
    }

    /** @test */
    public function users_who_have_permission_can_deactivate_an_active_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('active-wrestlers.index'))->delete(route('active-wrestlers.deactivate', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('active-wrestlers.index'));
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertFalse($wrestler->isActive());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_deactivate_a_wrestler()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->from(route('active-wrestlers.index'))->delete(route('active-wrestlers.deactivate', $wrestler->id));

        $response->assertStatus(403);
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertTrue($wrestler->isActive());
        });
    }

    /** @test */
    public function guests_cannot_deactivate_an_active_wrestler()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->from(route('active-wrestlers.index'))->delete(route('active-wrestlers.deactivate', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
