<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteWrestlerTest extends TestCase
{
    use DatabaseMigrations;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-wrestler');

        $this->wrestler = factory(Wrestler::class)->create();
    }

    /** @test */
    public function users_who_have_permission_can_delete_a_wrestler()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.index'))
                        ->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(302);
        $this->assertSoftDeleted('wrestlers', $this->wrestler->toArray());
        $response->assertRedirect(route('wrestlers.index'));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('wrestlers.index'))
                        ->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_delete_a_wrestler()
    {
        $response = $this->from(route('wrestlers.index'))
                        ->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
