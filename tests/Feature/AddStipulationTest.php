<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddStipulationTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'create-stipulation']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
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
        $response = $this->actingAs($this->user)->get(route('stipulations.create'));

        $response->assertSuccessful();
        $response->assertViewIs('stipulations.create');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_stipulation_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('stipulations.create'));

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
    public function name_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Stipulation::count());
    }

    /** @test */
    public function name_must_be_unique()
    {
        factory(Stipulation::class)->create(['name' => 'Stipulation Name']);

        $response = $this->actingAs($this->user)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'name' => 'Stipulation Name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Stipulation::where('name', 'Stipulation Name')->count());
    }

    /** @test */
    public function slug_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Stipulation::count());
    }

    /** @test */
    public function slug_must_be_unique()
    {
        factory(Stipulation::class)->create(['slug' => 'stipulation-slug']);

        $response = $this->actingAs($this->user)->from(route('stipulations.create'))->post(route('stipulations.index'), $this->validParams([
            'slug' => 'stipulation-slug',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('stipulations.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Stipulation::where('slug', 'stipulation-slug')->count());
    }

    /** @test */
    public function adding_a_valid_stipulation()
    {
        $response = $this->actingAs($this->user)->post(route('stipulations.index'), $this->validParams());

        tap(Stipulation::first(), function ($stipulation) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('stipulations.index'));

            $this->assertEquals('Stipulation Name', $stipulation->name);
            $this->assertEquals('stipulation-slug', $stipulation->slug);
        });
    }
}
