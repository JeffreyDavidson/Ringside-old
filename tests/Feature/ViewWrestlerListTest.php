<?php

namespace Tests\Feature;

use App\Models\Wrestler;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewWrestlerListTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'view-wrestlers']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    function users_who_have_permission_can_view_the_list_of_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $wrestlerC = factory(Wrestler::class)->create();

        $response = $this->actingAs($this->user)
                        ->get(route('wrestlers.index'));

        $response->assertStatus(200);
        $response->data('wrestlers')->assertEquals([
            $wrestlerA,
            $wrestlerB,
            $wrestlerC,
        ]);
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_list_of_wrestlers()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)
                        ->get(route('wrestlers.index'));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_wrestler_list()
    {
        $response = $this->get(route('wrestlers.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
