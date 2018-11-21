<?php

namespace Tests\Feature\Event;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Models\MatchType;
use Facades\EventFactory;
use Tests\IntegrationTestCase;

class UpdateEventTest extends IntegrationTestCase
{
    private $venue;
    private $matchType;
    private $referee;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-event']);

        $this->matchType = factory(MatchType::class)->create(['number_of_sides' => 2, 'total_competitors' => 2]);
        $this->venue = factory(Venue::class)->create();
        $this->referee = factory(Referee::class)->create();
        $this->wrestlerA = factory(Wrestler::class)->create();
        $this->wrestlerB = factory(Wrestler::class)->create();
    }

    private function validParams($overrides = [])
    {
        return array_replace_recursive([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            'venue_id' => $this->venue->id,
            'number_of_matches' => 1,
            'schedule_matches' => 1,
            'matches' => [
                0 => [
                    'match_type_id' => $this->matchType->id,
                    'referees' => [$this->referee->id],
                    'preview' => 'This is just a basic preview.',
                    'wrestlers' => [
                        0 => [$this->wrestlerA->id],
                        1 => [$this->wrestlerB->id]
                    ],
                ],
            ],
        ], $overrides);
    }

    public function oldAttributes($overrides = []) 
    {
        return array_merge([
            'name' => 'Old Name',
            'slug' => 'old-slug',
        ], $overrides); 
    }

    private function validWithoutMatchesParams($overrides = [])
    {
        return array_replace_recursive([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            'venue_id' => $this->venue->id,
            'number_of_matches' => 1,
            'schedule_matches' => 1,
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_update_a_scheduled_event()
    {
        $event = EventFactory::onDate(Carbon::now()->addWeeks(3))->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams([
            'name' => 'New Name',
            'slug' => 'new-slug',
            'date' => '2017-09-27',
            'venue_id' => $this->venue->id,
        ]));

        tap($event->fresh(), function ($event) {
            $this->assertEquals('New Name', $event->name);
            $this->assertEquals('new-slug', $event->slug);
            $this->assertEquals('2017-09-27', $event->date->toDateString());
            $this->assertEquals(1, $event->venue_id);
        });
    }

    /** @test */
    public function a_past_event_event_cannot_be_updated()
    {
        $event = EventFactory::onDate(Carbon::yesterday())->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_edit_an_event()
    {
        $event = EventFactory::create();

        $response = $this->actingAs($this->unauthorizedUser)->patch(route('events.update', $event->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_edit_an_event()
    {
        $event = EventFactory::create();

        $response = $this->patch(route('events.update', $event->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function event_name_is_required()
    {
        $event = factory(Event::class)->create($this->oldAttributes(['name' => 'Old Name']));

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('events.edit', $event->id));
        $response->assertSessionHasErrors('name');
        tap($event->fresh(), function ($event) {
            $this->assertEquals('Old Name', $event->name);
        });
    }

    /** @test */
    public function event_name_must_be_unique()
    {
        $event = factory(Event::class)->create($this->oldAttributes(['name' => 'Old Name']));
        factory(Event::class)->create(['name' => 'Other Event Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams([
            'name' => 'Other Event Name',
        ]));

        $response->assertRedirect(route('events.edit', $event->id));
        $response->assertSessionHasErrors('name');
        tap($event->fresh(), function ($event) {
            $this->assertEquals('Old Name', $event->name);
        });
    }

    /** @test */
    public function event_slug_is_required()
    {
        $event = factory(Event::class)->create($this->oldAttributes(['slug' => 'old-slug']));

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams([
            'slug' => '',
        ]));

        $response->assertRedirect(route('events.edit', $event->id));
        $response->assertSessionHasErrors('slug');
        tap($event->fresh(), function ($event) {
            $this->assertEquals('old-slug', $event->slug);
        });
    }

    /** @test */
    public function event_slug_must_be_unique()
    {
        $event = factory(Event::class)->create($this->oldAttributes(['slug' => 'old-slug']));
        factory(Event::class)->create(['slug' => 'other-event-slug']);

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams([
            'slug' => 'other-event-slug',
        ]));

        $response->assertRedirect(route('events.edit', $event->id));
        $response->assertSessionHasErrors('slug');
        tap($event->fresh(), function ($event) {
            $this->assertEquals('old-slug', $event->slug);
        });
    }

    /** @test */
    public function event_date_is_required()
    {
        $event = factory(Event::class)->create($this->oldAttributes(['date' => Carbon::tomorrow()]));

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams([
            'date' => '',
        ]));

        $response->assertRedirect(route('events.edit', $event->id));
        $response->assertSessionHasErrors('date');
        tap($event->fresh(), function ($event) {
            $this->assertEquals(Carbon::tomorrow()->toDateString(), $event->date->toDateString());
        });
    }

    /** @test */
    public function event_date_must_be_a_date()
    {
        $event = factory(Event::class)->create($this->oldAttributes(['date' => Carbon::tomorrow()]));

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams([
            'date' => 'not-a-date',
        ]));

        $response->assertRedirect(route('events.edit', $event->id));
        $response->assertSessionHasErrors('date');
        tap($event->fresh(), function ($event) {
            $this->assertEquals(Carbon::tomorrow()->toDateString(), $event->date->toDateString());
        });
    }

    /** @test */
    public function event_venue_is_required()
    {
        $event = factory(Event::class)->create($this->oldAttributes(['venue_id' => $this->venue->id]));

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams([
            'venue_id' => '',
        ]));

        $response->assertRedirect(route('events.edit', $event->id));
        $response->assertSessionHasErrors('venue_id');
        tap($event->fresh(), function ($event) {
            $this->assertTrue($event->venue->is($this->venue));
        });
    }

    /** @test */
    public function event_venue_must_exist_in_the_database()
    {
        $event = factory(Event::class)->create($this->oldAttributes(['venue_id' => $this->venue->id]));

        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams([
            'venue_id' => 99,
        ]));

        $response->assertRedirect(route('events.edit', $event->id));
        $response->assertSessionHasErrors('venue_id');
        tap($event->fresh(), function ($event) {
            $this->assertTrue($event->venue->is($this->venue));
        });
    }

    /** @test */
    public function an_events_venue_cannot_be_soft_deleted()
    {
        $venueB = factory(Venue::class)->create(['name' => 'soft deleted venue']);
        $venueB->delete();
        $event = factory(Event::class)->create($this->oldAttributes(['venue_id' => $this->venue->id]));


        $response = $this->actingAs($this->authorizedUser)->from(route('events.edit', $event->id))->patch(route('events.update', $event->id), $this->validParams([
            'venue_id' => $venueB->id,
        ]));

        $response->assertRedirect(route('events.edit', $event->id));
        $response->assertSessionHasErrors('venue_id');
        tap($event->fresh(), function ($event) {
            $this->assertTrue($event->venue->is($this->venue));
        });
    }
}
