<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Event;
use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewEventListTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'view-events']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_list_of_events()
    {
        $eventA = factory(Event::class)->create();
        $eventB = factory(Event::class)->create();
        $eventC = factory(Event::class)->create();

        $response = $this->actingAs($this->user)->get(route('events.index'));

        $response->assertStatus(200);
        $response->data('events')->assertEquals([
            $eventA,
            $eventB,
            $eventC,
        ]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_list_of_events()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('events.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_event_list()
    {
        $response = $this->get(route('events.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
