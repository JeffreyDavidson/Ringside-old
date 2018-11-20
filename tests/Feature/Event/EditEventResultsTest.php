<?php

namespace Tests\Feature\Event;

use Facades\EventFactory;
use Tests\IntegrationTestCase;

class EditEventResultsTest extends IntegrationTestCase
{
    private $response;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['edit-event']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_event_results_page()
    {
        $event = EventFactory::create();

        $response = $this->actingAs($this->authorizedUser)->get(route('event-results.edit', ['event' => $event->id]));

        $response->assertSuccessful();
        $response->assertViewIs('events.results');
        $response->assertViewHas('event', function ($viewEvent) use ($event) {
            return $viewEvent->id === $event->id;
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_event_results_page()
    {
        $event = EventFactory::create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('event-results.edit', $event->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_event_results_page()
    {
        $event = EventFactory::create();

        $response = $this->get(route('event-results.edit', $event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function users_who_have_permission_can_edit_an_event()
    {
        $response = $this->actingAs($this->authorizedUser)
            ->from(route('events.edit', $this->event->id))
            ->patch(route('events.update', $this->event->id), [
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
        $response = $this->actingAs($this->unauthorizedUser)
            ->patch(route('events.update', $this->event->id), $this->validParams());

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
        $this->response = $this->actingAs($this->authorizedUser)
            ->from(route('events.edit', $this->event->id))
            ->patch(route('events.update', $this->event->id), $this->validParams([
                'name' => '',
            ]));

        $this->assertFormError('name', 'Old Name', $this->event->name);
    }

    /** @test */
    public function event_name_must_be_unique()
    {
        factory(Event::class)->create(['name' => 'Event Name']);

        $this->response = $this->actingAs($this->authorizedUser)
            ->from(route('events.edit', $this->event->id))
            ->patch(route('events.update', $this->event->id), $this->validParams([
                'name' => 'Event Name',
            ]));

        $this->assertFormError('name', 'Old Name', $this->event->name);
    }

    /** @test */
    public function event_slug_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
            ->from(route('events.edit', $this->event->id))
            ->patch(route('events.update', $this->event->id), $this->validParams([
                'slug' => '',
            ]));

        $this->assertFormError('slug', 'old-slug', $this->event->slug);
    }

    /** @test */
    public function event_slug_must_be_unique()
    {
        factory(Event::class)->create(['slug' => 'event-slug']);

        $this->response = $this->actingAs($this->authorizedUser)
            ->from(route('events.edit', $this->event->id))
            ->patch(route('events.update', $this->event->id), $this->validParams([
                'slug' => 'event-slug',
            ]));

        $this->assertFormError('slug', 'old-slug', $this->event->slug);
    }

    /** @test */
    public function event_date_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
            ->from(route('events.edit', $this->event->id))
            ->patch(route('events.update', $this->event->id), $this->validParams([
                'date' => '',
            ]));

        $this->assertFormError('date', '2017-09-27', $this->event->date->toDateString());
    }

    /** @test */
    public function event_date_must_be_a_date()
    {
        $this->response = $this->actingAs($this->authorizedUser)
            ->from(route('events.edit', $this->event->id))
            ->patch(route('events.update', $this->event->id), $this->validParams([
                'date' => 'not-a-date',
            ]));

        $this->assertFormError('date', '2017-09-27', $this->event->date->toDateString());
    }

    /** @test */
    public function event_venue_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
            ->from(route('events.edit', $this->event->id))
            ->patch(route('events.update', $this->event->id), $this->validParams([
                'venue_id' => '',
            ]));

        $this->assertFormError('venue_id', 1, $this->event->venue_id);
    }

    /** @test */
    public function event_venue_must_exist_in_the_database()
    {
        $this->response = $this->actingAs($this->authorizedUser)
            ->from(route('events.edit', $this->event->id))
            ->patch(route('events.update', $this->event->id), $this->validParams([
                'venue_id' => 99,
            ]));

        $this->assertFormError('venue_id', 1, $this->event->venue_id);
    }

    /** @test */
    public function an_events_venue_cannot_be_soft_deleted()
    {
        $venue = factory(Venue::class)->create();
        $venue->delete();

        $this->response = $this->actingAs($this->authorizedUser)
            ->from(route('events.edit', $this->event->id))
            ->patch(route('events.update', $this->event->id), $this->validParams([
                'venue_id' => $venue->id,
            ]));

        $this->assertFormError('venue_id', 1, $this->event->venue_id);
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

    private function assertFormError($field, $expectedValue, $property)
    {
        $this->response->assertRedirect(route('events.edit', $this->event->id));
        $this->response->assertSessionHasErrors($field);
        tap($this->event->fresh(), function ($event) use ($expectedValue, $property) {
            $this->assertEquals($expectedValue, $property);
        });
    }
}
