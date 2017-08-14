<?php

namespace Tests\Feature;

use App\Models\Stipulation;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewStipulationListTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'view-stipulations']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    function users_who_have_permission_can_view_the_list_of_stipulations()
    {
        $stipulationA = factory(Stipulation::class)->create();
        $stipulationB = factory(Stipulation::class)->create();
        $stipulationC = factory(Stipulation::class)->create();

        $response = $this->actingAs($this->user)->get(route('stipulations.index'));

        $response->assertStatus(200);
        $response->data('stipulations')->assertEquals([
            $stipulationA,
            $stipulationB,
            $stipulationC,
        ]);
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_list_of_stipulations()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)
                        ->get(route('stipulations.index'));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_stipulation_list()
    {
        $response = $this->get(route('stipulations.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}