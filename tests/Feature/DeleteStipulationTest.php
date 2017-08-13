<?php

namespace Tests\Feature;

use App\Models\Stipulation;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteStipulationTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'delete-stipulation']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    function users_who_have_permission_can_delete_a_stipulation()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->actingAs($this->user)->delete(route('stipulations.destroy', $stipulation->id));

        $response->assertStatus(302);
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_add_stipulation_form()
    {
        $stipulation = factory(Stipulation::class)->create();
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->delete(route('stipulations.destroy', $stipulation->id));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_the_add_stipulation_form()
    {
        $stipulation = factory(Stipulation::class)->create();

        $response = $this->delete(route('stipulations.destroy', $stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
