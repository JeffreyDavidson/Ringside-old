<?php

namespace Tests\Feature;

use App\Models\Venue;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewVenueListTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
    }

    /** @test */
    function users_can_view_venue_list()
    {
        $user = factory(User::class)->create();
        $venue = factory(Venue::class)->create();

        $response = $this->actingAs($user)->get('venues');

        $response->assertStatus(200);
        $response->data('venues')->assertContains($venue);

    }

    /** @test */
    function guests_cannot_view_venue_list()
    {
        $response = $this->get('/venues');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}