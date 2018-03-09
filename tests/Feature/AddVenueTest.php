<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddVenueTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-venue', 'store-venue']);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Venue Name',
            'address' => '123 Main Street',
            'city' => 'Laraville',
            'state' => 'ON',
            'postcode' => '12345',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_venue_form()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('venues.create'));

        $response->assertStatus(200);
        $response->assertViewIs('venues.create');
    }

    /** @test */
    public function users_who_have_permission_can_create_a_venue()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index'), $this->validParams());

        tap(Venue::first(), function ($venue) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('venues.index'));

            $this->assertEquals('Venue Name', $venue->name);
            $this->assertEquals('123 Main Street', $venue->address);
            $this->assertEquals('Laraville', $venue->city);
            $this->assertEquals('ON', $venue->state);
            $this->assertEquals('12345', $venue->postcode);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_venue_form()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('venues.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_create_a_venue()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->post(route('venues.index'), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_venue_form()
    {
        $response = $this->get(route('venues.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guests_cannot_create_a_venue()
    {
        $response = $this->post(route('venues.index'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function venue_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index'), $this->validParams([
                            'name' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function venue_name_must_only_contain_letters_numbers_and_spaces()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index'), $this->validParams([
                            'name' => 'Club 83%#(@0@(*U$',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function venue_name_must_be_unique()
    {
        factory(Venue::class)->create(['name' => 'Venue Name']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index', $this->validParams([
                            'name' => 'Venue Name',
                        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function venue_address_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index', $this->validParams([
                            'address' => '',
                        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('address');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function venue_address_must_only_contain_letters_numbers_and_spaces()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index'), $this->validParams([
                            'address' => 'Address 83%#(@0@(*U$',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('address');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function venue_city_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index', $this->validParams([
                            'city' => '',
                        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('city');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function venue_city_must_only_contain_letters_and_spaces()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index', $this->validParams([
                            'city' => '12345',
                        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('city');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function venue_state_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index', $this->validParams([
                            'state' => '',
                        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function venue_state_must_only_contain_letters()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index', $this->validParams([
                            'state' => 'abcd789',
                        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function venue_postcode_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index', $this->validParams([
                            'postcode' => '',
                        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function venue_postcode_must_be_numeric()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index', $this->validParams([
                            'postcode' => 'not-a-number',
                        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function venue_postcode_must_be_5_digits()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.create'))
                        ->post(route('venues.index', $this->validParams([
                            'postcode' => 11111111,
                        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Venue::count());
    }
}
