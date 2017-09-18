<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Match;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Wrestler;
use App\Models\User;
use App\Models\WrestlerStatus;
use Carbon\Carbon;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditWrestlerTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'edit-wrestler']);
        $this->wrestler = factory(Wrestler::class)->create($this->oldAttributes());
        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Name',
            'slug' => 'old-slug',
            'status_id' => 1,
            'hometown' => 'Old City, Old State',
            'height' => 63,
            'weight' => 175,
            'signature_move' => 'Old Signature Move',
            'hired_at' => Carbon::parse('10/09/2017'),
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Wrestler Name',
            'slug' => 'wrestler-slug',
            'status_id' => 1,
            'hometown' => 'Laraville, FL',
            //'height' => 63,
            'feet' => 6,
            'inches' => 3,
            'weight' => 175,
            'signature_move' => 'Wrestler Signature Move',
            'hired_at' => '2017-10-09 12:00:00',
        ], $overrides);
    }

    /** @test */
    function users_who_have_permission_can_view_the_edit_wrestler_form()
    {
        factory(WrestlerStatus::class)->create(['name' => 'Active']);
        factory(WrestlerStatus::class)->create(['name' => 'Inactive']);
        factory(WrestlerStatus::class)->create(['name' => 'Injured']);
        factory(WrestlerStatus::class)->create(['name' => 'Suspended']);
        factory(WrestlerStatus::class)->create(['name' => 'Retired']);

        $response = $this->actingAs($this->user)->get(route('wrestlers.edit', $this->wrestler->id));

        $response->assertSuccessful();
        $this->assertTrue($response->data('wrestler')->is($this->wrestler));
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_edit_wrestler_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('wrestlers.edit', $this->wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_the_edit_wrestler_form()
    {
        $response = $this->get(route('wrestlers.edit', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    function name_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.edit', $this->wrestler->id))->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('name');
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old Name', $wrestler->name);
        });
    }

    /** @test */
    function name_must_be_unique()
    {
        factory(Wrestler::class)->create($this->validParams());

        $response = $this->actingAs($this->user)
            ->from(route('wrestlers.edit', $this->wrestler->id))
            ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
                'name' => 'Wrestler Name',
            ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Wrestler::where('name', 'Old Name')->count());
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old Name', $wrestler->name);
        });
    }

    /** @test */
    function slug_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.edit', $this->wrestler->id))->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
            'slug' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('slug');
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('old-slug', $wrestler->slug);
        });
    }

    /** @test */
    function slug_must_be_unique()
    {
        factory(Wrestler::class)->create($this->validParams());

        $response = $this->actingAs($this->user)->from(route('wrestlers.edit', $this->wrestler->id))->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
            'slug' => 'wrestler-slug',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Wrestler::where('slug', 'wrestler-slug')->count());
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('old-slug', $wrestler->slug);
        });
    }

    /** @test */
    function hired_at_date_must_be_a_valid_date()
    {
        factory(WrestlerStatus::class)->create(['name' => 'Active']);
        factory(WrestlerStatus::class)->create(['name' => 'Inactive']);
        factory(WrestlerStatus::class)->create(['name' => 'Injured']);
        factory(WrestlerStatus::class)->create(['name' => 'Suspended']);
        factory(WrestlerStatus::class)->create(['name' => 'Retired']);

        $response = $this->actingAs($this->user)->from(route('wrestlers.edit', $this->wrestler->id))->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
            'hired_at' => 'not-a-date',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('hired_at');
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(Carbon::parse('2017-10-09'), $wrestler->hired_at);
        });
    }

    /** @test */
    function hired_at_date_must_be_before_first_competed_for_match()
    {
        factory(WrestlerStatus::class)->create(['name' => 'Active']);
        factory(WrestlerStatus::class)->create(['name' => 'Inactive']);
        factory(WrestlerStatus::class)->create(['name' => 'Injured']);
        factory(WrestlerStatus::class)->create(['name' => 'Suspended']);
        factory(WrestlerStatus::class)->create(['name' => 'Retired']);
        $event = factory(Event::class)->create(['date' => '2017-11-09']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addWrestler($this->wrestler);

        $response = $this->actingAs($this->user)->from(route('wrestlers.edit', $this->wrestler->id))->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
            'hired_at' => '2017-11-10',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('hired_at');
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(Carbon::parse('2017-10-09'), $wrestler->hired_at);
        });
    }

    /** @test */
    function editing_a_valid_wrestler_with_no_matches()
    {
        $this->disableExceptionHandling();
        factory(WrestlerStatus::class)->create(['name' => 'Active']);
        factory(WrestlerStatus::class)->create(['name' => 'Inactive']);
        factory(WrestlerStatus::class)->create(['name' => 'Injured']);
        factory(WrestlerStatus::class)->create(['name' => 'Suspended']);
        factory(WrestlerStatus::class)->create(['name' => 'Retired']);

        $response = $this->actingAs($this->user)->from(route('wrestlers.edit', $this->wrestler->id))->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
            'name' => 'New Name',
            'slug' => 'new-slug',
            'status_id' => 1,
            'hometown' => 'Laraville, FL',
            'feet' => 5,
            'inches' => 3,
            'weight' => 175,
            'signature_move' => 'Wrestler Signature Move',
            'hired_at' => '2017-09-10',
        ]));

        $response->assertRedirect(route('wrestlers.index'));
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('New Name', $wrestler->name);
            $this->assertEquals('new-slug', $wrestler->slug);
            $this->assertEquals(Carbon::parse('2017-09-10'), $wrestler->hired_at);
            $this->assertEquals(1, $wrestler->status());
            $this->assertEquals('Laraville, FL', $wrestler->hometown);
            $this->assertEquals(63, $wrestler->height);
            $this->assertEquals(175, $wrestler->weight);
            $this->assertEquals('Wrestler Signature Move', $wrestler->signature_move);
        });
    }

    /** @test */
    function editing_a_valid_wrestler_with_matches()
    {
        $this->disableExceptionHandling();
        factory(WrestlerStatus::class)->create(['name' => 'Active']);
        factory(WrestlerStatus::class)->create(['name' => 'Inactive']);
        factory(WrestlerStatus::class)->create(['name' => 'Injured']);
        factory(WrestlerStatus::class)->create(['name' => 'Suspended']);
        factory(WrestlerStatus::class)->create(['name' => 'Retired']);

        $event = factory(Event::class)->create(['date' => '2017-10-11']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addWrestler($this->wrestler);


        $response = $this->actingAs($this->user)->from(route('wrestlers.edit', $this->wrestler->id))->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
                'hired_at' => '2017-10-01',
            ]));

        $response->assertRedirect(route('wrestlers.index'));
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(Carbon::parse('2017-10-01'), $wrestler->hired_at);
        });
    }
}
