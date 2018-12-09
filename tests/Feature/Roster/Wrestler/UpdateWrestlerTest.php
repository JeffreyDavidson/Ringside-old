<?php

namespace Tests\Feature\Roster\Wrestler;

use App\Models\Event;
use App\Models\Roster\Wrestler;
use Facades\MatchFactory;
use Tests\IntegrationTestCase;

class UpdateWrestlerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-roster-member']);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Name',
            'slug' => 'old-slug',
            'hometown' => 'Old City, Old State',
            'height' => 63,
            'weight' => 175,
            'signature_move' => 'Old Signature Move',
            'hired_at' => '2017-10-09',
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'New Name',
            'slug' => 'new-slug',
            'hometown' => 'Laraville, FL',
            'feet' => 6,
            'inches' => 3,
            'weight' => 175,
            'signature_move' => 'New Signature Move',
            'hired_at' => '2017-10-09 12:00:00',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_update_a_wrestler_with_no_matches()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams());

        $response->assertStatus(302);
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('New Name', $wrestler->name);
            $this->assertEquals('new-slug', $wrestler->slug);
            $this->assertEquals('2017-10-09', $wrestler->hired_at->toDateString());
            $this->assertEquals('Laraville, FL', $wrestler->hometown);
            $this->assertEquals(75, $wrestler->height);
            $this->assertEquals(175, $wrestler->weight);
            $this->assertEquals('New Signature Move', $wrestler->signature_move);
        });
    }

    /** @test */
    public function a_wrestlers_hired_at_date_must_be_before_its_first_match()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes(['hired_at' => '2017-09-10']));
        $event = factory(Event::class)->create(['date' => '2017-10-11']);
        $match = MatchFactory::forEvent($event)->withCompetitor($wrestler)->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'hired_at' => '2017-10-01',
        ]));

        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('2017-10-01', $wrestler->hired_at->toDateString());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_update_a_wrestler()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->unauthorizedUser)->patch(route('wrestlers.update', $wrestler->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_update_a_wrestler()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->patch(route('wrestlers.update', $wrestler->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_active_wrestler_that_is_updated_is_redirected_to_active_wrestlers_page()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams());

        $response->assertRedirect(route('active-wrestlers.index'));
    }

    /** @test */
    public function an_inactive_wrestler_that_is_updated_is_redirected_to_inactive_wrestlers_page()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams());

        $response->assertRedirect(route('inactive-wrestlers.index'));
    }

    /** @test */
    public function a_retired_wrestler_that_is_updated_is_redirected_to_retired_wrestlers_page()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams());

        $response->assertRedirect(route('retired-wrestlers.index'));
    }

    /** @test */
    public function wrestler_name_is_required()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('name');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old Name', $wrestler->name);
        });
    }

    /** @test */
    public function wrestler_name_must_be_a_string()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('name');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old Name', $wrestler->name);
        });
    }

    /** @test */
    public function wrestler_name_must_be_unique()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());
        factory(Wrestler::class)->create(['name' => 'Wrestler Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'name' => 'Wrestler Name',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('name');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old Name', $wrestler->name);
        });
    }

    /** @test */
    public function wrestler_slug_is_required()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'slug' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('slug');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('old-slug', $wrestler->slug);
        });
    }

    /** @test */
    public function wrestler_slug_must_be_a_string()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'slug' => [],
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('slug');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('old-slug', $wrestler->slug);
        });
    }

    /** @test */
    public function wrestler_slug_must_be_unique()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());
        factory(Wrestler::class)->create(['slug' => 'wrestler-slug']);

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'slug' => 'wrestler-slug',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('slug');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('old-slug', $wrestler->slug);
        });
    }

    /** @test */
    public function wrestler_hometown_is_required()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'hometown' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('hometown');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old City, Old State', $wrestler->hometown);
        });
    }

    /** @test */
    public function wrestler_hometown_must_be_a_string()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'hometown' => [],
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('hometown');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old City, Old State', $wrestler->hometown);
        });
    }

    /** @test */
    public function wrestler_feet_is_required()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'feet' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('feet');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(63, $wrestler->height);
        });
    }

    /** @test */
    public function wrestler_feet_must_be_an_integer()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'feet' => 'not-an-integer',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('feet');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(63, $wrestler->height);
        });
    }

    /** @test */
    public function wrestler_inches_is_required()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'inches' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('inches');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(63, $wrestler->height);
        });
    }

    /** @test */
    public function wrestler_inches_must_be_an_integer()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'inches' => 'not-an-integer',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('inches');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(63, $wrestler->height);
        });
    }

    /** @test */
    public function wrestler_inches_must_be_lower_than_12()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'inches' => 12,
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('inches');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(63, $wrestler->height);
        });
    }

    /** @test */
    public function wrestler_weight_is_required()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'weight' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('weight');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(175, $wrestler->weight);
        });
    }

    /** @test */
    public function wrestler_weight_must_be_an_integer()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'weight' => 'not-an-integer',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('weight');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(175, $wrestler->weight);
        });
    }

    /** @test */
    public function wrestler_signature_move_is_required()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'signature_move' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('signature_move');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old Signature Move', $wrestler->signature_move);
        });
    }

    /** @test */
    public function wrestler_signature_move_must_be_a_string()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'signature_move' => [],
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('signature_move');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old Signature Move', $wrestler->signature_move);
        });
    }

    /** @test */
    public function wrestler_hired_at_date_is_required()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'hired_at' => '',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('hired_at');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('2017-10-09', $wrestler->hired_at->toDateString());
        });
    }

    /** @test */
    public function wrestler_hired_at_date_must_be_a_string()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'hired_at' => [],
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('hired_at');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('2017-10-09', $wrestler->hired_at->toDateString());
        });
    }

    /** @test */
    public function wrestler_hired_at_date_must_be_a_valid_date()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'hired_at' => 'not-a-valid-date',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('hired_at');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('2017-10-09', $wrestler->hired_at->toDateString());
        });
    }

    /** @test */
    public function wrestler_hired_at_date_must_be_before_first_competed_for_match()
    {
        $wrestler = factory(Wrestler::class)->create($this->oldAttributes());
        $event = factory(Event::class)->create(['date' => '2017-11-09']);
        $match = MatchFactory::forEvent($event)->withCompetitor($wrestler)->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('wrestlers.edit', $wrestler->id))->patch(route('wrestlers.update', $wrestler->id), $this->validParams([
            'hired_at' => '2017-11-10',
        ]));

        $response->assertRedirect(route('wrestlers.edit', $wrestler->id));
        $response->assertSessionHasErrors('hired_at');
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('2017-10-09', $wrestler->hired_at->toDateString());
        });
    }
}
