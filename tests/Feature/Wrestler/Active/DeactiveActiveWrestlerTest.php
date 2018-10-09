<?php

namespace Tests\Feature\Wrestler\Active;

use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeactivateActiveWrestlerTest extends TestCase
{
    use RefreshDatabase;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('deactivate-wrestler');

        $this->wrestler = factory(Wrestler::class)->states('active')->create();
    }

    /** @test */
    public function users_who_have_permission_can_deactivate_an_active_wrestler()
    {
        $response = $this->actingAs($this->authorizedUser)
            ->from(route('active-wrestlers.index'))
            ->delete(route('active-wrestlers.deactivate', $this->wrestler->id));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('active-wrestlers.index'));
        $this->assertFalse($this->wrestler->fresh()->is_active);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_deactivate_an_active_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
            ->from(route('active-wrestlers.index'))
            ->delete(route('active-wrestlers.deactivate', $this->wrestler->id));

        $response->assertStatus(403);
        $this->assertTrue($this->wrestler->is_active);
    }

    /** @test */
    public function guests_cannot_deactivate_an_active_wrestler()
    {
        $response = $this->from(route('active-wrestlers.index'))
            ->delete(route('active-wrestlers.deactivate', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
