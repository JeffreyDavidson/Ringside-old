<?php

namespace Tests\Feature\Wrestler\Inactive;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteInactiveWrestlerTest extends TestCase
{
    use RefreshDatabase;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-wrestler');

        $this->wrestler = factory(Wrestler::class)->states('inactive')->create();
    }

    /** @test */
    public function users_who_have_permission_can_delete_an_inactive_wrestler()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('inactive-wrestlers.index'))
                        ->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('inactive-wrestlers.index'));
        $this->assertSoftDeleted('wrestlers', ['id' => $this->wrestler->id, 'name' => $this->wrestler->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_an_inactive_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('inactive-wrestlers.index'))
                        ->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(403);
        $this->assertNull($this->wrestler->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_an_inactive_wrestler()
    {
        $response = $this->from(route('inactive-wrestlers.index'))
                        ->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
