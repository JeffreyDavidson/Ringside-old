<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Venue;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteVenueTest extends TestCase
{
    use DatabaseMigrations;

    private $venue;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-venue');

        $this->venue = factory(Venue::class)->create();
    }

    /** @test */
    public function users_who_have_permission_can_soft_delete_a_venue()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->authorizedUser)->from(route('venues.index'))->delete(route('venues.destroy', $this->venue->id));

        $response->assertStatus(302);
        $this->assertSoftDeleted('venues', $this->venue->toArray());
        $response->assertRedirect(route('venues.index'));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_venue()
    {
        $response = $this->actingAs($this->unauthorizedUser)->from(route('venues.index'))->delete(route('venues.destroy', $this->venue->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_delete_a_venue()
    {
        $response = $this->from(route('venues.index'))->delete(route('venues.destroy', $this->venue->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
