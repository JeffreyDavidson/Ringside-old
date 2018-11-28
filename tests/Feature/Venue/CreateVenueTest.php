<?php

namespace Tests\Feature\Venue;

use App\Models\Venue;
use Tests\IntegrationTestCase;

class CreateVenueTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-venue']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_create_venue_page()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('venues.create'));

        $response->assertSuccessful();
        $response->assertViewIs('venues.create');
        $response->assertViewHas('venue');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_create_venue_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('venues.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_create_venue_page()
    {
        $response = $this->get(route('venues.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
