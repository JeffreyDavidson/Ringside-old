<?php

namespace Tests\Feature\Venue;

use Tests\TestCase;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewVenueListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('view-venues');
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_venues()
    {
        factory(Venue::class)->create(['name' => 'Venue A']);
        factory(Venue::class)->create(['name' => 'Venue B']);
        factory(Venue::class)->create(['name' => 'Venue C']);

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('venues.index'));

        $response->assertSuccessful();
        $response->assertViewIs('venues.index');
        $response->assertSee('Venue A');
        $response->assertSee('Venue B');
        $response->assertSee('Venue C');
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
