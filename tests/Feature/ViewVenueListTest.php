<?php

namespace Tests\Feature;

use App\Models\Venue;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewVenueListTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'view-venues']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    function users_who_have_permission_can_view_the_list_of_venues()
    {
        $this->disableExceptionHandling();

        $venueA = factory(Venue::class)->create();
        $venueB = factory(Venue::class)->create();
        $venueC = factory(Venue::class)->create();

        $response = $this->actingAs($this->user)->get(route('venues.index'));

        $response->assertStatus(200);
        $response->data('venues')->assertEquals([
            $venueA,
            $venueB,
            $venueC,
        ]);
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_list_of_venues()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('venues.index'));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_venue_list()
    {
        $response = $this->get(route('venues.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}