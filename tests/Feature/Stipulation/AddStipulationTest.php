<?php

namespace Tests\Feature\Stipulation;

use Tests\TestCase;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddStipulationTest extends TestCase
{
    use RefreshDatabase;

    private $response;

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

    private function assertFormError($field, $expectedEventCount = 0)
    {
        $this->response->assertStatus(302);
        $this->response->assertRedirect(route('stipulations.create'));
        $this->response->assertSessionHasErrors($field);
        $this->assertEquals($expectedEventCount, Stipulation::count());
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_stipulation_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('stipulations.create'));

        $response->assertSuccessful();
        $response->assertViewIs('stipulations.create');
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
    public function users_who_dont_have_permission_cannot_view_the_add_stipulation_page()
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
    public function guests_cannot_view_the_add_stipulation_page()
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
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.create'))
                        ->post(route('stipulations.index'), $this->validParams([
                            'name' => '',
                        ]));

        $this->assertFormError('name');
    }

    /** @test */
    public function stipulation_name_must_be_unique()
    {
        factory(Stipulation::class)->create(['name' => 'Stipulation Name']);

        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.create'))
                        ->post(route('stipulations.index'), $this->validParams([
                            'name' => 'Stipulation Name',
                        ]));

        $this->assertFormError('name', 1);
    }

    /** @test */
    public function stipulation_slug_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.create'))
                        ->post(route('stipulations.index'), $this->validParams([
                            'slug' => '',
                        ]));

        $this->assertFormError('slug');
    }

    /** @test */
    public function stipulation_slug_must_be_unique()
    {
        factory(Stipulation::class)->create(['slug' => 'stipulation-slug']);

        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('stipulations.create'))
                        ->post(route('stipulations.index'), $this->validParams([
                            'slug' => 'stipulation-slug',
                        ]));

        $this->assertFormError('slug', 1);
    }
}
