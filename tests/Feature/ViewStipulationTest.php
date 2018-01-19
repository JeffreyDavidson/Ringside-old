<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Models\Stipulation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewStipulationTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'show-stipulation']);
        $this->stipulation = factory(Stipulation::class)->create([
            'name' => 'Stipulation Name',
            'slug' => 'stipulation-slug',
        ]);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_stipulation()
    {
        $response = $this->actingAs($this->user)->get(route('stipulations.show', $this->stipulation->id));

        $response->assertSuccessful();
        $response->assertViewIs('stipulations.show');
        $response->assertViewHas('stipulation');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_stipulation()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('stipulations.show', $this->stipulation->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_stipulation()
    {
        $response = $this->get(route('stipulations.show', $this->stipulation->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
