<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Venue;
use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteVenueTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    private $venue;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'delete-venue']);
        $this->venue = factory(Venue::class)->create();

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    public function users_who_have_permission_can_soft_delete_a_venue()
    {
        $response = $this->actingAs($this->user)->from(route('venues.index'))->delete(route('venues.destroy', $this->venue->id));

        $response->assertStatus(302);
        $this->assertSoftDeleted('venues', $this->venue->toArray());
        $response->assertRedirect(route('venues.index'));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_venue()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->from(route('venues.index'))->delete(route('venues.destroy', $this->venue->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_delete_a_venue()
    {
        $response = $this->from(route('venues.index'))->delete(route('venues.destroy', $this->venue->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
