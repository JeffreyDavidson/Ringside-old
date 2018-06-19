<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Match;
use App\Models\MatchType;
use App\Models\Wrestler;
use App\Models\Referee;
use App\Models\Title;
use App\Models\Stipulation;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AddMatchesTest extends TestCase
{
    use RefreshDatabase;

    private $match;
    private $event;
    private $matchtype;
    private $title;
    private $stipulation;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-match', 'store-match']);

        $this->event = factory(Event::class)->create(['date' => Carbon::now()]);
        $this->matchtype = factory(MatchType::class)->create(['number_of_sides' => 2, 'total_competitors' => 2]);
        $this->referee = factory(Referee::class)->create();
        $this->wrestlerA = factory(Wrestler::class)->create(['hired_at' => Carbon::yesterday()]);
        $this->wrestlerB = factory(Wrestler::class)->create(['hired_at' => Carbon::yesterday()]);
        $this->title = factory(Title::class)->create(['introduced_at' => Carbon::yesterday()]);
        $this->stipulation = factory(Stipulation::class)->create();
    }

    private function validParams($overrides = [])
    {
        return array_replace_recursive([
            'matches' => [
                0 => [
                    'match_number' => 1,
                    'match_type_id' => $this->matchtype->id,
                    'stipulations_id' => $this->stipulation->id,
                    'titles' => [$this->title->id],
                    'referees' => [$this->referee->id],
                    'wrestlers' => [
                        0 => [$this->wrestlerA->id],
                        1 => [$this->wrestlerB->id],
                    ],
                    'preview' => 'Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper.',
                ]
            ]
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_match_form()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('matches.create', ['event' => 1]));

        $response->assertSuccessful();
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_event_form()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('matches.create', ['event' => 1]));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_event_form()
    {
        $response = $this->get(route('matches.create', ['event' => 1]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function returns_404_on_invalid_event_id()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('matches.create', ['event' => NULL]));

        $response->assertStatus(404);
    }

    /** @test */
    public function users_who_have_permission_can_add_valid_matches_to_an_event()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), [
                            'matches' => [
                                0 => [
                                    'match_number' => 1,
                                    'match_type_id' => $this->matchtype->id,
                                    'stipulation_id' => $this->stipulation->id,
                                    'titles' => [$this->title->id],
                                    'referees' => [$this->referee->id],
                                    'wrestlers' => [
                                        0 => [$this->wrestlerA->id],
                                        1 => [$this->wrestlerB->id],
                                    ],
                                    'preview' => 'Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper.',
                                ]
                            ]
                        ]);

        tap(Match::first(), function ($match) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('events.show', $this->event->id));

            $this->assertTrue($match->event->is($this->event));
            $this->assertEquals(1, $match->match_number);
            $this->assertEquals($this->matchtype->name, $match->type->name);
            $this->assertEquals($this->stipulation->name, $match->stipulation->name);
            $this->assertEquals(1, $match->titles->count());
            $this->assertEquals($this->referee->name, $match->referees->first()->name);
            $this->assertEquals($this->wrestlerA->name, $match->wrestlers->first()->name);
            $this->assertEquals($this->wrestlerB->name, $match->wrestlers->last()->name);
            $this->assertEquals('Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper.', $match->preview);
        });
    }

    /** @test */
    public function matches_is_valid_array()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => 'a-string-not-an-array'
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function match_number_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'match_number' => ''
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.match_number');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function match_number_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'match_number' => 'a-string-not-an-integer'
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.match_number');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function match_number_must_be_at_least_one()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'match_number' => 0
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.match_number');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function match_type_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'match_type_id' => ''
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.match_type_id');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function match_type_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'match_type_id' => 'a-string-not-an-integer'
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.match_type_id');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function match_type_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'match_type_id' => 99
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.match_type_id');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function stipulation_is_optional()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'stipulation_id' => ''
                                ]
                            ]
                        ]));

        tap(Event::first()->matches->first(), function ($match) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('events.show', $this->event->id));
            $this->assertNull($match->stipulation);
        });
    }

    /** @test */
    public function stipulation_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'stipulation_id' => ['a-string-not-an-integer']
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.stipulation_id');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function stipulation_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'stipulation_id' => [99]
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.stipulation_id');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function titles_must_be_an_array()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'titles' => 'a-string-not-an-array'
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.titles');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function title_must_be_distinct()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'titles' => [1,2,3,1]
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.titles.*');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function title_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'titles' => ['a-string-not-an-integer']
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.titles.*');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function title_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'titles' => [99]
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.titles.*');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function referees_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'referees' => ''
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.referees');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function referees_must_be_an_array()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'referees' => 'a-string-not-an-array'
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.referees');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function referee_must_be_distinct()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'referees' => [1,2,3,1]
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.referees.*');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function referee_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'referees' => ['a-string-not-an-integer']
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.referees.*');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function referee_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'referees' => [99]
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.referees.*');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function wrestlers_are_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'wrestlers' => ''
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.wrestlers');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function wrestlers_must_be_an_array()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'wrestlers' => 'a-string-not-an-array'
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.wrestlers');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function wrestler_must_be_distinct()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'wrestlers' => [
                                        0 => [1,2],
                                        1 => [3,1]
                                    ]
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.wrestlers');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function wrestler_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'wrestlers' => [
                                        0 => ['a-string-not-an-integer'],
                                        1 => 1
                                    ]
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.wrestlers.*');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function wrestler_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'wrestlers' => [
                                        0 => [99],
                                    ]
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.wrestlers.*');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function wrestler_array_count_must_equal_number_of_wrestlers_according_to_its_match_type()
    {
        $matchType = factory(MatchType::class)->create(['total_competitors' => 5]);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'match_type_id' => $matchType->id,
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function wrestler_must_be_qualified()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => Carbon::tomorrow()]);
        $event = factory(Event::class)->create(['date' => Carbon::yesterday()]);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $event->id]))
                        ->post(route('matches.store', ['event' => $event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'wrestlers' => [
                                        0 => [$wrestler->id]
                                    ]
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $event->id]));
        $response->assertSessionHasErrors('matches.*.wrestlers.*.*');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function preview_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'preview' => ''
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.preview');
        $this->assertEquals(0, $this->event->matches->count());
    }

    /** @test */
    public function preview_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', ['event' => $this->event->id]))
                        ->post(route('matches.store', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                0 => [
                                    'preview' => []
                                ]
                            ]
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $response->assertSessionHasErrors('matches.*.preview');
        $this->assertEquals(0, $this->event->matches->count());
    }
}
