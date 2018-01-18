<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class EditVenueTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    private $venue;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'edit-venue']);
        $this->venue = factory(Venue::class)->create($this->oldAttributes());

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name'     => 'Old Name',
            'address'  => 'Old Address',
            'city'     => 'Old City',
            'state'    => 'Old State',
            'postcode' => '98765',
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name'     => 'Venue Name',
            'address'  => '123 Main Street',
            'city'     => 'Laraville',
            'state'    => 'Florida',
            'postcode' => '12345',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_venue_form()
    {
        $response = $this->actingAs($this->user)->get(route('venues.edit', $this->venue->id));

        $response->assertStatus(200);
        $response->assertViewIs('venues.edit');
        $this->assertTrue($response->data('venue')->is($this->venue));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_venue_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('venues.edit', $this->venue->id));

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
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function name_must_only_contain_letters_numbers_and_spaces()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'name' => 'Club 83%#(@0@(*U$',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function name_must_be_unique()
    {
        factory(Venue::class)->create($this->validParams());

        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'name' => 'Venue Name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Venue::where('name', 'Old Name')->count());
        tap($this->venue->fresh(), function ($venue) {
            $this->assertEquals('Old Name', $venue->name);
        });
    }

    /** @test */
    public function address_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'address' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('address');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function address_must_only_contain_letters_numbers_and_spaces()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'address' => 'Address 83%#(@0@(*U$',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('address');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function city_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'city' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('city');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function city_must_only_contain_letters_and_spaces()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id, $this->validParams([
            'city' => '90210',
        ])));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('city');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function state_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'state' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function state_must_only_contain_letters()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'state' => 'abcd789',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function state_must_have_a_valid_selection()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'state' => '0',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function postcode_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'postcode' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function postcode_must_be_numeric()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'postcode' => 'not a number',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function postcode_must_be_5_digits()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'postcode' => time(),
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.edit', $this->venue->id));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    public function editing_a_valid_venue()
    {
        $response = $this->actingAs($this->user)->from(route('venues.edit', $this->venue->id))->patch(route('venues.update', $this->venue->id), $this->validParams([
            'name'     => 'New Venue Name',
            'address'  => '456 Main Drive',
            'city'     => 'Beverly Hills',
            'state'    => 'California',
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
}
