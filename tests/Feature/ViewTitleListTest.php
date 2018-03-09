<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTitleListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-titles');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_titles()
    {
        $titleA = factory(Title::class)->create();
        $titleB = factory(Title::class)->create();
        $titleC = factory(Title::class)->create();

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('titles.index'));

        $response->assertStatus(200);
        $response->data('titles')->assertEquals([
            $titleA,
            $titleB,
            $titleC,
        ]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_titles()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('titles.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_title_list()
    {
        $response = $this->get(route('titles.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
