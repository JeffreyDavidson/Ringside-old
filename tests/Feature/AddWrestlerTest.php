<?php

namespace Tests\Feature;

use App\Models\Wrestler;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\WrestlerStatus;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddWrestlerTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'create-wrestler']);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Wrestler Name',
            'slug' => 'wrestler-slug',
            'status_id' => 1,
            'hired_at' => '2017-09-08',
            'hometown' => 'Laraville',
            'height' => 63,
            'weight' => 175,
            'signature_move' => 'Wrestler Signature Move',
        ], $overrides);
    }

    /** @test */
    function users_who_have_permission_can_view_the_add_wrestler_form()
    {
        factory(WrestlerStatus::class)->create(['name' => 'Active']);
        factory(WrestlerStatus::class)->create(['name' => 'Inactive']);

        $response = $this->actingAs($this->user)->get(route('wrestlers.create'));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.create');
        $response->assertViewHas('statuses');
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_the_add_wrestler_form()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('wrestlers.create'));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_the_add_wrestler_form()
    {
        $response = $this->get(route('wrestlers.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    function name_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function name_must_be_unique()
    {
        factory(Wrestler::class)->create(['name' => 'Wrestler Name']);

        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'name' => 'Wrestler Name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Wrestler::where('name', 'Wrestler Name')->count());
    }

    /** @test */
    function slug_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function slug_must_be_unique()
    {
        factory(Wrestler::class)->create(['slug' => 'wrestler-slug']);

        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'slug' => 'wrestler-slug',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Wrestler::where('slug', 'wrestler-slug')->count());
    }

    /** @test */
    function status_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'status_id' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('status_id');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function status_must_be_an_integer()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'status_id' => 'abc',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('status_id');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function status_must_be_a_nonzero_value()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'status_id' => 0,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('status_id');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function status_must_be_existant_in_the_database()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'status_id' => 1,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('status_id');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function hometown_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'hometown' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hometown');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function feet_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'feet' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('feet');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function feet_must_be_an_integer()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'feet' => 'abc',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('feet');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function inches_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'inches' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('inches');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function inches_must_be_an_integer()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'inches' => 'abc',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('inches');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function inches_must_have_a_value_smaller_than_12()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'status_id' => '12',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('inches');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function weight_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'weight' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('weight');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function weight_must_be_an_integer()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'weight' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('weight');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function signature_move_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'signature_move' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('signature_move');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function hired_at_is_required()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'hired_at' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hired_at');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function hired_at_must_be_a_date()
    {
        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'hired_at' => 'abc',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hired_at');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    function adding_a_valid_wrestler()
    {
        factory(WrestlerStatus::class)->create(['name' => 'Active']);
        factory(WrestlerStatus::class)->create(['name' => 'Inactive']);

        $response = $this->actingAs($this->user)->from(route('wrestlers.create'))->post(route('wrestlers.index'), $this->validParams([
            'name' => 'Wrestler Name',
            'slug' => 'wrestler-slug',
            'status_id' => 1,
            'hired_at' => '09/08/2017',
            'hometown' => 'Laraville, FL',
            'feet' => 5,
            'inches' => 3,
            'weight' => 175,
            'signature_move' => 'Wrestler Signature Move',
        ]));

        tap(Wrestler::first(), function ($wrestler) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('wrestlers.index'));

            $this->assertEquals('Wrestler Name', $wrestler->name);
            $this->assertEquals('wrestler-slug', $wrestler->slug);
            $this->assertEquals('1', $wrestler->status());
            $this->assertEquals(Carbon::parse('2017-09-08'), $wrestler->hired_at);
            $this->assertEquals('Laraville, FL', $wrestler->hometown);
            $this->assertEquals(63, $wrestler->height);
            $this->assertEquals(175, $wrestler->weight);
            $this->assertEquals('Wrestler Signature Move', $wrestler->signature_move);
        });
    }
}
