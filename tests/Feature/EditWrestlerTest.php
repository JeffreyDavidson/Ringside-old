<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Match;
use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditWrestlerTest extends TestCase
{
    use RefreshDatabase;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['edit-wrestler', 'update-wrestler']);

        $this->wrestler = factory(Wrestler::class)->create($this->oldAttributes());
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
            'hired_at' => '2017-10-09',
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Wrestler Name',
            'slug' => 'wrestler-slug',
            'status_id' => 1,
            'hometown' => 'Laraville, FL',
            'feet' => 6,
            'inches' => 3,
            'weight' => 175,
            'signature_move' => 'New Signature Move',
            'hired_at' => '2017-10-09 12:00:00',
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_wrestler_form()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.edit', $this->wrestler->id));

        $response->assertSuccessful();
    }

    /** @test */
    public function users_who_have_permission_can_edit_a_wrestler_with_no_matches()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.edit', $this->wrestler->id))
                        ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
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
            $this->assertEquals('2017-09-10', $wrestler->hired_at->toDateString());
            $this->assertEquals(1, $wrestler->status_id);
            $this->assertEquals('Laraville, FL', $wrestler->hometown);
            $this->assertEquals(63, $wrestler->height);
            $this->assertEquals(175, $wrestler->weight);
            $this->assertEquals('Wrestler Signature Move', $wrestler->signature_move);
        });
    }

    /** @test */
    public function users_who_have_permission_can_edit_a_wrestler_with_matches()
    {
        $event = factory(Event::class)->create(['date' => '2017-10-11']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $sideNumber = mt_rand(0, $match->type->number_of_sides);
        $match->addWrestler($this->wrestler, $sideNumber);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.edit', $this->wrestler->id))
                        ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
                            'hired_at' => '2017-10-01',
                        ]));

        $response->assertRedirect(route('wrestlers.index'));
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('2017-10-01', $wrestler->hired_at->toDateString());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_wrestler_form()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('wrestlers.edit', $this->wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_edit_a_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_wrestler_form()
    {
        $response = $this->get(route('wrestlers.edit', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guests_cannot_edit_a_wrestler()
    {
        $response = $this->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function wrestler_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.edit', $this->wrestler->id))
                        ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
                            'name' => '',
                        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('name');
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old Name', $wrestler->name);
        });
    }

    /** @test */
    public function wrestler_name_must_be_unique()
    {
        factory(Wrestler::class)->create(['name' => 'Wrestler Name']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.edit', $this->wrestler->id))
                        ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
                            'name' => 'Wrestler Name',
                        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('name');
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Old Name', $wrestler->name);
        });
    }

    /** @test */
    public function wrestler_slug_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.edit', $this->wrestler->id))
                        ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
                            'slug' => '',
                        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('slug');
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('old-slug', $wrestler->slug);
        });
    }

    /** @test */
    public function wrestler_slug_must_be_unique()
    {
        factory(Wrestler::class)->create(['slug' => 'wrestler-slug']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.edit', $this->wrestler->id))
                        ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
                            'slug' => 'wrestler-slug',
                        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('slug');
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('old-slug', $wrestler->slug);
        });
    }

    /** @test */
    public function wrestler_status_can_be_changed()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.edit', $this->wrestler->id))
                        ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
                            'status_id' => 4,
                        ]));

        $response->assertRedirect(route('wrestlers.index'));
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals(4, $wrestler->status_id);
        });
    }

    /** @test */
    public function wrestler_hired_at_date_must_be_a_valid_date()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.edit', $this->wrestler->id))
                        ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
                            'hired_at' => 'not-a-date',
                        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('hired_at');
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('2017-10-09', $wrestler->hired_at->toDateString());
        });
    }

    /** @test */
    public function wrestler_hired_at_date_must_be_before_first_competed_for_match()
    {
        $event = factory(Event::class)->create(['date' => '2017-11-09']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $sideNumber = mt_rand(0, $match->type->number_of_sides);
        $match->addWrestler($this->wrestler, $sideNumber);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.edit', $this->wrestler->id))
                        ->patch(route('wrestlers.update', $this->wrestler->id), $this->validParams([
                            'hired_at' => '2017-11-10',
                        ]));

        $response->assertRedirect(route('wrestlers.edit', $this->wrestler->id));
        $response->assertSessionHasErrors('hired_at');
        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('2017-10-09', $wrestler->hired_at->toDateString());
        });
    }
}
