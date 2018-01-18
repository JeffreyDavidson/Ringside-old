<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class EditEventTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    private $event;

    private $venue;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'edit-event']);
        $this->event = factory(Event::class)->create($this->oldAttributes());
        $this->venue = factory(Venue::class)->create();

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name'     => 'Old Name',
            'slug'     => 'old-slug',
            'date'     => '2017-09-27',
            'venue_id' => 1,
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name'     => 'Event Name',
            'slug'     => 'event-slug',
            'date'     => '2017-09-27',
            'venue_id' => 1,
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_event_form()
    {
        $response = $this->actingAs($this->user)->get(route('events.edit', $this->event->id));

        $response->assertSuccessful();
        $this->assertTrue($response->data('event')->is($this->event));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_event_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('events.edit', $this->event->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_event_form()
    {
        $response = $this->get(route('events.edit', $this->event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function name_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('name');
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals('Old Name', $event->name);
        });
    }

    /** @test */
    public function slug_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'slug' => '',
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('slug');
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals('old-slug', $event->slug);
        });
    }

    /** @test */
    public function name_must_be_unique()
    {
        factory(Event::class)->create($this->validParams());

        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'name' => 'Event Name',
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Event::where('name', 'Old Name')->count());
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals('Old Name', $event->name);
        });
    }

    /** @test */
    public function slug_must_be_unique()
    {
        factory(Event::class)->create(['slug' => 'event-slug']);

        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'slug' => 'event-slug',
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Event::where('slug', 'event-slug')->count());
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals('old-slug', $event->slug);
        });
    }

    /** @test */
    public function date_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'date' => '',
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('date');
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals(Carbon::parse('2017-09-27'), $event->date);
        });
    }

    /** @test */
    public function date_must_be_a_date()
    {
        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'date' => 'not-a-date',
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('date');
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals(Carbon::parse('2017-09-27'), $event->date);
        });
    }

    /** @test */
    public function an_events_venue_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'venue_id' => '',
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('venue_id');
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals(1, $event->venue_id);
        });
    }

    /** @test */
    public function an_events_venue_must_be_an_integer()
    {
        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'venue_id' => 'abc',
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('venue_id');
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals(1, $event->venue_id);
        });
    }

    /** @test */
    public function an_events_venue_must_be_a_valid_selection()
    {
        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'venue_id' => 0,
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('venue_id');
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals(1, $event->venue_id);
        });
    }

    /** @test */
    public function an_events_venue_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'venue_id' => 99,
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('venue_id');
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals(1, $event->venue_id);
        });
    }

    /** @test */
    public function an_events_venue_cannot_be_soft_deleted()
    {
        $venue = factory(Venue::class)->create();
        $venue->delete();

        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'venue_id' => $venue->id,
        ]));

        $response->assertRedirect(route('events.edit', $this->event->id));
        $response->assertSessionHasErrors('venue_id');
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals(1, $event->venue_id);
        });
    }

    /** @test */
    public function editing_a_valid_event()
    {
        $response = $this->actingAs($this->user)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), [
            'name'     => 'New Name',
            'slug'     => 'new-slug',
            'date'     => '2017-09-27',
            'venue_id' => 1,
        ]);

        $response->assertRedirect(route('events.index'));
        tap($this->event->fresh(), function ($event) {
            $this->assertEquals('New Name', $event->name);
            $this->assertEquals('new-slug', $event->slug);
            $this->assertEquals(Carbon::parse('2017-09-27'), $event->date);
            $this->assertEquals(1, $event->venue_id);
        });
    }
}
