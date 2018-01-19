<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Venue;
use App\Models\Wrestler;
use EventFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatchFactory;
use Tests\TestCase;

class ViewVenueTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'show-venue']);
        $this->venue = factory(Venue::class)->create([
            'name'     => 'Venue Name',
            'address'  => '123 Main Street',
            'city'     => 'Laraville',
            'state'    => 'FL',
            'postcode' => '90210',
        ]);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_venue()
    {
        $response = $this->actingAs($this->user)->get(route('venues.show', $this->venue->id));

        $response->assertSuccessful();
        $response->assertViewIs('venues.show');
        $response->assertViewHas('venue');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_venue()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('venues.show', $this->venue->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_venue()
    {
        $response = $this->get(route('venues.show', $this->venue->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_venues_past_events_can_be_viewed_on_venue_page()
    {
        $this->withoutExceptionHandling();
        $event = EventFactory::create(['name' => 'Event Name', 'venue_id' => $this->venue->id]);
        MatchFactory::create(['event_id' => $event->id], factory(Wrestler::class, 2)->create());

        $response = $this->actingAs($this->user)->get(route('venues.show', $this->venue->id));

        $response->assertSuccessful();
        $response->assertViewIs('venues.show');
        $response->assertViewHas('venue');
        $response->assertSee('Event Name');
    }
}
