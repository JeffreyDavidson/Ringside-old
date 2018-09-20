<?php

namespace Tests\Feature\Title\Inactive;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteInactiveTitleTest extends TestCase
{
    use RefreshDatabase;

    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-title');

        $this->title = factory(Title::class)->states('inactive')->create();
    }

    /** @test */
    public function users_who_have_permission_can_delete_an_inactive_title()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('inactive-titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('inactive-titles.index'));
        $this->assertSoftDeleted('titles', ['id' => $this->title->id, 'name' => $this->title->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_an_inactive_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('inactive-titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(403);
        $this->assertNull($this->title->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_an_inactive_title()
    {
        $response = $this->from(route('inactive-titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertNull($this->title->deleted_at);
    }
}
