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
    private $stipulation;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'delete-stipulation']);
        $this->stipulation =factory(Stipulation::class)->create();

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    function users_who_have_permission_can_delete_a_stipulation()
    {
        $response = $this->actingAs($this->user)
                        ->from(route('stipulations.index'))
                        ->delete(route('stipulations.destroy', $this->stipulation->id));

        $response->assertStatus(302);
    }

    /** @test */
    function users_who_dont_have_permission_cannot_delete_a_stipulation()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)
                        ->from(route('stipulations.index'))
                        ->delete(route('stipulations.destroy', $this->stipulation->id));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_delete_a_stipulation()
    {
        $response = $this->from(route('stipulations.index'))
                        ->delete(route('stipulations.destroy', $this->stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
