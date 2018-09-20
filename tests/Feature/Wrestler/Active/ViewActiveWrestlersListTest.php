<?php

namespace Tests\Feature\Wrestler\Active;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewActiveWrestlersListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-wrestlers');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_active_wrestlers()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('active-wrestlers.index'));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.active');
        $response->assertViewHas('wrestlers');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_active_wrestlers()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('active-wrestlers.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_list_of_active_wrestlers()
    {
        $response = $this->get(route('active-wrestlers.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
