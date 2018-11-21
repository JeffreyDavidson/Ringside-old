<?php

namespace Tests\Feature\Venue;

use App\Models\Venue;
use Tests\IntegrationTestCase;

class ViewVenueTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-venue');
    }

    /** @test */
    public function users_who_have_permission_can_view_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('venues.show', $venue->id));

        $response->assertSuccessful();
        $response->assertViewIs('venues.show');
        $response->assertViewHas('venue');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('venues.show', $venue->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $response = $this->get(route('venues.show', $venue->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
