<?php

namespace Tests\Feature\Venue;

use Tests\TestCase;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteVenueTest extends TestCase
{
    use RefreshDatabase;

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
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.index'))
                        ->delete(route('venues.destroy', $this->venue->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.index'));
        $this->assertSoftDeleted('venues', ['id' => $this->venue->id, 'name' => $this->venue->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_soft_delete_a_venue()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->from(route('venues.index'))
                        ->delete(route('venues.destroy', $this->venue->id));

        $response->assertStatus(403);
        $this->assertNull($this->venue->deleted_at);
    }

    /** @test */
    public function guests_cannot_soft_delete_a_venue()
    {
        $response = $this->from(route('venues.index'))
                        ->delete(route('venues.destroy', $this->venue->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertNull($this->venue->deleted_at);
    }
}
