<?php

namespace Tests\Feature;

use App\Models\Arena;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateArenasTest extends TestCase
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
    public function name_is_required()
    {
        $response = $this->from(route('arenas.create'))->post(route('arenas.index'), $this->validParams([
            'title' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('title');
        $this->assertEquals(0, Concert::count());
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
    public function an_arena_requires_a_postcode_of_exactly_5_digits()
    {
        $this->createArena(['postcode' => 445544]);
        $this->createArena(['postcode' => 4455])
            ->assertSessionHasErrors('postcode');
    }

    /** @test */
    public function can_see_created_arena_after_form_submission()
    {
        $this->createArena(['name' => 'Amway Center', 'address' => '400 W Church St', 'city' => 'Orlando', 'state' => 'Florida', 'postcode' => 32801]);

        $this->get(route('arenas.index'))
            ->see('Amway Center')
            ->see('400 W Church St')
            ->see('Orlando')
            ->see('Florida')
            ->see(32801);
    }

    public function createArena($overrides = [])
    {
        $this->withExceptionHandling();

        $arena = factory(Arena::class)->make($overrides);

        return $this->post(route('arenas.index'), $arena->toArray());
    }
}