<?php

namespace Tests\Feature\Wrestler\Inactive;

use App\Models\Wrestler;
use Carbon\Carbon;
use Tests\IntegrationTestCase;

class ActivateInactiveWrestlerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('activate-wrestler');
    }

    /** @test */
    public function users_who_have_permission_can_activate_an_inactive_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create(['hired_at' => Carbon::yesterday()]);

        $response = $this->actingAs($this->authorizedUser)->from(route('inactive-wrestlers.index'))->post(route('inactive-wrestlers.activate', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('inactive-wrestlers.index'));
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertTrue($wrestler->isActive());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_activate_an_inactive_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $response = $this->actingAs($this->unauthorizedUser)->from(route('inactive-wrestlers.index'))->post(route('inactive-wrestlers.activate', $wrestler->id));

        $response->assertStatus(403);
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertFalse($wrestler->isActive());
        });
    }

    /** @test */
    public function guests_cannot_activate_an_inactive_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $response = $this->from(route('inactive-wrestlers.index'))->post(route('inactive-wrestlers.activate', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
