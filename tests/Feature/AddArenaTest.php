<?php

namespace Tests\Feature;

use App\Models\Arena;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddArenaTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'My Arena',
            'address' => '123 Main St.',
            'city' => 'Laraville',
            'state' => 'ON',
            'postcode' => '12345',
        ], $overrides);
    }

    private function from($url)
    {
        session()->setPreviousUrl(url($url));
        return $this;
    }

    /** @test */
    function users_can_view_the_add_arena_form()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('arenas.create'));

        $response->assertStatus(200);
    }

    /** @test */
    function guests_cannot_view_the_add_arena_form()
    {
        $response = $this->get(route('arenas.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    function name_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('arenas.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Arena::count());
    }

    /** @test */
    function name_must_be_unique()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(route('arenas.index'), $this->validParams([
            'name' => 'My Arena',
        ]));

        tap(Arena::first(), function ($arena) use ($response) {
            $response->assertStatus(302);
            $this->assertEquals(1, Arena::count());
            $response->assertRedirect(route('arenas.index'));

            $this->assertEquals('My Arena', $arena->name);
        });

        $response = $this->actingAs($user)->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'name' => 'My Arena',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('arenas.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Arena::count());
    }

    /** @test */
    function address_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'address' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('arenas.create'));
        $response->assertSessionHasErrors('address');
        $this->assertEquals(0, Arena::count());
    }

    /** @test */
    function city_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'city' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('arenas.create'));
        $response->assertSessionHasErrors('city');
        $this->assertEquals(0, Arena::count());
    }

    /** @test */
    function state_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'state' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('arenas.create'));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Arena::count());
    }

    /** @test */
    function postcode_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'postcode' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('arenas.create'));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Arena::count());
    }

    /** @test */
    function postcode_must_be_numeric()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'postcode' => 'not a number',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('arenas.create'));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Arena::count());
    }

    /** @test */
    function postcode_must_be_5_digits()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'postcode' => time(),
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('arenas.create'));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Arena::count());
    }

    /** @test */
    function adding_a_valid_arena()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(route('arenas.index'), [
            'name' => 'My Arena',
            'address' => '123 Main St.',
            'city' => 'Laraville',
            'state' => 'ON',
            'postcode' => '12345',
        ]);

        tap(Arena::first(), function ($arena) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('arenas.index'));

            $this->assertEquals('My Arena', $arena->name);
            $this->assertEquals('123 Main St.', $arena->address);
            $this->assertEquals('Laraville', $arena->city);
            $this->assertEquals('ON', $arena->state);
            $this->assertEquals('12345', $arena->postcode);
        });
    }

    /** @test */
    function adding_a_valid_arena_two()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->ajax(route('arenas.index'), [
            'name' => 'My Arena',
            'address' => '123 Main St.',
            'city' => 'Laraville',
            'state' => 'ON',
            'postcode' => '12345',
        ]);

        tap(Arena::first(), function ($arena) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('arenas.index'));

            $this->assertEquals('My Arena', $arena->name);
            $this->assertEquals('123 Main St.', $arena->address);
            $this->assertEquals('Laraville', $arena->city);
            $this->assertEquals('ON', $arena->state);
            $this->assertEquals('12345', $arena->postcode);
        });
    }
}