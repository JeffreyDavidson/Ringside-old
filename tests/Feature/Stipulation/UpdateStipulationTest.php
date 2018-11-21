<?php

namespace Tests\Feature\Stipulation;

use App\Models\Stipulation;
use Tests\IntegrationTestCase;

class UpdateStipulationTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-stipulation']);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Name',
            'slug' => 'old-slug',
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Stipulation Name',
            'slug' => 'stipulation-slug',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_update_a_stipulation()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.edit', $stipulation->id))->patch(route('stipulations.update', $stipulation->id), [
            'name' => 'New Name',
            'slug' => 'new-slug',
        ]);

        $response->assertRedirect(route('stipulations.index'));
        tap($stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('New Name', $stipulation->name);
            $this->assertEquals('new-slug', $stipulation->slug);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_update_a_stipulation()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->patch(route('stipulations.update', $stipulation->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_update_a_stipulation()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->patch(route('stipulations.update', $stipulation->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function stipulation_name_is_required()
    {
        $stipulation = factory(Stipulation::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.edit', $stipulation->id))->patch(route('stipulations.update', $stipulation->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('stipulations.edit', $stipulation->id));
        $response->assertSessionHasErrors('name');
        tap($stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('Old Name', $stipulation->name);
        });
    }

    /** @test */
    public function stipulation_name_must_be_a_string()
    {
        $stipulation = factory(Stipulation::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.edit', $stipulation->id))->patch(route('stipulations.update', $stipulation->id), $this->validParams([
            'name' => [],
        ]));

        $response->assertRedirect(route('stipulations.edit', $stipulation->id));
        $response->assertSessionHasErrors('name');
        tap($stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('Old Name', $stipulation->name);
        });
    }

    /** @test */
    public function stipulation_name_must_be_unique()
    {
        $stipulation = factory(Stipulation::class)->create($this->oldAttributes());
        factory(Stipulation::class)->create(['name' => 'Stipulation Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.edit', $stipulation->id))->patch(route('stipulations.update', $stipulation->id), $this->validParams([
            'name' => 'Stipulation Name',
        ]));

        $response->assertRedirect(route('stipulations.edit', $stipulation->id));
        $response->assertSessionHasErrors('name');
        tap($stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('Old Name', $stipulation->name);
        });
    }

    /** @test */
    public function stipulation_slug_is_required()
    {
        $stipulation = factory(Stipulation::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.edit', $stipulation->id))->patch(route('stipulations.update', $stipulation->id), $this->validParams([
            'slug' => '',
        ]));

        $response->assertRedirect(route('stipulations.edit', $stipulation->id));
        $response->assertSessionHasErrors('slug');
        tap($stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('old-slug', $stipulation->slug);
        });
    }

    /** @test */
    public function stipulation_slug_must_be_a_string()
    {
        $stipulation = factory(Stipulation::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.edit', $stipulation->id))->patch(route('stipulations.update', $stipulation->id), $this->validParams([
            'slug' => [],
        ]));

        $response->assertRedirect(route('stipulations.edit', $stipulation->id));
        $response->assertSessionHasErrors('slug');
        tap($stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('old-slug', $stipulation->slug);
        });
    }

    /** @test */
    public function stipulation_slug_must_be_unique()
    {
        $stipulation = factory(Stipulation::class)->create($this->oldAttributes());
        factory(Stipulation::class)->create(['slug' => 'stipulation-slug']);

        $response = $this->actingAs($this->authorizedUser)->from(route('stipulations.edit', $stipulation->id))->patch(route('stipulations.update', $stipulation->id), $this->validParams([
            'slug' => 'stipulation-slug',
        ]));

        $response->assertRedirect(route('stipulations.edit', $stipulation->id));
        $response->assertSessionHasErrors('slug');
        tap($stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('old-slug', $stipulation->slug);
        });
    }
}
