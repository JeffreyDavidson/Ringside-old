<?php

namespace Tests\Feature\Title;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTitleTest extends TestCase
{
    use RefreshDatabase;

    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-title');

        $this->title = factory(Title::class)->create();
    }

    /** @test */
    public function users_who_have_permission_can_soft_delete_a_title()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.index'));
        $this->assertSoftDeleted('titles', ['id' => $this->title->id, 'name' => $this->title->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_soft_delete_a_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(403);
        $this->assertNull($this->title->deleted_at);
    }

    /** @test */
    public function guests_cannot_soft_delete_a_title()
    {
        $response = $this->from(route('titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertNull($this->title->deleted_at);
    }
}
