<?php

namespace Tests\Feature\Match;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Match;
use App\Models\Title;
use App\Models\Roster\Referee;
use App\Models\Roster\Wrestler;
use App\Models\MatchType;
use App\Models\Stipulation;
use Tests\IntegrationTestCase;

class AddMatchTest extends IntegrationTestCase
{
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

    private function validParams($overrides = [])
    {
        return array_merge([
            'match_type_id' => $this->matchtype->id,
            'stipulation_id' => $this->stipulation->id,
            'titles' => [$this->title->id],
            'referees' => [$this->referee->id],
            'wrestlers' => [
                [$this->wrestlerA->id],
                [$this->wrestlerB->id],
            ],
            'preview' => 'Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper.',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_match_page()
    {
        $response = $this->actingAs($this->authorizedUser)->get(route('matches.create', $this->event->id));

        $response->assertSuccessful();
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_event_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('matches.create', $this->event->id));

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
    public function users_who_have_permission_can_add_a_valid_match_to_an_event()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'match_type_id' => $this->matchtype->id,
            'stipulation_id' => $this->stipulation->id,
            'titles' => [$this->title->id],
            'referees' => [$this->referee->id],
            'wrestlers' => [
                [$this->wrestlerA->id],
                [$this->wrestlerB->id],
            ],
            'preview' => 'Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper.',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.show', $this->event->id));
        tap($this->event->matches()->first(), function ($match) {
            $this->assertTrue($match->event->is($this->event));
            $this->assertEquals($this->matchtype->name, $match->type->name);
            $this->assertEquals($this->stipulation->name, $match->stipulation->name);
            $this->assertCount(1, $match->titles);
            $this->assertEquals($this->referee->name, $match->referees->first()->name);
            $this->assertEquals($this->wrestlerA->name, $match->wrestlers->first()->name);
            $this->assertEquals($this->wrestlerB->name, $match->wrestlers->last()->name);
            $this->assertEquals('Maecenas faucibus mollis interdum. Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper.', $match->preview);
        });
    }

    /** @test */
    public function match_type_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'match_type_id' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('match_type_id');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function match_type_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'match_type_id' => 'a-string-not-an-integer',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('match_type_id');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function match_type_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'match_type_id' => 99,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('match_type_id');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function match_stipulation_is_optional_if_provided()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'stipulation_id' => '',
        ]));
        
        tap(Event::first()->matches->first(), function ($match) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('events.show', $this->event->id));
            $this->assertNull($match->stipulation_id);
        });
    }

    /** @test */
    public function match_stipulation_must_be_an_integer_if_provided()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'stipulation_id' => 'a-string-not-an-integer',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('stipulation_id');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function match_stipulation_must_exist_in_the_database_if_provided()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'stipulation_id' => 99,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('stipulation_id');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function match_titles_must_be_an_array_if_provided()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'titles' => 'a-string-not-an-array',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('titles');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function each_match_title_must_be_distinct_if_provided()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'titles' => [1, 2, 3, 1],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('titles.*');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function each_match_title_must_be_an_integer_if_provided()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'titles' => ['a-string-not-an-integer'],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('titles.*');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function each_match_title_must_exist_in_the_database_if_provided()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'titles' => [99],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('titles.*');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function match_referees_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'referees' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('referees');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function match_referees_must_be_an_array()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'referees' => 'a-string-not-an-array',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('referees');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function each_match_referee_must_be_distinct()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'referees' => [1, 2, 3, 1],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('referees.*');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function each_match_referee_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'referees' => ['a-string-not-an-integer'],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('referees.*');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function each_match_referee_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'referees' => [99],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('referees.*');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function match_wrestlers_are_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'wrestlers' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('wrestlers');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function match_wrestlers_must_be_an_array()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'wrestlers' => 'a-string-not-an-array',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('wrestlers');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function each_match_wrestler_must_be_distinct()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'wrestlers' => [
                [1, 2],
                [3, 1],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('wrestlers.*');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function each_match_wrestler_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'wrestlers' => [
                ['a-string-not-an-integer'],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('wrestlers.*');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function each_match_wrestler_must_exist_in_the_database()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'wrestlers' => [
                [99],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('wrestlers.*');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function wrestler_array_count_must_equal_number_of_wrestlers_according_to_its_match_type()
    {
        $matchType = factory(MatchType::class)->create(['total_competitors' => 5]);

        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'match_type_id' => $matchType->id,
            'wrestlers' => [
                0 => [1],
                1 => [2]
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('wrestlers.*');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function each_match_wrestler_must_be_qualified_for_the_match()
    {
        $wrestler = factory(Wrestler::class)->create(['hired_at' => Carbon::tomorrow()]);
        $this->event = factory(Event::class)->create(['date' => Carbon::yesterday()]);

        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'wrestlers' => [
                [$wrestler->id],
            ],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('wrestlers.*.*');
        $this->assertCount(0, $this->event->matches);

    }

    /** @test */
    public function match_preview_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'preview' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('preview');
        $this->assertCount(0, $this->event->matches);
    }

    /** @test */
    public function match_preview_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('matches.create', $this->event->id))->post(route('matches.store', $this->event->id), $this->validParams([
            'preview' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('matches.create', $this->event->id));
        $response->assertSessionHasErrors('preview');
        $this->assertCount(0, $this->event->matches);

    }
}
