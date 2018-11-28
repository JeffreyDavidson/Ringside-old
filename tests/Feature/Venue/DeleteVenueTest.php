<?php

namespace Tests\Feature\Venue;

use App\Models\Venue;
use Tests\IntegrationTestCase;

class DeleteVenueTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-venue');
    }

    /** @test */
    public function users_who_have_permission_can_soft_delete_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.index'))->delete(route('venues.destroy', $venue->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.index'));
        $this->assertSoftDeleted('venues', ['id' => $venue->id, 'name' => $venue->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_soft_delete_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->from(route('venues.index'))->delete(route('venues.destroy', $venue->id));

        $response->assertStatus(403);
        $this->assertNull($venue->deleted_at);
    }

    /** @test */
    public function guests_cannot_soft_delete_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $response = $this->from(route('venues.index'))->delete(route('venues.destroy', $venue->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertNull($venue->deleted_at);
    }
}
