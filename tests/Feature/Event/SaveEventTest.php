<?php

namespace Tests\Feature\Event;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Models\MatchType;
use Tests\IntegrationTestCase;

class SaveEventTest extends IntegrationTestCase
{
    private $venue;
    private $matchType;
    private $referee;
    private $wrestlerA;
    private $wrestlerB;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-event']);

        $this->venue = factory(Venue::class)->create();
        $this->matchType = factory(MatchType::class)->create(['number_of_sides' => 2, 'total_competitors' => 2]);
        $this->referee = factory(Referee::class)->create();
        $this->wrestlerA = factory(Wrestler::class)->create(['hired_at' => Carbon::yesterday()]);
        $this->wrestlerB = factory(Wrestler::class)->create(['hired_at' => Carbon::yesterday()]);
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
    public function users_who_have_permission_can_save_an_event()
    {
        $response = $this->actingAs($this->authorizedUser)->post(route('events.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('scheduled-events.index'));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_save_an_event()
    {
        $response = $this->actingAs($this->unauthorizedUser)->post(route('events.store'), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_save_an_event()
    {
        $response = $this->get(route('events.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function adding_a_valid_event_with_matches()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
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
        ]));

        tap(Event::first(), function ($event) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('scheduled-events.index'));

            $this->assertEquals('Event Name', $event->name);
            $this->assertEquals('event-slug', $event->slug);
            $this->assertEquals('2017-09-17', $event->date->toDateString());
            $this->assertEquals($this->venue->id, $event->venue_id);
            $this->assertCount(1, $event->matches);
            $this->assertEquals(1, $event->matches()->first()->match_type_id);
            $this->assertEquals('This is just a basic preview.', $event->matches()->first()->preview);
        });
    }

    /** @test */
    public function adding_a_valid_event_without_array_of_matches_that_doesnt_require_a_match()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'number_of_matches' => 1,
            'schedule_matches' => 0,
        ]));

        tap(Event::first(), function ($event) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('scheduled-events.index'));
        });
    }

    /** @test */
    public function event_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_name_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'name' => [],
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

        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
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
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_slug_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'slug' => [],
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

        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'slug' => 'event-slug',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Event::count());
    }

    /** @test */
    public function event_date_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'date' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_date_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'date' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_date_must_be_a_date()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
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
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'venue_id' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_venue_id_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'venue_id' => 'abc',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_venue_id_cannot_be_a_zero_value()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'venue_id' => 0,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function event_venue_id_must_exist_in_database()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'venue_id' => 99,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function number_of_matches_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'number_of_matches' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('number_of_matches');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function number_of_matches_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'number_of_matches' => 'not-an-integer',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('number_of_matches');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function number_of_matches_must_be_at_least_one()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'number_of_matches' => 0,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('number_of_matches');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function schedule_matches_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'schedule_matches' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('schedule_matches');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function schedule_matches_must_be_a_boolean_value()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'schedule_matches' => 'not-a-boolean',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('schedule_matches');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function matches_array_is_required_if_schedule_matches_is_true()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validWithoutMatchesParams([
            'schedule_matches' => 1,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function matches_must_be_a_valid_array()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => 'a-string-not-an-array',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function matches_array_must_contain_at_least_one_match_in_size()
    {
        $event = $this->validParams();
        data_set($event, 'matches', []);

        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $event);

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function match_type_is_required_for_each_match()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
                'matches' => [
                    [
                        'match_type_id' => '',
                    ],
                ],
            ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.match_type_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function match_type_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'match_type_id' => 'a-string-not-an-integer',
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.match_type_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function match_type_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'match_type_id' => 99,
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.match_type_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_stipulation_is_optional()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'stipulation_id' => '',
                ],
            ],
        ]));

        tap(Event::first()->matches()->first(), function ($match) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('scheduled-events.index'));
            $this->assertNull($match->stipulation_id);
        });
    }

    /** @test */
    public function each_match_stipulation_must_be_an_integer_if_provided()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'stipulation_id' => 'a-string-not-an-integer',
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.stipulation_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_stipulation_must_exist_in_the_database_if_provided()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'stipulation_id' => 99,
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.stipulation_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_titles_must_be_an_array_if_provided()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'titles' => 'a-string-not-an-array',
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.titles');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_title_must_be_distinct()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'titles' => [1, 2, 3, 1],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.titles.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_title_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'titles' => ['a-string-not-an-integer'],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.titles.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_title_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'titles' => [99],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.titles.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_referees_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'referees' => '',
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.referees');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_referees_must_be_an_array()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'referees' => 'a-string-not-an-array',
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.referees');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_referees_must_be_distinct()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'referees' => [1, 2, 3, 1],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.referees.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_referees_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'referees' => ['a-string-not-an-integer'],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.referees.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_referees_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'referees' => [99],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.referees.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_should_have_at_least_two_sides_of_wrestlers()
    {
        $event = $this->validParams();
        data_set($event, 'matches.0.wrestlers', [[$this->wrestlerA->id, $this->wrestlerB->id]]);

        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $event);

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.wrestlers');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_wrestlers_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'wrestlers' => '',
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.wrestlers');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_wrestlers_must_be_an_array()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'wrestlers' => 'a-string-not-an-array',
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.wrestlers');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_wrestlers_must_be_distinct()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'wrestlers' => [
                        [1, 2],
                        [3, 1],
                    ],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.wrestlers.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_wrestlers_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'wrestlers' => [
                        ['a-string-not-an-integer'],
                    ],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.wrestlers.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_wrestlers_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'wrestlers' => [
                        [99],
                    ],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.wrestlers.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_wrestlers_array_count_must_equal_number_of_wrestlers_count_according_to_its_match_type()
    {
        $matchType = factory(MatchType::class)->create(['total_competitors' => 5]);

        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'match_type_id' => $matchType->id,
                    'wrestlers' => [
                        0 => [1, 2],
                        1 => [3]
                    ]
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_wrestlers_must_be_qualified_to_be_in_the_match()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => Carbon::tomorrow()]);

        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'wrestlers' => [
                        [$wrestler->id],
                    ],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.wrestlers.*.*');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_preview_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'preview' => '',
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.preview');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function each_match_preview_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('events.create'))->post(route('events.store'), $this->validParams([
            'matches' => [
                [
                    'preview' => [],
                ],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('matches.*.preview');
        $this->assertEquals(0, Event::count());
    }
}
