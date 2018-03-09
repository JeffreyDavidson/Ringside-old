<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditStipulationTest extends TestCase
{
    use RefreshDatabase;

    private $stipulation;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['edit-stipulation', 'update-stipulation']);

        $this->stipulation = factory(Stipulation::class)->create($this->oldAttributes());
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
    public function users_who_have_permission_can_view_the_edit_stipulation_form()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('stipulations.edit', $this->stipulation->id));

        $response->assertSuccessful();
        $this->assertTrue($response->data('stipulation')->is($this->stipulation));
    }

    /** @test */
    public function users_who_have_permission_can_edit_a_stipulation()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.edit', $this->stipulation->id))
                        ->patch(route('stipulations.update', $this->stipulation->id), [
                            'name' => 'New Name',
                            'slug' => 'new-slug',
                        ]);

        $response->assertRedirect(route('stipulations.index'));
        tap($this->stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('New Name', $stipulation->name);
            $this->assertEquals('new-slug', $stipulation->slug);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_stipulation_form()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('stipulations.edit', $this->stipulation->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_edit_a_stipulation ()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->patch(route('stipulations.update', $this->stipulation->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_stipulation_form()
    {
        $response = $this->get(route('stipulations.edit', $this->stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guests_cannot_edit_a_stipulation()
    {
        $response = $this->patch(route('stipulations.update', $this->stipulation->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function stipulation_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.edit', $this->stipulation->id))
                        ->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
                            'name' => '',
                        ]));

        $response->assertRedirect(route('stipulations.edit', $this->stipulation->id));
        $response->assertSessionHasErrors('name');
        tap($this->stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('Old Name', $stipulation->name);
        });
    }

    /** @test */
    public function stipulation_name_must_be_unique()
    {
        factory(Stipulation::class)->create(['name' => 'Stipulation Name']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.edit', $this->stipulation->id))
                        ->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
                            'name' => 'Stipulation Name',
                        ]));

        $response->assertRedirect(route('stipulations.edit', $this->stipulation->id));
        $response->assertSessionHasErrors('name');
        tap($this->stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('Old Name', $stipulation->name);
        });
    }

    /** @test */
    public function stipulation_slug_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.edit', $this->stipulation->id))
                        ->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
                            'slug' => '',
                        ]));

        $response->assertRedirect(route('stipulations.edit', $this->stipulation->id));
        $response->assertSessionHasErrors('slug');
        tap($this->stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('old-slug', $stipulation->slug);
        });
    }

    /** @test */
    public function stipulation_slug_must_be_unique()
    {
        factory(Stipulation::class)->create(['slug' => 'stipulation-slug']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.edit', $this->stipulation->id))
                        ->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
                            'slug' => 'stipulation-slug',
                        ]));

        $response->assertRedirect(route('stipulations.edit', $this->stipulation->id));
        $response->assertSessionHasErrors('slug');
        tap($this->stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('old-slug', $stipulation->slug);
        });
    }
}
