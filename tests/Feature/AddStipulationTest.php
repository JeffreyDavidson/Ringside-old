<?php

namespace Tests\Feature;

use App\Models\Stipulation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddStipulationTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'My Stipulation',
            'slug' => 'mystip',
        ], $overrides);
    }

    private function from($url)
    {
        session()->setPreviousUrl(url($url));
        return $this;
    }

    /** @test */
    function users_can_view_the_add_stipulation_form()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('stipulations.create'));

        $response->assertStatus(200);
    }

    /** @test */
    function guests_cannot_view_the_add_stipulation_form()
    {
        $response = $this->get(route('stipulations.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    function name_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                        ->from(route('stipulations.create'))
                        ->post(route('stipulations.index'), $this->validParams([
                            'name' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Stipulation::count());
    }

    /** @test */
    function name_must_be_unique()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(route('stipulations.index'), $this->validParams([
            'name' => 'My Stipulation',
        ]));

        tap(Stipulation::first(), function ($venue) use ($response) {
            $response->assertStatus(302);
            $this->assertEquals(1, Stipulation::count());
            $response->assertRedirect(route('stipulations.index'));

            $this->assertEquals('My Stipulation', $venue->name);
        });

        $response = $this->actingAs($user)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'name' => 'My Stipulation',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Stipulation::count());
    }

    /** @test */
    function slug_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Stipulation::count());
    }

    /** @test */
    function slug_must_be_unique()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(route('stipulations.index'), $this->validParams([
            'slug' => 'mystip',
        ]));

        tap(Stipulation::first(), function ($stipulation) use ($response) {
            $response->assertStatus(302);
            $this->assertEquals(1, Stipulation::count());
            $response->assertRedirect(route('stipulations.index'));

            $this->assertEquals('mystip', $stipulation->slug);
        });

        $response = $this->actingAs($user)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'slug' => 'mystip',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Stipulation::count());
    }

    /** @test */
    function adding_a_valid_stipulation()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(route('stipulations.index'), [
            'name' => 'My Stipulation',
            'slug' => 'mystip',
        ]);

        tap(Stipulation::first(), function ($stipulation) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('stipulations.index'));

            $this->assertEquals('My Stipulation', $stipulation->name);
            $this->assertEquals('mystip', $stipulation->slug);
        });
    }
}