<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\Event;
use App\Models\MatchType;
use App\Models\MatchDecision;
use App\Models\Title;
use App\Models\Champion;
use MatchFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEventMatchesWithResultsTest extends TestCase
{
    use RefreshDatabase;

    private $event;
    private $match;
    private $matchtype;
    private $response;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['edit-event-results', 'update-event-results']);

        $this->event = factory(Event::class)->create();
        $this->matchtype = factory(MatchType::class)->create(['number_of_sides' => 2, 'total_competitors' => 2]);
        $this->match = MatchFactory::create(['event_id' => $this->event->id, 'match_number' => 1, 'match_type_id' => $this->matchtype->id], factory(Wrestler::class, 2)->create());
    }

    private function validParams($overrides = [])
    {
        return array_replace_recursive([
            'matches' => [
                [
                    'match_decision_id' => 1,
                    'winner_id' => 1,
                    'result' => 'Donec sed odio dui. Cras mattis consectetur purus sit amet fermentum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.'
                ]
            ]
        ], $overrides);
    }

    private function assertFormError($field)
    {
        $this->response->assertStatus(302);
        $this->response->assertRedirect(route('results.edit', ['event' => $this->event->id]));
        $this->response->assertSessionHasErrors($field);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_event_results_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('results.edit', ['event' => $this->event->id]));

        $event = $this->event;

        $response->assertSuccessful();
        $response->assertViewIs('events.results');
        $response->assertViewHas('event', function ($viewEvent) use ($event) {
            return $viewEvent->id === $event->id;
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_event_results_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('results.edit', ['event' => $this->event->id]));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_event_results_page()
    {
        $response = $this->get(route('results.edit', ['event' => $this->event->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function users_who_have_permission_can_update_a_regular_match_with_a_result()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $this->event->id]))
                        ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => 1,
                                    'winner_id' => $this->event->matches->first()->wrestlers->first()->id,
                                    'result' => 'Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod.',
                                ]
                            ]
                        ]));

        $response->assertRedirect(route('events.index'));
    }

    /** @test */
    public function winners_and_losers_can_be_separated_based_off_result_of_match()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $this->event->id]))
                        ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => 1,
                                    'winner_id' => $this->event->matches->first()->wrestlers->first()->id,
                                    'result' => 'Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod.',
                                ]
                            ]
                        ]));

        tap($this->event->matches->first()->fresh(), function ($match) use ($response) {
            $this->assertTrue($match->wrestlers->contains('id', $match->winner_id));
            $this->assertFalse($match->losers->contains('id', $match->winner_id));
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
        $event = factory(Event::class)->create();
        $match = MatchFactory::createTitleMatchWithNoChampion(
                            ['event_id' => $event->id, 'match_type_id' => $this->matchtype->id],
                            factory(Title::class)->create(['introduced_at' => $event->date->subWeeks(2)]),
                            factory(Wrestler::class, 2)->create(['hired_at' => $event->date->subWeeks(2)])
                        );

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $event->id]))
                        ->patch(route('results.update', ['event' => $event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => MatchDecision::titleCanBeWonBySlug()->first()->id,
                                    'winner_id' => $event->matches->first()->wrestlers->first()->id,
                                ]
                            ]
                        ]));

        tap($event->matches->first()->fresh(), function ($match) use ($response) {
            $match->titles->each(function ($title, $key) use ($match) {
                $this->assertEquals($match->winner_id, $title->currentChampion->wrestler_id);
            });
        });
    }

    /** @test */
    public function a_title_match_with_a_set_champion_that_wins_a_title_match_keeps_the_title_and_increases_successful_defenses()
    {
        $event = factory(Event::class)->create(['date' => '2018-04-27 19:00:00']);
        $title = factory(Title::class)->create(['introduced_at' => $event->date->subMonths(5)]);
        $champion = factory(Champion::class)->create(['title_id' => $title->id, 'wrestler_id' => factory(Wrestler::class)->create(['hired_at' => $event->date->subMonths(4)])]);

        $match = MatchFactory::createTitleMatchWithChampion(
                            ['event_id' => $event->id, 'match_type_id' => $this->matchtype->id],
                            [$title],
                            [$champion->wrestler, factory(Wrestler::class)->create(['hired_at' => $event->date->subWeeks(2)])]
                        );

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('results.edit', ['event' => $event->id]))
                        ->patch(route('results.update', ['event' => $event->id]), $this->validParams([
                            'matches' => [
                                [
                                    'match_decision_id' => MatchDecision::titleCanBeWonBySlug()->first()->id,
                                    'winner_id' => $champion->wrestler->id,
                                ]
                            ]
                        ]));

        tap($event->matches->first()->fresh(), function ($match) use ($response) {
            $match->titles->each(function ($title, $key) use ($match) {
                $this->assertEquals($match->winner_id, $title->currentChampion->wrestler_id);
                $this->assertEquals(1, $title->currentChampion->successful_defenses);
            });
        });
    }

    /** @test */
    public function matches_must_be_an_array()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => 'a-string-not-an-array'
                            ]));

        $this->assertFormError('matches');
    }

    /** @test */
    public function it_fails_if_invalid_number_of_match_results_sent()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => $this->event->matches->times(3)->toArray(),
                            ]));

        $this->assertFormError('matches');
    }

    /** @test */
    public function each_match_decision_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'match_decision_id' => '',
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.match_decision_id');
    }

    /** @test */
    public function each_match_decision_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'match_decision_id' => 'abc',
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.match_decision_id');
    }

    /** @test */
    public function each_match_decision_must_have_a_value_more_than_one()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'match_decision_id' => 0,
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.match_decision_id');
    }

    /** @test */
    public function each_match_decision_must_exist_in_the_database()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'match_decision_id' => 99,
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.match_decision_id');
    }

    /** @test */
    public function each_match_winner_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'winner_id' => '',
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.winner_id');
    }

    /** @test */
    public function each_match_winner_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'winner_id' => 'abc',
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.winner_id');
    }

    /** @test */
    public function each_match_winner_must_have_a_value_more_than_one()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'winner_id' => 0,
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.winner_id');
    }

    /** @test */
    public function each_match_winner_must_exist_in_the_database()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'winner_id' => 99,
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.winner_id');
    }

    /** @test */
    public function each_match_winner_must_exist_in_the_match()
    {
        factory(Wrestler::class)->create(['id' => 3]);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'winner_id' => 3,
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.winner_id');
    }

    /** @test */
    public function each_match_result_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'result' => '',
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.result');
    }

    /** @test */
    public function each_match_result_must_be_a_string()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('results.edit', ['event' => $this->event->id]))
                            ->patch(route('results.update', ['event' => $this->event->id]), $this->validParams([
                                'matches' => [
                                    [
                                        'result' => [],
                                    ]
                                ]
                            ]));

        $this->assertFormError('matches.*.result');
    }
}
