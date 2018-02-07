<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddEventTest extends TestCase
{
    use DatabaseMigrations;

    private $venue;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('create-event');

        $this->venue = factory(Venue::class)->create();
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            'venue_id' => 1
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_event_form()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('events.create'));

        $response->assertSuccessful();
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_event_form()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('events.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_event_form()
    {
        $response = $this->get(route('events.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function event_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_name_must_be_unique()
    {
        factory(Event::class)->create(['name' => 'Event Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'name' => 'Event Name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Event::count());
    }

    /** @test */
    public function event_slug_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_slug_must_be_unique()
    {
        factory(Event::class)->create(['slug' => 'event-slug']);

        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'slug' => 'event-slug',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Event::where('slug', 'event-slug')->count());
    }

    /** @test */
    public function event_date_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'date' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Event::count());
    }

    ///** @test */
    public function event_date_must_be_date()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'date' => 'not-a-date',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_venue_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'venue_id' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_venue_must_exist_in_database()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'venue_id' => 99,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function adding_a_valid_event()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            'venue_id' => 1
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
