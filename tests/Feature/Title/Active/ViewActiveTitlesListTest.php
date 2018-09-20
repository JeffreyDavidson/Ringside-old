<?php

namespace Tests\Feature\Title\Active;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewActiveTitlesListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-titles');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_active_titles()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('active-titles.index'));

        $response->assertSuccessful();
        $response->assertViewIs('titles.active');
        $response->assertViewHas('titles');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_active_titles()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('active-titles.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_list_of_active_titles()
    {
        $response = $this->get(route('active-titles.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
