<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Event;
use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewEventTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'show-event']);
        $this->event = factory(Event::class)->create([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            //'venue_id' => 1
        ]);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_event()
    {
        $response = $this->actingAs($this->user)->get(route('events.show', $this->event->id));

        $response->assertSuccessful();
        $response->assertViewIs('events.show');
        $response->assertViewHas('event');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_event()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('events.show', $this->event->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_event()
    {
        $response = $this->get(route('events.show', $this->event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
