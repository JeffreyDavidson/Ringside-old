<?php

namespace Tests\Feature\Match;

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

class AddMatchTest extends TestCase
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

        $this->setupAuthorizedUser(['create-match']);

        $this->event = factory(Event::class)->create(['date' => Carbon::now()]);
        $this->matchtype = factory(MatchType::class)->create(['number_of_sides' => 2, 'total_competitors' => 2]);
        $this->referee = factory(Referee::class)->create();
        $this->wrestlerA = factory(Wrestler::class)->create(['hired_at' => Carbon::yesterday()]);
        $this->wrestlerB = factory(Wrestler::class)->create(['hired_at' => Carbon::yesterday()]);
        $this->title = factory(Title::class)->create(['introduced_at' => Carbon::yesterday()]);
        $this->stipulation = factory(Stipulation::class)->create();
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_match_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('matches.create', $this->event->id));

        $response->assertSuccessful();
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_event_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('matches.create', $this->event->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_event_page()
    {
        $response = $this->get(route('matches.create', $this->event->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function users_who_have_permission_can_add_valid_matches_to_an_event()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), [
                            'matches' => [
                                0 => [
                                    'match_type_id' => $this->matchtype->id,
                                    'stipulation_id' => $this->stipulation->id,
                                    'titles' => [$this->title->id],
                                    'referees' => [$this->referee->id],
                                    'wrestlers' => [
                                        [$this->wrestlerA->id],
                                        [$this->wrestlerB->id],
                                    ],
                                    'preview' => 'Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper.',
                                ],
                            ],
                        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('events.show', $this->event->id));
        tap(Match::first(), function ($match) {
            $this->assertTrue($match->event->is($this->event));
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
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => 'a-string-not-an-array',
                        ]));

        $this->assertFormError('matches');
    }

    /** @test */
    public function match_type_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'match_type_id' => '',
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.match_type_id');
    }

    /** @test */
    public function match_type_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'match_type_id' => 'a-string-not-an-integer',
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.match_type_id');
    }

    /** @test */
    public function match_type_must_exist_in_the_database()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'match_type_id' => 99,
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.match_type_id');
    }

    /** @test */
    public function stipulation_is_optional()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'stipulation_id' => '',
                                ],
                            ],
                        ]));

        tap(Event::first()->matches->first(), function ($match) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('events.show', $this->event->id));
            $this->assertNull($match->stipulation_id);
        });
    }

    /** @test */
    public function stipulation_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'stipulation_id' => 'a-string-not-an-integer',
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.stipulation_id');
    }

    /** @test */
    public function stipulation_must_exist_in_the_database()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'stipulation_id' => 99,
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.stipulation_id');
    }

    /** @test */
    public function titles_must_be_an_array()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'titles' => 'a-string-not-an-array',
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.titles');
    }

    /** @test */
    public function title_must_be_distinct()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'titles' => [1, 2, 3, 1],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.titles.*');
    }

    /** @test */
    public function title_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'titles' => ['a-string-not-an-integer'],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.titles.*');
    }

    /** @test */
    public function title_must_exist_in_the_database()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'titles' => [99],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.titles.*');
    }

    /** @test */
    public function referees_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'referees' => '',
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.referees');
    }

    /** @test */
    public function referees_must_be_an_array()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'referees' => 'a-string-not-an-array',
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.referees');
    }

    /** @test */
    public function referee_must_be_distinct()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'referees' => [1, 2, 3, 1],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.referees.*');
    }

    /** @test */
    public function referee_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'referees' => ['a-string-not-an-integer'],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.referees.*');
    }

    /** @test */
    public function referee_must_exist_in_the_database()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'referees' => [99],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.referees.*');
    }

    /** @test */
    public function wrestlers_are_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'wrestlers' => '',
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.wrestlers');
    }

    /** @test */
    public function wrestlers_must_be_an_array()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'wrestlers' => 'a-string-not-an-array',
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.wrestlers');
    }

    /** @test */
    public function wrestler_must_be_distinct()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'wrestlers' => [
                                        [1, 2],
                                        [3, 1],
                                    ],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.wrestlers');
    }

    /** @test */
    public function wrestler_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'wrestlers' => [
                                        ['a-string-not-an-integer'],
                                    ],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.wrestlers.*');
    }

    /** @test */
    public function wrestler_must_exist_in_the_database()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'wrestlers' => [
                                        [99],
                                    ],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.wrestlers.*');
    }

    /** @test */
    public function wrestler_array_count_must_equal_number_of_wrestlers_according_to_its_match_type()
    {
        $matchType = factory(MatchType::class)->create(['total_competitors' => 5]);

        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'match_type_id' => $matchType->id,
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*');
    }

    /** @test */
    public function wrestler_must_be_qualified()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => Carbon::tomorrow()]);
        $this->event = factory(Event::class)->create(['date' => Carbon::yesterday()]);

        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'wrestlers' => [
                                        [$wrestler->id],
                                    ],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.wrestlers.*.*');
    }

    /** @test */
    public function preview_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'preview' => '',
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.preview');
    }

    /** @test */
    public function preview_must_be_a_string()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                        ->from(route('matches.create', $this->event->id))
                        ->post(route('matches.store', $this->event->id), $this->validParams([
                            'matches' => [
                                [
                                    'preview' => [],
                                ],
                            ],
                        ]));

        $this->assertFormError('matches.*.preview');
    }

    private function validParams($overrides = [])
    {
        return array_replace_recursive([
            'matches' => [
                [
                    'match_type_id' => $this->matchtype->id,
                    'stipulations_id' => $this->stipulation->id,
                    'titles' => [$this->title->id],
                    'referees' => [$this->referee->id],
                    'wrestlers' => [
                        [$this->wrestlerA->id],
                        [$this->wrestlerB->id],
                    ],
                    'preview' => 'Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper.',
                ],
            ],
        ], $overrides);
    }

    private function assertFormError($field, $expectedEventCount = 0)
    {
        $this->response->assertStatus(302);
        $this->response->assertRedirect(route('matches.create', ['event' => $this->event->id]));
        $this->response->assertSessionHasErrors($field);
        $this->assertEquals($expectedEventCount, $this->event->matches()->count());
    }
}
