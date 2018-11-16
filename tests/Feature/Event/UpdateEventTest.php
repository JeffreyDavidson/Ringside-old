<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\Venue;
use Tests\IntegrationTestCase;

class UpdateEventTest extends IntegrationTestCase
{
    private $event;
    private $venue;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-event']);

        $this->venue = factory(Venue::class)->create(['id' => 1]);
        $this->event = factory(Event::class)->create($this->oldAttributes(['venue_id' => $this->venue->id]));
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Name',
            'slug' => 'old-slug',
            'date' => '2017-09-27',
            'venue_id' => 1,
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-27',
            'venue_id' => 1,
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_edit_an_event()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), [
            'name' => 'New Name',
            'slug' => 'new-slug',
            'date' => '2017-09-27',
            'venue_id' => $this->venue->id,
        ]);

        tap($this->event->fresh(), function ($event) {
            $this->assertEquals('New Name', $event->name);
            $this->assertEquals('new-slug', $event->slug);
            $this->assertEquals('2017-09-27', $event->date->toDateString());
            $this->assertEquals(1, $event->venue_id);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_edit_an_event()
    {
        $response = $this->actingAs($this->unauthorizedUser)->patch(route('events.update', $this->event->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_edit_an_event()
    {
        $response = $this->patch(route('events.update', $this->event->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function event_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'name' => '',
        ]));

        $this->assertFormError('name', 'Old Name', $this->event->name);
    }

    /** @test */
    public function event_name_must_be_unique()
    {
        factory(Event::class)->create(['name' => 'Event Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'name' => 'Event Name',
        ]));

        $this->assertFormError('name', 'Old Name', $this->event->name);
    }

    /** @test */
    public function event_slug_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'slug' => '',
        ]));

        $this->assertFormError('slug', 'old-slug', $this->event->slug);
    }

    /** @test */
    public function event_slug_must_be_unique()
    {
        factory(Event::class)->create(['slug' => 'event-slug']);

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'slug' => 'event-slug',
        ]));

        $this->assertFormError('slug', 'old-slug', $this->event->slug);
    }

    /** @test */
    public function event_date_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'date' => '',
        ]));

        $this->assertFormError('date', '2017-09-27', $this->event->date->toDateString());
    }

    /** @test */
    public function event_date_must_be_a_date()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'date' => 'not-a-date',
        ]));

        $this->assertFormError('date', '2017-09-27', $this->event->date->toDateString());
    }

    /** @test */
    public function event_venue_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'venue_id' => '',
        ]));

        $this->assertFormError('venue_id', 1, $this->event->venue_id);
    }

    /** @test */
    public function event_venue_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'venue_id' => 99,
        ]));

        $this->assertFormError('venue_id', 1, $this->event->venue_id);
    }

    /** @test */
    public function an_events_venue_cannot_be_soft_deleted()
    {
        $venue = factory(Venue::class)->create();
        $venue->delete();

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $this->event->id))->patch(route('events.update', $this->event->id), $this->validParams([
            'venue_id' => $venue->id,
        ]));

        $this->assertFormError('venue_id', 1, $this->event->venue_id);
    }
}
