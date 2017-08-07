<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Stipulation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditStipulationTest extends TestCase
{
    use DatabaseMigrations;

    private $permission;
    private $role;

    public function setUp()
    {
        parent::setUp();

        $this->permission = factory(Permission::class)->create(['name' => 'Edit Stipulation ', 'slug' => 'edit-stipulation']);
        $this->role = factory(Role::class)->create(['name' => 'Administrator', 'slug' => 'admin']);
        factory(Role::class)->create(['name' => 'Basic User', 'slug' => 'basic']);
        $this->role->givePermissionTo($this->permission);
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
            'name' => 'My Stipulation',
            'slug' => 'my-stip',
        ], $overrides);
    }

    /** @test */
    function users_who_have_permission_can_view_the_edit_stipulation_form()
    {
        $user = factory(User::class)->create();
        $stipulation = factory(Stipulation::class)->create($this->oldAttributes());

        $response = $this->actingAs($user)->get('venues/create');

        $response->assertStatus(200);
        $this->assertTrue($response->data('stipulation')->is($stipulation));
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_edit_stipulation_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $stipulation = factory(Stipulation::class)->create($this->oldAttributes());

        $response = $this->actingAs($userWithoutPermission)->get(route('stipulations.edit', $stipulation->id));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_the_edit_stipulation_form()
    {
        $stipulation = factory(Stipulation::class)->create($this->oldAttributes());

        $response = $this->get(route('stipulations.edit', $stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function name_is_required()
    {
        $user = factory(User::class)->create();
        $stipulation = factory(Stipulation::class)->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->from(route('stipulations.edit', $stipulation->id))
            ->patch(route('stipulations.update', $stipulation->id), $this->validParams(['name' => '']));

        $response->assertRedirect(route('stipulations.edit', $stipulation->id));
        $response->assertSessionHasErrors('name');
        tap($stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('Old Name', $stipulation->name);
        });
    }

    /** @test */
    function slug_is_required()
    {
        $user = factory(User::class)->create();
        $stipulation = factory(Stipulation::class)->create(['slug' => 'old-slug']);

        $response = $this->actingAs($user)
            ->from(route('stipulations.edit', $stipulation->id))
            ->patch(route('stipulations.update', $stipulation->id), $this->validParams(['slug' => '']));

        $response->assertRedirect(route('stipulations.edit', $stipulation->id));
        $response->assertSessionHasErrors('slug');
        tap($stipulation->fresh(), function ($stipulation) {
            $this->assertEquals('old-slug', $stipulation->slug);
        });
    }
}
