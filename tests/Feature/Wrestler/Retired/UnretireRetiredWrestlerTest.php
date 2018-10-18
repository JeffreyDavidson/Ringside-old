<?php

namespace Tests\Feature\Wrestler\Retired;

use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnretireRetiredWrestlerTest extends TestCase
{
    use RefreshDatabase;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('unretire-wrestler');

        $this->wrestler = factory(Wrestler::class)->states('retired')->create();
    }

    /** @test */
    public function users_who_have_permission_can_unretire_a_retired_wrestler()
    {
        $response = $this->actingAs($this->authorizedUser)
            ->from(route('retired-wrestlers.index'))
            ->delete(route('retired-wrestlers.unretire', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('retired-wrestlers.index'));
        $this->assertNotNull($this->wrestler->retirements->first()->ended_at);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_unretire_a_retired_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
            ->from(route('retired-wrestlers.index'))
            ->delete(route('retired-wrestlers.unretire', $this->wrestler->id));

        $response->assertStatus(403);
        $this->assertNull($this->wrestler->retirements->first()->ended_at);
    }

    /** @test */
    public function guests_cannot_unretire_a_retired_wrestler()
    {
        $response = $this->from(route('retired-wrestlers.index'))
            ->delete(route('retired-wrestlers.unretire', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
