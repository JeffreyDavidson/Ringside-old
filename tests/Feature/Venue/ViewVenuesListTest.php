<?php

namespace Tests\Feature\Venue;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewVenuesListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-venues');
    }

    public function a_user_must_be_authorized_to_view_the_venue_index_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('venues.index'));
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_venues()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('venues.index'));

        $response->assertSuccessful();
        $response->assertViewIs('venues.index');
        $response->assertViewHas('venues');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_venues()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('venues.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_list_of_venues()
    {
        $response = $this->get(route('venues.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
