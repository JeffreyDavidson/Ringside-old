<?php

namespace Tests\Feature;

use App\Models\Venue;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewVenueListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function users_can_view_venue_listings()
    {
        $user = factory(User::class)->create();

        factory(Venue::class)->create([
            'name' => 'My Venue',
            'address' => '123 Example Lane',
            'city' => 'Laraville',
            'state' => 'ON',
            'postcode' => '90210',
        ]);

        $response = $this->actingAs($user)->get(route('venues.index'));

        $response->assertStatus(200);
        $response->assertSee('My Venue');
        $response->assertSee('123 Example Lane');
        $response->assertSee('Laraville');
        $response->assertSee('ON');
        $response->assertSee('90210');
    }

    /** @test */
    function guests_cannot_view_venue_listings()
    {
        $response = $this->get(route('venues.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}