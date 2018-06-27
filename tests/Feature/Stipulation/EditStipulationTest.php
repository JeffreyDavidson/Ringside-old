<?php

namespace Tests\Feature\Stipulation;

use Tests\TestCase;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditStipulationTest extends TestCase
{
    use RefreshDatabase;

    private $stipulation;
    private $response;

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

    private function assertFormError($field, $expectedValue, $property)
    {
        $this->response->assertRedirect(route('stipulations.edit', $this->stipulation->id));
        $this->response->assertSessionHasErrors($field);
        tap($this->stipulation->fresh(), function ($stipulation) use ($expectedValue, $property) {
            $this->assertEquals($expectedValue, $property);
        });
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_stipulation_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('stipulations.edit', $this->stipulation->id));

        $response->assertSuccessful();
        $response->assertViewIs('stipulations.edit');
        $response->assertViewHas('stipulation');
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
    public function users_who_dont_have_permission_cannot_view_the_edit_stipulation_page()
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
    public function guests_cannot_view_the_edit_stipulation_page()
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
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('stipulations.edit', $this->stipulation->id))
                            ->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
                                'name' => '',
                            ]));

        $this->assertFormError('name', 'Old Name', $this->stipulation->name);
    }

    /** @test */
    public function stipulation_name_must_be_unique()
    {
        factory(Stipulation::class)->create(['name' => 'Stipulation Name']);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('stipulations.edit', $this->stipulation->id))
                            ->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
                                'name' => 'Stipulation Name',
                            ]));

        $this->assertFormError('name', 'Old Name', $this->stipulation->name);
    }

    /** @test */
    public function stipulation_slug_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('stipulations.edit', $this->stipulation->id))
                            ->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
                                'slug' => '',
                            ]));

        $this->assertFormError('slug', 'old-slug', $this->stipulation->slug);
    }

    /** @test */
    public function stipulation_slug_must_be_unique()
    {
        factory(Stipulation::class)->create(['slug' => 'stipulation-slug']);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('stipulations.edit', $this->stipulation->id))
                            ->patch(route('stipulations.update', $this->stipulation->id), $this->validParams([
                                'slug' => 'stipulation-slug',
                            ]));

        $this->assertFormError('slug', 'old-slug', $this->stipulation->slug);
    }
}
