<?php

namespace Tests\Feature\Title\Active;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteActiveTitleTest extends TestCase
{
    use RefreshDatabase;

    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-title');

        $this->title = factory(Title::class)->states('active')->create();
    }

    /** @test */
    public function users_who_have_permission_can_delete_an_active_title()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('active-titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('active-titles.index'));
        $this->assertSoftDeleted('titles', ['id' => $this->title->id, 'name' => $this->title->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_an_active_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('active-titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(403);
        $this->assertNull($this->title->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_an_active_title()
    {
        $response = $this->from(route('active-titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertNull($this->title->deleted_at);
    }
}
