<?php

namespace Tests\Feature\Title\Retired;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewInactiveTitlesListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-titles');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_inactive_titles()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('inactive-titles.index'));

        $response->assertSuccessful();
        $response->assertViewIs('titles.inactive');
        $response->assertViewHas('titles');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_inactive_titles()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('inactive-titles.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_list_of_inactive_titles()
    {
        $response = $this->get(route('inactive-titles.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
