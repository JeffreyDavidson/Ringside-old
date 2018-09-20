<?php

namespace Tests\Feature\Venue;

use Tests\TestCase;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditVenueTest extends TestCase
{
    use RefreshDatabase;

    private $venue;
    private $response;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-venue']);

        $this->venue = factory(Venue::class)->create($this->oldAttributes());
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_venue_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('venues.edit', $this->venue->id));

        $response->assertSuccessful();
        $response->assertViewIs('venues.edit');
        $response->assertViewHas('venue');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_venue_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('venues.edit', $this->venue->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_venue_page()
    {
        $response = $this->get(route('venues.edit', $this->venue->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function users_who_have_permission_can_edit_a_venue()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('venues.edit', $this->venue->id))
                        ->patch(route('venues.update', $this->venue->id), $this->validParams([
                            'name' => 'New Venue Name',
                            'address' => '456 Main Drive',
                            'city' => 'Beverly Hills',
                            'state' => 'California',
                            'postcode' => '90210',
                        ]));

        $response->assertRedirect(route('venues.index'));
        tap(Venue::first(), function ($venue) use ($response) {
            $this->assertEquals('New Venue Name', $venue->name);
            $this->assertEquals('456 Main Drive', $venue->address);
            $this->assertEquals('Beverly Hills', $venue->city);
            $this->assertEquals('California', $venue->state);
            $this->assertEquals('90210', $venue->postcode);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_edit_a_venue()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->patch(route('venues.update', $this->venue->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_edit_a_venue()
    {
        $response = $this->patch(route('venues.update', $this->venue->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function name_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'name' => '',
                            ]));

        $this->assertFormError('name', 'Old Name', $this->venue->name);
    }

    /** @test */
    public function name_must_only_contain_letters_numbers_and_spaces()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'name' => 'Club 83%#(@0@(*U$',
                            ]));

        $this->assertFormError('name', 'Old Name', $this->venue->name);
    }

    /** @test */
    public function name_must_be_unique()
    {
        factory(Venue::class)->create($this->validParams());

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'name' => 'Venue Name',
                            ]));

        $this->assertFormError('name', 'Old Name', $this->venue->name);
    }

    /** @test */
    public function address_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'address' => '',
                            ]));

        $this->assertFormError('address', 'Old Address', $this->venue->address);
    }

    /** @test */
    public function address_must_only_contain_letters_numbers_and_spaces()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'address' => 'Address 83%#(@0@(*U$',
                            ]));

        $this->assertFormError('address', 'Old Address', $this->venue->address);
    }

    /** @test */
    public function city_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'city' => '',
                            ]));

        $this->assertFormError('city', 'Old City', $this->venue->city);
    }

    /** @test */
    public function city_must_only_contain_letters_and_spaces()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id, $this->validParams([
                                'city' => '90210',
                            ])));

        $this->assertFormError('city', 'Old City', $this->venue->city);
    }

    /** @test */
    public function state_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'state' => '',
                            ]));

        $this->assertFormError('state', 'Old State', $this->venue->state);
    }

    /** @test */
    public function state_must_only_contain_letters()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'state' => 'abcd789',
                            ]));

        $this->assertFormError('state', 'Old State', $this->venue->state);
    }

    /** @test */
    public function state_must_have_a_valid_selection()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'state' => '0',
                            ]));

        $this->assertFormError('state', 'Old State', $this->venue->state);
    }

    /** @test */
    public function postcode_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'postcode' => '',
                            ]));

        $this->assertFormError('postcode', '98765', $this->venue->postcode);
    }

    /** @test */
    public function postcode_must_be_numeric()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'postcode' => 'not a number',
                            ]));

        $this->assertFormError('postcode', '98765', $this->venue->postcode);
    }

    /** @test */
    public function postcode_must_be_5_digits()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.edit', $this->venue->id))
                            ->patch(route('venues.update', $this->venue->id), $this->validParams([
                                'postcode' => time(),
                            ]));

        $this->assertFormError('postcode', '98765', $this->venue->postcode);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Name',
            'address' => 'Old Address',
            'city' => 'Old City',
            'state' => 'Old State',
            'postcode' => '98765',
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Venue Name',
            'address' => '123 Main Street',
            'city' => 'Laraville',
            'state' => 'Florida',
            'postcode' => '12345',
        ], $overrides);
    }

    private function assertFormError($field, $expectedValue, $property)
    {
        $this->response->assertRedirect(route('venues.edit', $this->venue->id));
        $this->response->assertSessionHasErrors($field);
        tap($this->venue->fresh(), function ($venue) use ($expectedValue, $property) {
            $this->assertEquals($expectedValue, $property);
        });
    }
}
