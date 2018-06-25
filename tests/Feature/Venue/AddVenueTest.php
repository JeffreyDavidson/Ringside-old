<?php

namespace Tests\Feature\Venue;

use Tests\TestCase;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddVenueTest extends TestCase
{
    use RefreshDatabase;

    private $response;

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

    private function assertFormError($field, $expectedEventCount = 0)
    {
        $this->response->assertStatus(302);
        $this->response->assertRedirect(route('venues.create'));
        $this->response->assertSessionHasErrors($field);
        $this->assertEquals($expectedEventCount, Venue::count());
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_venue_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('venues.create'));

        $response->assertSuccessful();
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
    public function users_who_dont_have_permission_cannot_view_the_add_venue_page()
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
    public function guests_cannot_view_the_add_venue_page()
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
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index'), $this->validParams([
                                'name' => '',
                            ]));

        $this->assertFormError('name');
    }

    /** @test */
    public function venue_name_must_only_contain_letters_numbers_and_spaces()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index'), $this->validParams([
                                'name' => 'Club 83%#(@0@(*U$',
                            ]));

        $this->assertFormError('name');
    }

    /** @test */
    public function venue_name_must_be_unique()
    {
        factory(Venue::class)->create(['name' => 'Venue Name']);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index', $this->validParams([
                                'name' => 'Venue Name',
                            ])));

        $this->assertFormError('name', 1);
    }

    /** @test */
    public function venue_address_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index', $this->validParams([
                                'address' => '',
                            ])));

        $this->assertFormError('address');
    }

    /** @test */
    public function venue_address_must_only_contain_letters_numbers_and_spaces()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index'), $this->validParams([
                                'address' => 'Address 83%#(@0@(*U$',
                            ]));

        $this->assertFormError('address');
    }

    /** @test */
    public function venue_city_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index', $this->validParams([
                                'city' => '',
                            ])));

        $this->assertFormError('city');
    }

    /** @test */
    public function venue_city_must_only_contain_letters_and_spaces()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index', $this->validParams([
                                'city' => '12345',
                            ])));

        $this->assertFormError('city');
    }

    /** @test */
    public function venue_state_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index', $this->validParams([
                                'state' => '',
                            ])));

        $this->assertFormError('state');
    }

    /** @test */
    public function venue_state_must_only_contain_letters()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index', $this->validParams([
                                'state' => 'abcd789',
                            ])));

        $this->assertFormError('state');
    }

    /** @test */
    public function venue_postcode_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index', $this->validParams([
                                'postcode' => '',
                            ])));

        $this->assertFormError('postcode');
    }

    /** @test */
    public function venue_postcode_must_be_numeric()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index', $this->validParams([
                                'postcode' => 'not-a-number',
                            ])));

        $this->assertFormError('postcode');
    }

    /** @test */
    public function venue_postcode_must_be_5_digits()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('venues.create'))
                            ->post(route('venues.index', $this->validParams([
                                'postcode' => 11111111,
                            ])));

        $this->assertFormError('postcode');
    }
}
