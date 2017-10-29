<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\MatchType;
use App\Models\Referee;
use App\Models\Stipulation;
use App\Models\Title;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Venue;
use App\Models\Wrestler;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddEventTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    private $wrestler1;

    private $wrestler2;

    private $referee1;

    private $referee2;

    private $stipulation1;

    private $stipulation2;

    private $title1;

    private $title2;

    private $matchType;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'create-event']);

        $this->venue = factory(Venue::class)->create();
        //$this->wrestler1 = factory(Wrestler::class)->create();
        //$this->wrestler2 = factory(Wrestler::class)->create();
        //$this->referee1 = factory(Referee::class)->create();
        //$this->referee2 = factory(Referee::class)->create();
        //$this->stipulation1 = factory(Stipulation::class)->create();
        //$this->stipulation2 = factory(Stipulation::class)->create();
        //$this->title1 = factory(Title::class)->create();
        //$this->title2 = factory(Title::class)->create();
        //$this->matchType = factory(MatchType::class)->create();

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    private function eventAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            'venue_id' => $this->venue->id,
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            'venue_id' => $this->venue->id,
            //'matches' => [
            //    1 => [
            //        'match_type_id' => $this->matchType->id,
            //        'stipulations' => [$this->stipulation1->id, $this->stipulation2->id],
            //        'titles' => [$this->title1->id, $this->title2->id],
            //        'referees' => [$this->referee1->id, $this->referee2->id],
            //        'wrestlers' => [$this->wrestler1->id, $this->wrestler2->id],
            //        'preview' => 'This is a preview of the match.',
            //    ]
            //],
        ], $overrides);
    }

    /** @test */
    function users_who_have_permission_can_view_the_add_event_form()
    {
        $response = $this->actingAs($this->user)->get(route('events.create'));

        $response->assertSuccessful();
        $response->assertViewIs('events.create');
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_add_event_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('events.create'));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_the_add_event_form()
    {
        $response = $this->get(route('events.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    function name_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function name_must_be_unique()
    {
        factory(Event::class)->create($this->eventAttributes([
            'name' => 'Event Name',
        ]));

        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'name' => 'Event Name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Event::where('name', 'Event Name')->count());
    }

    /** @test */
    function slug_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function slug_must_be_unique()
    {
        factory(Event::class)->create($this->eventAttributes([
            'slug' => 'event-slug',
        ]));

        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'slug' => 'event-slug',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Event::where('slug', 'event-slug')->count());
    }

    /** @test */
    function date_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'date' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Event::count());
    }

    ///** @test */
    function date_must_be_date()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'date' => 'not-a-date',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function venue_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'venue_id' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function venue_must_be_an_integer()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'venue_id' => 'abc',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function venue_must_be_a_valid_selection()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'venue_id' => 0,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    function venue_must_exist_in_database()
    {
        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'venue_id' => 99,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors('venue_id');
        $this->assertEquals(0, Event::count());
    }

    ///** @test */
    //function a_match_type_is_required()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'match_type_id' => '',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.match_type_id');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_type_must_be_an_integer()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'match_type_id' => 'abc',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.match_type_id');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_type_must_be_a_valid_selection()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'match_type_id' => 0,
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.match_type_id');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_type_must_already_exist()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'match_type_id' => 1,
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.match_type_id');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_stipulations_must_be_validated_only_if_there_is_a_value()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'stipulations' => '',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.stipulations');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_stipulations_must_be_an_array()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'stipulations' => 'abc',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.stipulations');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_stipulation_must_be_an_integer()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'stipulations' => ['abc'],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.stipulations.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_stipulation_must_be_an_valid_selection()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'stipulations' => [0],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.stipulations.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_stipulation_must_already_exist()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'stipulations' => [1],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.stipulations.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_titles_must_be_validated_only_if_there_is_a_value()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'titles' => '',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.titles');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_titles_must_be_an_array()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'titles' => 'abc',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.titles');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_title_must_be_an_integer()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'titles' => ['abc'],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.titles.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_title_must_be_an_valid_selection()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'titles' => [0],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.titles.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_title_must_already_exist()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'titles' => [1],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.titles.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_referees_is_required()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'referees' => '',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.referees');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_referees_must_be_an_array()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'referees' => 'abc',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.referees');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_referee_must_be_an_integer()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'referees' => ['abc'],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.referees.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_referee_must_be_an_valid_selection()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'referees' => [0],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.referees.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_referee_must_already_exist()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'referees' => [1],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.referees.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_wrestlers_is_required()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'wrestlers' => '',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.wrestlers');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function a_match_wrestlers_must_be_an_array()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'wrestlers' => 'abc',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.wrestlers');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_wrestler_must_be_an_integer()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'wrestlers' => ['abc'],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.wrestlers.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_wrestler_must_be_an_valid_selection()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'wrestlers' => [0],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.wrestlers.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///* TODO: Write test to make sure more than 1 wrestler is apart of match. */
    //
    ///** @test */
    //function each_match_wrestler_must_already_exist()
    //{
    //    //$this->disableExceptionHandling();
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            1 => [
    //                'wrestlers' => [99],
    //            ]
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.wrestlers.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_wrestler_must_be_qualified_for_the_event()
    //{
    //    $wrestler = factory(Wrestler::class)->create(['hired_at' => '2017-09-20']);
    //
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'date' => '2017-09-19',
    //        'matches' => [
    //            [
    //                'wrestlers' => [$wrestler->id],
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.wrestlers.*');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_preview_is_required()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'preview' => '',
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.preview');
    //    $this->assertEquals(0, Event::count());
    //}
    //
    ///** @test */
    //function each_match_preview_must_be_a_string()
    //{
    //    $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
    //        'matches' => [
    //            [
    //                'preview' => 12345,
    //            ],
    //        ],
    //    ]));
    //
    //    $response->assertStatus(302);
    //    $response->assertRedirect(route('events.create'));
    //    $response->assertSessionHasErrors('matches.*.preview');
    //    $this->assertEquals(0, Event::count());
    //}

    /** @test */
    function adding_a_valid_event()
    {
        //$this->disableExceptionHandling();
        //$stipulationA = factory(Stipulation::class)->create();
        //$stipulationB = factory(Stipulation::class)->create();
        //$wrestlerA = factory(Wrestler::class)->create();
        //$wrestlerB = factory(Wrestler::class)->create();
        //$matchType = factory(MatchType::class)->create();
        //$titleA = factory(Title::class)->create();
        //$titleB = factory(Title::class)->create();
        //$refereeA = factory(Referee::class)->create();
        //$refereeB = factory(Referee::class)->create();

        $response = $this->actingAs($this->user)->from(route('events.create'))->post(route('events.index'), $this->validParams([
            'name' => 'Event Name',
            'slug' => 'event-slug',
            'date' => '2017-09-17',
            'venue_id' => $this->venue->id,
            //'matches' => [
            //    [
            //        'match_type_id' => $matchType->id,
            //        'stipulations' => [$stipulationA->id, $stipulationB->id],
            //        'titles' => [$titleA->id, $titleB->id],
            //        'referees' => [$refereeA->id, $refereeB->id],
            //        'wrestlers' => [$wrestlerA->id, $wrestlerB->id],
            //        'preview' => 'This is a preview of the match.',
            //    ],
            //],
        ]));

        tap(Event::first(), function ($event) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('events.index'));

            $this->assertEquals('Event Name', $event->name);
            $this->assertEquals('event-slug', $event->slug);
            $this->assertEquals(Carbon::parse('2017-09-17'), $event->date);
            $this->assertEquals(1, $event->venue_id);
        });
    }
}
