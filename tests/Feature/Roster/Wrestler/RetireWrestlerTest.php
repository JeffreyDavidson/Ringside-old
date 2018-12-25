<?php

namespace Tests\Feature\Roster\Wrestler;

use App\Models\Roster\Wrestler;
use Tests\IntegrationTestCase;

class RetireWrestlerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('retire-roster-member');
    }

    /** @test */
    public function users_who_have_permission_can_retire_an_active_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('active-wrestlers.index'))->post(route('wrestlers.retire', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('active-wrestlers.index'));
        $this->assertCount(1, $wrestler->retirements);
        $this->assertNull($wrestler->retirements->first()->ended_at);
    }

    /** @test */
    public function users_who_have_permission_can_retire_an_inactive_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('inactive-wrestlers.index'))->post(route('wrestlers.retire', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('inactive-wrestlers.index'));
        $this->assertCount(1, $wrestler->retirements);
        $this->assertNull($wrestler->retirements->first()->ended_at);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_retire_a_restler()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->post(route('wrestlers.retire', $wrestler->id));

        $response->assertStatus(403);
        $this->assertCount(0, $wrestler->retirements);
    }

    /** @test */
    public function guests_cannot_retire_a_wrestler()
    {
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->post(route('wrestlers.retire', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
