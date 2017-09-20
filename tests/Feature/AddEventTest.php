<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Venue;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddEventTest extends TestCase
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
        $this->permission = factory(Permission::class)->create(['slug' => 'create-event']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            'venue_id' => 1,
            'matches.*.match_type_id' => 1,
            'matches.*.stipulations' => [1],
            'matches.*.titles' => [1, 2],
            'matches.*.referees' => [1],
            'matches.*.wrestlers' => [1, 2],
            'matches.*.preview' => 'This is a preview of the match.',
        ], $overrides);
    }

    /** @test */
    function users_who_have_permission_can_view_the_add_event_form()
    {
        $response = $this->actingAs($this->user)->get(route('events.create'));

        $response->assertSuccessful();
        $response->assertViewIs('events.create');
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_add_event_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('events.create'));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_the_add_event_form()
    {
        $response = $this->get(route('events.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    function name_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function name_must_be_unique()
    {
        factory(Event::class)->create($this->validParams());

        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'name' => 'Event Name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Event::where('name', 'Event Name')->count());
    }

    /** @test */
    function slug_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function slug_must_be_unique()
    {
        factory(Event::class)->create($this->validParams());

        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'slug' => 'event-slug',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Event::where('slug', 'event-slug')->count());
    }

    /** @test */
    function date_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'date' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function date_must_be_date()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'date' => 'not-a-date',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function venue_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'venue_id' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function venue_must_be_an_integer()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'venue_id' => 'abc',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function venue_must_be_a_valid_selection()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'venue_id' => 0,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function venue_must_exist_in_database()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'venue_id' => 1,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function a_match_type_is_required()
    {
        $this->disableExceptionHandling();
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'matches.*.match_type_id' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        //$response->assertSessionHasErrors('matches.*.match_type_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function adding_a_valid_event()
    {
        factory(Venue::class)->create();

        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            'venue_id' => '1',
        ]));

        tap(Event::first(), function ($event) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('events.index'));

            $this->assertEquals('Event Name', $event->name);
            $this->assertEquals('event-slug', $event->slug);
            $this->assertEquals(Carbon::parse('2017-09-17'), $event->date);
            $this->assertEquals(1, $event->venue_id);
        });
    }
}
