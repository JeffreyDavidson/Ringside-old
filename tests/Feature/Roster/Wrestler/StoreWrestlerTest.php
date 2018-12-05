<?php

namespace Tests\Feature\Roster\Wrestler;

use Carbon\Carbon;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;

class StoreWrestlerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-roster-member']);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Wrestler Name',
            'slug' => 'wrestler-slug',
            'hometown' => 'Laraville, ON',
            'feet' => 6,
            'inches' => 10,
            'weight' => 175,
            'signature_move' => 'Wrestler Signature Move',
            'hired_at' => '2017-09-08',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_store_a_wrestler()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams());

        $response->assertStatus(302);
        tap(Wrestler::first(), function ($wrestler) {
            $this->assertEquals('Wrestler Name', $wrestler->name);
            $this->assertEquals('2017-09-08', $wrestler->hired_at->toDateString());
            $this->assertEquals('Laraville, ON', $wrestler->hometown);
            $this->assertEquals(82, $wrestler->height);
            $this->assertEquals(175, $wrestler->weight);
            $this->assertEquals('Wrestler Signature Move', $wrestler->signature_move);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_store_a_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)->post(route('wrestlers.store'), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_store_a_wrestler()
    {
        $response = $this->post(route('wrestlers.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_wrestler_that_is_hired_today_or_before_is_set_to_active()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'hired_at' => Carbon::today()->toDateString(),
        ]));

        $response->assertRedirect(route('active-wrestlers.index'));
        tap(Wrestler::first(), function ($wrestler) {
            $this->assertTrue($wrestler->is_active);
        });
    }

    /** @test */
    public function wrestler_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function wrestler_name_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'name' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function wrestler_name_must_be_unique()
    {
        factory(Wrestler::class)->create(['name' => 'Wrestler Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'name' => 'Wrestler Name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Wrestler::count());
    }

    /** @test */
    public function a_wrestler_that_is_hired_after_today_is_set_to_inactive()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'hired_at' => Carbon::tomorrow()->toDateString(),
        ]));

        $response->assertRedirect(route('inactive-wrestlers.index'));
        tap(Wrestler::first(), function ($wrestler) {
            $this->assertFalse($wrestler->is_active);
        });
    }

    /** @test */
    public function wrestler_slug_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function wrestler_slug_is_must_be_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'slug' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function wrestler_slug_must_be_unique()
    {
        factory(Wrestler::class)->create(['slug' => 'wrestler-slug']);

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'slug' => 'wrestler-slug',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Wrestler::count());
    }

    /** @test */
    public function wrestler_hometown_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'hometown' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hometown');
    }

    /** @test */
    public function wrestler_hometown_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'hometown' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hometown');
    }

    /** @test */
    public function wrestler_feet_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'feet' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('feet');
    }

    /** @test */
    public function wrestler_feet_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'feet' => 'not-an-integer',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('feet');
    }

    /** @test */
    public function wrestler_inches_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'inches' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('inches');
    }

    /** @test */
    public function wrestler_inches_must_have_a_value_smaller_than_twelve()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'inches' => '12',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('inches');
    }

    /** @test */
    public function wrestler_weight_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'weight' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('weight');
    }

    /** @test */
    public function wrestler_weight_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'weight' => 'abc',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('weight');
    }

    /** @test */
    public function wrestler_signature_move_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'signature_move' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('signature_move');
    }

    /** @test */
    public function wrestler_signature_move_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'signature_move' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('signature_move');
    }

    /** @test */
    public function wrestler_signature_move_must_be_unique()
    {
        factory(Wrestler::class)->create(['signature_move' => 'Wrestler Signature Move']);
        
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'signature_move' => 'Wrestler Signature Move',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('signature_move');
    }

    /** @test */
    public function wrestler_hired_at_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'hired_at' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hired_at');
    }

    /** @test */
    public function wrestler_hired_at_date_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'hired_at' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hired_at');
    }

    /** @test */
    public function wrestler_hired_at_date_must_be_a_valid_date_format()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.create'))->post(route('wrestlers.store'), $this->validParams([
            'hired_at' => 'not-a-valid-date',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hired_at');
    }
}
