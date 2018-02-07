<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddStipulationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-stipulation', 'store-stipulation']);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Stipulation Name',
            'slug' => 'stipulation-slug',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_stipulation_form()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('stipulations.create'));

        $response->assertSuccessful();
    }

    /** @test */
    public function users_who_have_permission_can_create_a_stipulation()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.create'))
                        ->post(route('stipulations.index'), $this->validParams());

        tap(Stipulation::first(), function ($stipulation) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('stipulations.index'));

            $this->assertEquals('Stipulation Name', $stipulation->name);
            $this->assertEquals('stipulation-slug', $stipulation->slug);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_stipulation_form()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('stipulations.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_create_a_stipulation()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->post(route('stipulations.store', $this->validParams()));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_stipulation_form()
    {
        $response = $this->get(route('stipulations.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guests_cannot_create_a_stipulation()
    {
        $response = $this->post(route('stipulations.store', $this->validParams()));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function stipulation_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
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
    public function stipulation_name_must_be_unique()
    {
        factory(Stipulation::class)->create(['name' => 'Stipulation Name']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.create'))
                        ->post(route('stipulations.index'), $this->validParams([
                            'name' => 'Stipulation Name',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Stipulation::count());
    }

    /** @test */
    public function stipulation_slug_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.create'))
                        ->post(route('stipulations.index'), $this->validParams([
                            'slug' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Stipulation::count());
    }

    /** @test */
    public function stipulation_slug_must_be_unique()
    {
        factory(Stipulation::class)->create(['slug' => 'stipulation-slug']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.create'))
                        ->post(route('stipulations.index'), $this->validParams([
                            'slug' => 'stipulation-slug',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Stipulation::count());
    }
}
