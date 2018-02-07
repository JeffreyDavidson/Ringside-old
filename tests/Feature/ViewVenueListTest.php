<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Venue;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewVenueListTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-venues');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_venues()
    {
        $venueA = factory(Venue::class)->create();
        $venueB = factory(Venue::class)->create();
        $venueC = factory(Venue::class)->create();

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('venues.index'));

        $response->assertStatus(200);
        $response->data('venues')->assertEquals([
            $venueA,
            $venueB,
            $venueC
        ]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_venues()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('venues.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_venue_list()
    {
        $response = $this->get(route('venues.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
