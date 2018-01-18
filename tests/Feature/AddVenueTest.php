<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddVenueTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'create-venue']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name'     => 'Venue Name',
            'address'  => '123 Main Street',
            'city'     => 'Laraville',
            'state'    => 'ON',
            'postcode' => '12345',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_venue_form()
    {
        $response = $this->actingAs($this->user)->get(route('venues.create'));

        $response->assertStatus(200);
        $response->assertViewIs('venues.create');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_venue_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('venues.create'));

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
    public function name_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function name_must_only_contain_letters_numbers_and_spaces()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index'), $this->validParams([
            'name' => 'Club 83%#(@0@(*U$',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function name_must_be_unique()
    {
        factory(Venue::class)->create(['name' => 'Venue Name']);

        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index', $this->validParams([
            'name' => 'Venue Name',
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Venue::where('name', 'Venue Name')->count());
    }

    /** @test */
    public function address_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index', $this->validParams([
            'address' => '',
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('address');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function address_must_only_contain_letters_numbers_and_spaces()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index'), $this->validParams([
            'address' => 'Address 83%#(@0@(*U$',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('address');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function city_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index', $this->validParams([
            'city' => '',
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('city');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function city_must_only_contain_letters_and_spaces()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index', $this->validParams([
            'city' => '90210',
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('city');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function state_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index', $this->validParams([
            'state' => '',
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function state_must_only_contain_letters()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index', $this->validParams([
            'state' => 'abcd789',
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function state_must_have_a_valid_selection()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index', $this->validParams([
            'state' => '0',
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function postcode_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index', $this->validParams([
            'postcode' => '',
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function postcode_must_be_numeric()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index', $this->validParams([
            'postcode' => 'not a number',
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function postcode_must_be_5_digits()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index', $this->validParams([
            'postcode' => time(),
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function adding_a_valid_venue()
    {
        $response = $this->actingAs($this->user)->from(route('venues.create'))->post(route('venues.index'), $this->validParams());

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
}
