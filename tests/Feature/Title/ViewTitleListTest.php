<?php

namespace Tests\Feature\Title;

use Tests\TestCase;
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
    public function users_who_have_permission_can_view_the_index_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('titles.index'));

        $response->assertSuccessful();
        $response->assertViewIs('titles.index');
        $response->assertViewHas('activeTitles');
        $response->assertViewHas('retiredTitles');
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
