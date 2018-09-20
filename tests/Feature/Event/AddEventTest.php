<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddEventTest extends TestCase
{
    use RefreshDatabase;

    private $venue;
    private $response;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-event']);

        $this->venue = factory(Venue::class)->create();
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_event_page()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->get(route('events.create'));

        $this->response->assertSuccessful();
        $this->response->assertViewIs('events.create');
        $this->response->assertViewHas('event');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_event_page()
    {
        $this->response = $this->actingAs($this->unauthorizedUser)
                            ->get(route('events.create'));

        $this->response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_event_page()
    {
        $response = $this->get(route('events.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function event_name_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('events.create'))
                            ->post(route('events.store'), $this->validParams([
                                'name' => '',
                            ]));

        $this->assertFormError('name');
    }

    /** @test */
    public function event_name_must_be_unique()
    {
        factory(Event::class)->create(['name' => 'Event Name']);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('events.create'))
                            ->post(route('events.store'), $this->validParams([
                                'name' => 'Event Name',
                            ]));

        $this->assertFormError('name', 1);
    }

    /** @test */
    public function event_slug_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('events.create'))
                            ->post(route('events.store'), $this->validParams([
                                'slug' => '',
                            ]));

        $this->assertFormError('slug');
    }

    /** @test */
    public function event_slug_must_be_unique()
    {
        factory(Event::class)->create(['slug' => 'event-slug']);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('events.create'))
                            ->post(route('events.store'), $this->validParams([
                                'slug' => 'event-slug',
                            ]));

        $this->assertFormError('slug', 1);
    }

    /** @test */
    public function event_date_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('events.create'))
                            ->post(route('events.store'), $this->validParams([
                                'date' => '',
                            ]));

        $this->assertFormError('date');
    }

    /** @test */
    public function event_date_must_be_date()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('events.create'))
                            ->post(route('events.store'), $this->validParams([
                                'date' => 'not-a-date',
                            ]));

        $this->assertFormError('date');
    }

    /** @test */
    public function event_venue_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('events.create'))
                            ->post(route('events.store'), $this->validParams([
                                'venue_id' => '',
                            ]));

        $this->assertFormError('venue_id');
    }

    /** @test */
    public function event_venue_must_exist_in_database()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('events.create'))
                            ->post(route('events.store'), $this->validParams([
                                'venue_id' => 99,
                            ]));

        $this->assertFormError('venue_id');
    }

    /** @test */
    public function adding_a_valid_event()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('events.create'))
                            ->post(route('events.store'), $this->validParams([
                                'name' => 'Event Name',
                                'slug' => 'event-slug',
                                'date' => '2017-09-17',
                                'venue_id' => $this->venue->id,
                            ]));

        tap(Event::first(), function ($event) {
            $this->response->assertStatus(302);
            $this->response->assertRedirect(route('scheduled-events.index'));

            $this->assertEquals('Event Name', $event->name);
            $this->assertEquals('event-slug', $event->slug);
            $this->assertEquals('2017-09-17', $event->date->toDateString());
            $this->assertEquals($this->venue->id, $event->venue_id);
        });
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            'venue_id' => $this->venue->id,
        ], $overrides);
    }

    private function assertFormError($field, $expectedEventCount = 0)
    {
        $this->response->assertStatus(302);
        $this->response->assertRedirect(route('events.create'));
        $this->response->assertSessionHasErrors($field);
        $this->assertEquals($expectedEventCount, Event::count());
    }
}
