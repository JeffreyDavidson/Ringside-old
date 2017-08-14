<?php

namespace Tests\Feature;

use App\Models\Title;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteTitleTest extends TestCase
{
    use DatabaseMigrations;

    private $user;
    private $role;
    private $permission;
    private $title;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'delete-title']);
        $this->title = factory(Title::class)->create();

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    function users_who_have_permission_can_delete_a_title()
    {
        $response = $this->actingAs($this->user)
                        ->from(route('titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(302);
    }

    /** @test */
    function users_who_dont_have_permission_cannot_delete_a_title()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)
                        ->from(route('titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_delete_a_title()
    {
        $response = $this->from(route('titles.index'))
                        ->delete(route('titles.destroy', $this->title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
