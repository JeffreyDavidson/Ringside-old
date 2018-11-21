<?php

namespace Tests\Feature\Wrestler\Retired;

use App\Models\Wrestler;
use Tests\IntegrationTestCase;

class UnretireRetiredWrestlerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('unretire-wrestler');
    }

    /** @test */
    public function users_who_have_permission_can_unretire_a_retired_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('retired-wrestlers.index'))->delete(route('retired-wrestlers.unretire', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('retired-wrestlers.index'));
        $this->assertNotNull($wrestler->retirements->first()->ended_at);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_unretire_a_retired_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $response = $this->actingAs($this->unauthorizedUser)->from(route('retired-wrestlers.index'))->delete(route('retired-wrestlers.unretire', $wrestler->id));

        $response->assertStatus(403);
        $this->assertNull($wrestler->retirements->first()->ended_at);
    }

    /** @test */
    public function guests_cannot_unretire_a_retired_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $response = $this->from(route('retired-wrestlers.index'))->delete(route('retired-wrestlers.unretire', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
