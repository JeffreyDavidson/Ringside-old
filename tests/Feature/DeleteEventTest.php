<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DeleteEventTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    private $event;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'delete-event']);
        $this->event = factory(Event::class)->create();

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    public function users_who_have_permission_can_delete_a_event()
    {
        $response = $this->actingAs($this->user)->from(route('events.index'))->delete(route('events.destroy', $this->event->id));

        $response->assertStatus(302);
        $this->assertSoftDeleted('events', $this->event->toArray());
        $response->assertRedirect(route('events.index'));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_event()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->from(route('events.index'))->delete(route('events.destroy', $this->event->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_delete_a_event()
    {
        $response = $this->from(route('events.index'))->delete(route('events.destroy', $this->event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
