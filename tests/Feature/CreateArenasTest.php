<?php

namespace Tests\Feature;

use App\Arena;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateArenasTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_arena_requires_a_name()
    {
        $this->createArena(['name' => null])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function an_arena_name_must_be_unique()
    {
        $this->createArena(['name' => 'My Arena']);
        $this->createArena(['name' => 'My Arena'])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function an_arena_requires_an_address()
    {
        $this->createArena(['address' => null])
            ->assertSessionHasErrors('address');
    }

    /** @test */
    public function an_arena_requires_a_city()
    {
        $this->createArena(['city' => null])
            ->assertSessionHasErrors('city');
    }

    /** @test */
    public function an_arena_requires_a_state()
    {
        $this->createArena(['state' => null])
            ->assertSessionHasErrors('state');
    }

    /** @test */
    public function an_arena_requires_a_postcode()
    {
        $this->createArena(['postcode' => null])
            ->assertSessionHasErrors('postcode');
    }

    /** @test */
    public function an_arena_requires_a_numeric_postcode()
    {
        $this->createArena(['postcode' => 'invalid'])
            ->assertSessionHasErrors('postcode');
    }

    /** @test */
    public function an_arena_requires_a_postcode_of_only_6_digits()
    {
        $this->createArena(['postcode' => 445544])
            ->assertSessionHasErrors('postcode');
    }

    /** @test */
    public function can_see_created_arena_after_form_submission()
    {
        $arena = $this->createArena();

        $this->get(route('arenas.index'))
            ->see($arena->name)
            ->see($arena->address)
            ->see($arena->city)
            ->see($arena->state)
            ->see($arena->postcode);
    }

    public function createArena($overrides = [])
    {
        $this->withExceptionHandling();

        $arena = factory(Arena::class)->make($overrides);

        return $this->post(route('arenas.index'), $arena->toArray());
    }
}