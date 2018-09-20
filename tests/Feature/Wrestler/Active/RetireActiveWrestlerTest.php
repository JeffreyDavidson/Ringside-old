<?php

namespace Tests\Feature\Wrestler\Active;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RetireActiveWrestlerTest extends TestCase
{
    use RefreshDatabase;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('retire-wrestler');

        $this->wrestler = factory(Wrestler::class)->states('active')->create();
    }

    /** @test */
    public function users_who_have_permission_can_retire_an_active_wrestler()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('active-wrestlers.index'))
                        ->post(route('wrestlers.retire', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('active-wrestlers.index'));
        $this->assertCount(1, $this->wrestler->retirements);
        $this->assertNull($this->wrestler->retirements->first()->ended_at);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_retire_an_active_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('active-wrestlers.index'))
                        ->post(route('wrestlers.retire', $this->wrestler->id));

        $response->assertStatus(403);
        $this->assertCount(0, $this->wrestler->retirements);
    }

    /** @test */
    public function guests_cannot_retire_an_active_wrestler()
    {
        $response = $this->from(route('active-wrestlers.index'))
                        ->post(route('wrestlers.retire', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
