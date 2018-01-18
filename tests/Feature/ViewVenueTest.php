<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Match;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Venue;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
            'name'     => 'Stipulation Name',
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
    public function a_venues_events_can_be_viewed_on_page()
    {
        $this->disableExceptionHandling();
        $event = factory(Event::class)->create(['venue_id' => $this->venue->id]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler A']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler B']);
        $match->addWrestlers([$wrestlerA, $wrestlerB]);

        $response = $this->actingAs($this->user)->get(route('venues.show', $this->venue->id));

        $response->assertSuccessful();
        $response->assertViewIs('venues.show');
        $response->assertViewHas('venue');
    }
}
