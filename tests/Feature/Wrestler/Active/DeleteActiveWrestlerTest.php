<?php

namespace Tests\Feature\Wrestler\Active;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteActiveWrestlerTest extends TestCase
{
    use RefreshDatabase;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-wrestler');

        $this->wrestler = factory(Wrestler::class)->states('active')->create();
    }

    /** @test */
    public function users_who_have_permission_can_delete_an_active_wrestler()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('active-wrestlers.index'))
                        ->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('active-wrestlers.index'));
        $this->assertSoftDeleted('wrestlers', ['id' => $this->wrestler->id, 'name' => $this->wrestler->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_an_active_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('active-wrestlers.index'))
                        ->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(403);
        $this->assertNull($this->wrestler->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_an_active_wrestler()
    {
        $response = $this->from(route('active-wrestlers.index'))
                        ->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
