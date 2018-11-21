<?php

namespace Tests\Feature\Wrestler;

use App\Models\Wrestler;
use Tests\IntegrationTestCase;

class DeleteWrestlerTest extends IntegrationTestCase
{
    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-wrestler');
    }

    /** @test */
    public function users_who_have_permission_can_delete_an_inactive_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('inactive-wrestlers.index'))->delete(route('wrestlers.destroy', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('inactive-wrestlers.index'));
        $this->assertSoftDeleted('wrestlers', ['id' => $wrestler->id, 'name' => $wrestler->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_an_inactive_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)->delete(route('wrestlers.destroy', $wrestler->id));

        $response->assertStatus(403);
        $this->assertNull($wrestler->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_an_inactive_wrestler()
    {
        $response = $this->delete(route('wrestlers.destroy', $wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
