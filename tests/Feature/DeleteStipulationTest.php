<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Stipulation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

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
        $this->stipulation = factory(Stipulation::class)->create();

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    public function users_who_have_permission_can_soft_delete_a_stipulation()
    {
        $response = $this->actingAs($this->user)->from(route('stipulations.index'))->delete(route('stipulations.destroy', $this->stipulation->id));

        $response->assertStatus(302);
        $this->assertSoftDeleted('stipulations', $this->stipulation->toArray());
        $response->assertRedirect(route('stipulations.index'));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_stipulation()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->from(route('stipulations.index'))->delete(route('stipulations.destroy', $this->stipulation->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_delete_a_stipulation()
    {
        $response = $this->from(route('stipulations.index'))->delete(route('stipulations.destroy', $this->stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
