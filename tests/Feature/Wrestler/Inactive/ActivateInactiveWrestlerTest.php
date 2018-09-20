<?php

namespace Tests\Feature\Wrestler\Inactive;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivateInactiveWrestlerTest extends TestCase
{
    use RefreshDatabase;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('activate-wrestler');

        $this->wrestler = factory(Wrestler::class)->states('inactive')->create();
    }

    /** @test */
    public function users_who_have_permission_can_activate_an_inactive_wrestler()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('inactive-wrestlers.index'))
                        ->post(route('inactive-wrestlers.activate', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('inactive-wrestlers.index'));
        $this->assertTrue($this->wrestler->fresh()->is_active);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_activate_an_inactive_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('inactive-wrestlers.index'))
                        ->post(route('inactive-wrestlers.activate', $this->wrestler->id));

        $response->assertStatus(403);
        $this->assertFalse($this->wrestler->is_active);
    }

    /** @test */
    public function guests_cannot_activate_an_inactive_wrestler()
    {
        $response = $this->from(route('inactive-wrestlers.index'))
                        ->post(route('inactive-wrestlers.activate', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
