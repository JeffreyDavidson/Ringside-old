<?php

namespace Tests\Feature\Venue;

use App\Models\Venue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewVenueTest extends TestCase
{
    use RefreshDatabase;

    private $venue;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-venue');

        $this->venue = factory(Venue::class)->create([
            'name' => 'Venue Name',
            'address' => '123 Main Street',
            'city' => 'Laraville',
            'state' => 'FL',
            'postcode' => '90210',
        ]);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_venue()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('venues.show', $this->venue->id));

        $response->assertSuccessful();
        $response->assertViewIs('venues.show');
        $response->assertViewHas('venue');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_venue()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('venues.show', $this->venue->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_venue()
    {
        $response = $this->get(route('venues.show', $this->venue->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function venues_past_events_are_loaded_for_venue_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('venues.show', $this->venue->id));

        $this->assertTrue($response->data('venue')->relationLoaded('pastEvents'));
    }
}
