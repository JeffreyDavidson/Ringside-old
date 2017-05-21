<?php

namespace Tests\Feature;

use App\Models\Arena;
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
            'zip' => '12345',
        ], $overrides);
    }

    private function from($url)
    {
        session()->setPreviousUrl(url($url));
        return $this;
    }

    /** @test */
    function name_is_required()
    {
        $response = $this->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
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
        $response = $this->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'name' => 'My Arena',
        ]));

        tap(Arena::first(), function ($arena) use ($response) {
            dd($arena);
            $response->assertStatus(302);
            $this->assertEquals(1, Arena::count());
            $response->assertRedirect(route('arenas.index'));

            $this->assertEquals('My Arena', $arena->name);
        });
    }

    /** @test */
    function address_is_required()
    {
        $response = $this->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
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
        $response = $this->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
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
        $response = $this->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
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
        $response = $this->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
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
        $response = $this->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'postcode' => 'not a number',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('arenas.create'));
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Arena::count());
    }

    /** @test */
    public function an_arena_requires_a_postcode_of_exactly_5_digits()
    {
        $this->createArena(['postcode' => 445544]);
        $this->createArena(['postcode' => 4455])
            ->assertSessionHasErrors('postcode');
    }

    /** @test */
    function adding_a_valid_arena()
    {
        $this->disableExceptionHandling();

        $response = $this->post(route('arenas.index'), [
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
            $this->assertEquals('12345', $arena->zip);
        });
    }
}