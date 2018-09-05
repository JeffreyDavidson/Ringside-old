<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\Event;
use App\Models\MatchType;
use App\Models\MatchDecision;
use App\Models\Title;
use Facades\MatchFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEventMatchesWithResultsTest extends TestCase
{
    use RefreshDatabase;

    private $response;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['edit-event-results', 'update-event-results']);
    }

    public function createStandardMatch()
    {
        $event = factory(Event::class)->create();
        $matchtype = factory(MatchType::class)->create(['number_of_sides' => 2, 'total_competitors' => 2]);
        $match = MatchFactory::forEvent($event)->withMatchtype($matchtype)->create();

        return $match;
    }

    public function createStandardTitleMatchWithNoChampion()
    {
        $event = factory(Event::class)->create();
        $matchtype = factory(MatchType::class)->create(['number_of_sides' => 2, 'total_competitors' => 2]);
        $title = factory(Title::class)->create();

        $match = MatchFactory::forEvent($event)->withMatchtype($matchtype)->withTitle($title)->create();

        return $match;
    }

    public function createStandardTitleMatchWithChampion()
    {
        $event = factory(Event::class)->create(['date' => '2018-04-27 19:00:00']);
        $matchtype = factory(MatchType::class)->create(['number_of_sides' => 2, 'total_competitors' => 2]);
        $title = factory(Title::class)->create(['introduced_at' => $event->date->copy()->subMonths(4)]);
        $wrestler = factory(Wrestler::class)->create(['hired_at' => $event->date->copy()->subMonths(4)]);

        $match = MatchFactory::forEvent($event)
                            ->withMatchtype($matchtype)
                            ->withTitle($title)
                            ->withChampion($wrestler)
                            ->create();

        return $match;
    }

    private function nonChampionWinner($match)
    {
        return $match->wrestlers->reject(function ($wrestler, $key) use ($match) {
            return $wrestler->id == $match->titles->first()->currentChampion->wrestler->id;
        })->random()->id;
    }

    private function validParams($overrides = [])
    {
        return array_replace_recursive([
            'matches' => [
                [
                    'match_decision_id' => factory(MatchDecision::class)->create()->id,
                    'winner_id' => 1,
                    'result' => 'Donec sed odio dui. Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
                ],
            ],
        ], $overrides);
    }

    private function assertFormError($field, $match)
    {
        $this->response->assertStatus(302);
        $this->response->assertRedirect(route('results.edit', ['event' => $match->event->id]));
        $this->response->assertSessionHasErrors($field);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_event_results_page()
    {
        $event = factory(Event::class)->create();

        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('results.edit', ['event' => $event->id]));

        $response->assertSuccessful();
        $response->assertViewIs('events.results');
        $response->assertViewHas('event', function ($viewEvent) use ($event) {
            return $viewEvent->id === $event->id;
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_event_results_page()
    {
        $event = factory(Event::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('results.edit', ['event' => $event->id]));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_event_results_page()
    {
        $event = factory(Event::class)->create();

        $response = $this->get(route('results.edit', ['event' => $event->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function users_who_have_permission_can_update_a_regular_match_with_a_result()
    {
        $match = $this->createStandardMatch();

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $match->event->id]))
                        ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => factory(MatchDecision::class)->create()->id,
                                    'winner_id' => $match->wrestlers->first()->id,
                                    'result' => 'Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod.',
                                ],
                            ],
                        ]));

        $response->assertRedirect(route('events.index'));
    }

    /** @test */
    public function winners_and_losers_can_be_separated_based_off_decision_of_match()
    {
        $match = $this->createStandardMatch();

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $match->event->id]))
                        ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => 1,
                                    'winner_id' => $match->wrestlers->first()->id,
                                    'result' => 'Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod.',
                                ],
                            ],
                        ]));

        tap($match->fresh(), function ($match) use ($response) {
            dd($match->winner);
            $this->assertTrue($match->winner->is($match->wrestlers->first()));
            $this->assertFalse($match->losers->contains('id', $match->wrestlers->first()->id));
            $this->assertTrue(
                $match->losers->keyBy('id')->has(
                    $match->wrestlers->except($match->winner_id)->modelKeys()
                )
            );
            $this->assertEquals('Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod.', $match->result);
        });
    }

    /** @test */
    public function a_title_match_with_no_champion_can_crown_a_champion_depending_on_match_decision()
    {
        $match = $this->createStandardTitleMatchWithNoChampion();

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $match->event->id]))
                        ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => MatchDecision::titleCanBeWonBySlug()->first()->id,
                                    'winner_id' => $match->wrestlers->first()->id,
                                ],
                            ],
                        ]));

        tap($match->fresh(), function ($match) use ($response) {
            $match->titles->each(function ($title, $key) use ($match) {
                $this->assertEquals($match->winner_id, $title->fresh()->currentChampion->wrestler_id);
            });
        });
    }

    /** @test */
    public function a_title_match_with_no_champion_can_not_crown_a_champion_when_the_match_decision_does_not_allow_a_champion_to_be_crowned()
    {
        $match = $this->createStandardTitleMatchWithNoChampion();

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $match->event->id]))
                        ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => MatchDecision::titleCannotBeWonBySlug()->first()->id,
                                    'winner_id' => $match->wrestlers->first()->id,
                                ],
                            ],
                        ]));

        tap($match->fresh(), function ($match) use ($response) {
            $match->titles->each(function ($title, $key) use ($match) {
                $this->assertNull($title->currentChampion);
            });
        });
    }

    /** @test */
    public function a_title_match_with_a_set_champion_that_wins_a_title_match_keeps_the_title_and_increases_successful_defenses()
    {
        $match = $this->createStandardTitleMatchWithChampion();

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $match->event->id]))
                        ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => MatchDecision::titleCanBeWonBySlug()->first()->id,
                                    'winner_id' => $match->titles->first()->currentChampion->wrestler->id,
                                ],
                            ],
                        ]));

        tap($match->fresh(), function ($match) use ($response) {
            $match->titles->each(function ($title, $key) use ($match) {
                $this->assertEquals(1, $title->currentChampion->fresh()->successful_defenses);
            });
        });
    }

    /** @test */
    public function a_title_match_with_a_set_champion_that_loses_a_title_match_loses_title_if_the_title_can_change_hands()
    {
        $match = $this->createStandardTitleMatchWithChampion();

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $match->event->id]))
                        ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => MatchDecision::titleCanChangeHandsBySlug()->first()->id,
                                    'winner_id' => $this->nonChampionWinner($match),
                                ],
                            ],
                        ]));

        tap($match->fresh(), function ($match) use ($response) {
            $match->titles->each(function ($title, $key) use ($match) {
                $this->assertEquals($match->date->toDateTimeString(), $title->fresh()->champions->reverse()->slice(1, 1)->first()->lost_on->toDateTimeString());
                $this->assertEquals($match->winner_id, $title->fresh()->currentChampion->wrestler_id);
                $this->assertEquals($match->date->toDateTimeString(), $title->currentChampion->won_on);
            });
        });
    }

    /** @test */
    public function a_title_match_with_a_set_champion_that_loses_a_title_match_keeps_title_if_the_title_cannot_change_hands()
    {
        $match = $this->createStandardTitleMatchWithChampion();

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $match->event->id]))
                        ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => MatchDecision::titleCannotChangeHandsBySlug()->first()->id,
                                    'winner_id' => $this->nonChampionWinner($match),
                                ],
                            ],
                        ]));

        tap($match->fresh(), function ($match) use ($response) {
            $match->titles->each(function ($title, $key) use ($match) {
                $this->assertNotEquals($match->winner_id, $title->currentChampion->wrestler_id);
            });
        });
    }

    /** @test */
    public function matches_must_be_an_array()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => 'a-string-not-an-array',
                            ]));

        $this->assertFormError('matches', $match);
    }

    /** @test */
    public function it_fails_if_invalid_number_of_match_results_sent()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => $match->event->matches->times(3)->toArray(),
                            ]));

        $this->assertFormError('matches', $match);
    }

    /** @test */
    public function each_match_decision_is_required()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'match_decision_id' => '',
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.match_decision_id', $match);
    }

    /** @test */
    public function each_match_decision_must_be_an_integer()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'match_decision_id' => 'abc',
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.match_decision_id', $match);
    }

    /** @test */
    public function each_match_decision_must_have_a_value_more_than_one()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'match_decision_id' => 0,
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.match_decision_id', $match);
    }

    /** @test */
    public function each_match_decision_must_exist_in_the_database()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'match_decision_id' => 99,
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.match_decision_id', $match);
    }

    /** @test */
    public function each_match_winner_is_required()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'winner_id' => '',
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.winner_id', $match);
    }

    /** @test */
    public function each_match_winner_must_be_an_integer()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'winner_id' => 'abc',
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.winner_id', $match);
    }

    /** @test */
    public function each_match_winner_must_have_a_value_more_than_one()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'winner_id' => 0,
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.winner_id', $match);
    }

    /** @test */
    public function each_match_winner_must_exist_in_the_database()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'winner_id' => 99,
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.winner_id', $match);
    }

    /** @test */
    public function each_match_winner_must_exist_in_the_match()
    {
        $match = $this->createStandardMatch();
        factory(Wrestler::class)->create(['id' => 3]);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'winner_id' => 3,
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.winner_id', $match);
    }

    /** @test */
    public function each_match_result_is_required()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'result' => '',
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.result', $match);
    }

    /** @test */
    public function each_match_result_must_be_a_string()
    {
        $match = $this->createStandardMatch();

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $match->event->id]))
                            ->patch(route('results.update', ['event' => $match->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'result' => [],
                                    ],
                                ],
                            ]));

        $this->assertFormError('matches.*.result', $match);
    }
}
