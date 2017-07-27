<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Venue;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditVenueTest extends TestCase
{
    use DatabaseMigrations;

    private $permission;
    private $role;

    public function setUp()
    {
        parent::setUp();

        $this->permission = factory(Permission::class)->create(['name' => 'Create A Venue', 'slug' => 'create_venue']);
        $this->role = factory(Role::class)->create(['name' => 'Administrator', 'slug' => 'admin']);
        factory(Role::class)->create(['name' => 'Basic User', 'slug' => 'basic']);
        $this->role->givePermissionTo($this->permission);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'My Venue',
            'address' => '123 Main St.',
            'city' => 'Laraville',
            'state' => 'ON',
            'postcode' => '12345'
        ], $overrides);
    }

    private function from($url)
    {
        session()->setPreviousUrl(url($url));
        return $this;
    }

    /** @test */
    function basic_users_cannot_view_the_add_venue_form()
    {
        $user = factory(User::class)->states('basic')->create();

        $response = $this->actingAs($user)->get('venues/create');

        $response->assertStatus(403);
    }

    /** @test */
    function admins_can_view_the_add_venue_form()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->get('venues/create');

        $response->assertStatus(200);
    }

    /** @test */
    function guests_cannot_view_the_add_venue_form()
    {
        $response = $this->get('venues/create');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function name_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
                        ->from('venues/create')
                        ->post('venues', $this->validParams(['name' => '']));

        $response->assertStatus(302);
        $response->assertRedirect('venues/create');
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    function name_must_be_unique()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)->post('venues', $this->validParams(['name' => 'My Venue']));

        tap(Venue::first(), function ($venue) use ($response) {
            $response->assertStatus(302);
            $this->assertEquals(1, Venue::count());
            $response->assertRedirect('venues');

            $this->assertEquals('My Venue', $venue->name);
        });

        $response = $this->actingAs($user)
                        ->from('venues/create')
                        ->post('venues', $this->validParams(['name' => 'My Venue']));

        $response->assertStatus(302);
        $response->assertRedirect('venues/create');
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Venue::count());
    }

    /** @test */
    function address_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
                        ->from('venues/create')
                        ->post('venues', $this->validParams(['address' => '']));

        $response->assertStatus(302);
        $response->assertRedirect('venues/create');
        $response->assertSessionHasErrors('address');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    function city_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
                        ->from('venues/create')
                        ->post('venues', $this->validParams(['city' => '']));

        $response->assertStatus(302);
        $response->assertRedirect('venues/create');
        $response->assertSessionHasErrors('city');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    function state_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
                        ->from('venues/create')
                        ->post('venues', $this->validParams(['state' => '']));

        $response->assertStatus(302);
        $response->assertRedirect('venues/create');
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    function state_must_have_a_valid_selection()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
                        ->from('venues/create')
                        ->post('venues', $this->validParams(['state' => '0']));

        $response->assertStatus(302);
        $response->assertRedirect('venues/create');
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    function postcode_is_required()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
                        ->from('venues/create')
                        ->post('venues', $this->validParams(['postcode' => '']));

        $response->assertStatus(302);
        $response->assertRedirect('venues/create');
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    function postcode_must_be_numeric()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
                        ->from('venues/create')
                        ->post('venues', $this->validParams(['postcode' => 'not a number']));

        $response->assertStatus(302);
        $response->assertRedirect('venues/create');
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    function postcode_must_be_5_digits()
    {
        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
                        ->from('venues/create')
                        ->post('venues', $this->validParams(['postcode' => time()]));

        $response->assertStatus(302);
        $response->assertRedirect('venues/create');
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    function adding_a_valid_venue()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($user)
                        ->post('venues', [
                            'name' => 'My Venue',
                            'address' => '123 Main St.',
                            'city' => 'Laraville',
                            'state' => 'ON',
                            'postcode' => '12345'
                        ]);

        tap(Venue::first(), function ($venue) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect('venues');

            $this->assertEquals('My Venue', $venue->name);
            $this->assertEquals('123 Main St.', $venue->address);
            $this->assertEquals('Laraville', $venue->city);
            $this->assertEquals('ON', $venue->state);
            $this->assertEquals('12345', $venue->postcode);
        });
    }
}
