<?php

namespace Tests\Feature;

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
    public function users_who_have_permission_can_delete_a_title()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(302);
        $this->assertSoftDeleted('titles', $this->title->toArray());
        $response->assertRedirect(route('titles.index'));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_title()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_delete_a_title()
    {
        $response = $this->from(route('titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
