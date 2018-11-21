<?php

namespace Tests\Feature\Stipulation;

use App\Models\Stipulation;
use Tests\IntegrationTestCase;

class StoreStipulationTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-stipulation']);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Stipulation Name',
            'slug' => 'stipulation-slug',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_store_a_stipulation()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.create'))->post(route('stipulations.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.index'));
        tap(Stipulation::first(), function ($stipulation) {
            $this->assertEquals('Stipulation Name', $stipulation->name);
            $this->assertEquals('stipulation-slug', $stipulation->slug);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_store_a_stipulation()
    {
        $response = $this->actingAs($this->unauthorizedUser)->post(route('stipulations.store', $this->validParams()));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_store_a_stipulation()
    {
        $response = $this->post(route('stipulations.store', $this->validParams()));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function stipulation_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.create'))->post(route('stipulations.store'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function stipulation_name_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.create'))->post(route('stipulations.store'), $this->validParams([
            'name' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function stipulation_name_must_be_unique()
    {
        factory(Stipulation::class)->create(['name' => 'Stipulation Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.create'))->post(route('stipulations.store'), $this->validParams([
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
        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function stipulation_slug_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'slug' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function stipulation_slug_must_be_unique()
    {
        factory(Stipulation::class)->create(['slug' => 'stipulation-slug']);

        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'slug' => 'stipulation-slug',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Stipulation::count());
    }
}
