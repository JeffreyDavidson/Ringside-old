<?php

namespace Tests\Feature;

use App\Models\Wrestler;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteWrestlerTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'delete-wrestler']);
        $this->wrestler = factory(Wrestler::class)->create();

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    function users_who_have_permission_can_delete_a_wrestler()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.index'))->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(302);
    }

    /** @test */
    function users_who_dont_have_permission_cannot_delete_a_wrestler()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->from(route('wrestlers.index'))->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_delete_a_wrestler()
    {
        $response = $this->from(route('wrestlers.index'))->delete(route('wrestlers.destroy', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
