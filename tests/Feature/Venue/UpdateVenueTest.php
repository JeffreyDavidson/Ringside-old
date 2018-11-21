<?php

namespace Tests\Feature\Venue;

use App\Models\Venue;
use Tests\IntegrationTestCase;

class UpdateVenueTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-venue']);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Name',
            'address' => 'Old Address',
            'city' => 'Old City',
            'state' => 'CA',
            'postcode' => '98765',
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Venue Name',
            'address' => '123 Main Street',
            'city' => 'Laraville',
            'state' => 'FL',
            'postcode' => '12345',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_update_a_venue()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams());

        $response->assertRedirect(route('venues.index'));
        tap(Venue::first(), function ($venue) {
            $this->assertEquals('Venue Name', $venue->name);
            $this->assertEquals('123 Main Street', $venue->address);
            $this->assertEquals('Laraville', $venue->city);
            $this->assertEquals('FL', $venue->state);
            $this->assertEquals('12345', $venue->postcode);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_update_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->patch(route('venues.update', $venue->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_update_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $response = $this->patch(route('venues.update', $venue->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function venue_name_is_required()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('name');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('Old Name', $venue->name);
        });
    }

    /** @test */
    public function venue_name_must_be_a_string()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'name' => [],
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('name');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('Old Name', $venue->name);
        });
    }

    /** @test */
    public function venue_name_must_only_contain_letters_numbers_and_spaces()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'name' => 'Club 83%#(@0@(*U$',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('name');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('Old Name', $venue->name);
        });
    }

    /** @test */
    public function venue_name_must_be_unique()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());
        factory(Venue::class)->create($this->validParams());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'name' => 'Venue Name',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('name');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('Old Name', $venue->name);
        });
    }

    /** @test */
    public function venue_address_is_required()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'address' => '',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('address');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('Old Address', $venue->address);
        });
    }

    /** @test */
    public function venue_address_must_be_a_string()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'address' => [],
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('address');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('Old Address', $venue->address);
        });
    }

    /** @test */
    public function venue_address_must_only_contain_letters_numbers_and_spaces()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'address' => 'Address 83%#(@0@(*U$',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('address');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('Old Address', $venue->address);
        });
    }

    /** @test */
    public function venue_city_is_required()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'city' => '',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('city');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('Old City', $venue->city);
        });
    }

    /** @test */
    public function venue_city_must_be_a_string()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'city' => '',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('city');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('Old City', $venue->city);
        });
    }

    /** @test */
    public function venue_city_must_only_contain_letters_and_spaces()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id, $this->validParams([
            'city' => '90210',
        ])));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('city');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('Old City', $venue->city);
        });
    }

    /** @test */
    public function venue_state_is_required()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'state' => '',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('state');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('CA', $venue->state);
        });
    }

    public function venue_state_must_be_a_string()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'state' => [],
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('state');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('CA', $venue->state);
        });
    }

    /** @test */
    public function venue_state_must_only_contain_letters()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'state' => 'abcd789',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('state');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('CA', $venue->state);
        });
    }

    /** @test */
    public function venue_state_must_only_contain_two_letters()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'state' => 'AAA',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('state');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('CA', $venue->state);
        });
    }

    /** @test */
    public function venue_postcode_is_required()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'postcode' => '',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('postcode');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('98765', $venue->postcode);
        });
    }

    /** @test */
    public function venue_postcode_must_be_numeric()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'postcode' => 'not a number',
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('postcode');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('98765', $venue->postcode);
        });
    }

    /** @test */
    public function venue_postcode_must_be_5_digits()
    {
        $venue = factory(Venue::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('venues.edit', $venue->id))->patch(route('venues.update', $venue->id), $this->validParams([
            'postcode' => 111111,
        ]));

        $response->assertRedirect(route('venues.edit', $venue->id));
        $response->assertSessionHasErrors('postcode');
        tap($venue->fresh(), function ($venue) {
            $this->assertEquals('98765', $venue->postcode);
        });
    }
}
