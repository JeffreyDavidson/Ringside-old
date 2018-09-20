<?php

namespace Tests\Feature\Wrestler;

use App\Models\Wrestler;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddWrestlerTest extends TestCase
{
    use RefreshDatabase;

    private $response;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-wrestler']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_add_wrestler_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.create'));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.create');
        $response->assertViewHas('wrestler');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_wrestler_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('wrestlers.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_wrestler_page()
    {
        $response = $this->get(route('wrestlers.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_create_a_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->post(route('wrestlers.store'), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_have_permission_can_create_a_wrestler()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.store'), $this->validParams());

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
    public function guests_cannot_create_a_wrestler()
    {
        $response = $this->post(route('wrestlers.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_wrestler_that_is_hired_today_or_before_is_active()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.store'), $this->validParams([
                            'hired_at' => Carbon::today(),
                        ]));

        $response->assertRedirect(route('active-wrestlers.index'));
        tap(Wrestler::first(), function ($wrestler) {
            $this->assertTrue($wrestler->is_active);
        });
    }

    /** @test */
    public function a_wrestler_that_is_hired_after_today_is_inactive()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.store'), $this->validParams([
                            'hired_at' => Carbon::tomorrow(),
                        ]));

        $response->assertRedirect(route('inactive-wrestlers.index'));
        tap(Wrestler::first(), function ($wrestler) {
            $this->assertFalse($wrestler->is_active);
        });
    }

    /** @test */
    public function wrestler_name_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'name' => '',
                            ]));

        $this->assertFormError('name');
    }

    /** @test */
    public function wrestler_name_must_be_unique()
    {
        factory(Wrestler::class)->create(['name' => 'Wrestler Name']);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'name' => 'Wrestler Name',
                            ]));

        $this->assertFormError('name', 1);
    }

    /** @test */
    public function wrestler_slug_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'slug' => '',
                            ]));

        $this->assertFormError('slug');
    }

    /** @test */
    public function wrestler_slug_must_be_unique()
    {
        factory(Wrestler::class)->create(['slug' => 'wrestler-slug']);

        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'slug' => 'wrestler-slug',
                            ]));

        $this->assertFormError('slug', 1);
    }

    /** @test */
    public function wrestler_hometown_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'hometown' => '',
                            ]));

        $this->assertFormError('hometown');
    }

    /** @test */
    public function wrestler_feet_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'feet' => '',
                            ]));

        $this->assertFormError('feet');
    }

    /** @test */
    public function wrestler_feet_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'feet' => 'abc',
                            ]));

        $this->assertFormError('feet');
    }

    /** @test */
    public function wrestler_inches_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'inches' => '',
                            ]));

        $this->assertFormError('inches');
    }

    /** @test */
    public function wrestler_inches_must_have_a_value_smaller_than_twelve()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'inches' => '12',
                            ]));

        $this->assertFormError('inches');
    }

    /** @test */
    public function wrestler_weight_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'weight' => '',
                            ]));

        $this->assertFormError('weight');
    }

    /** @test */
    public function wrestler_weight_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'weight' => 'abc',
                            ]));

        $this->assertFormError('weight');
    }

    /** @test */
    public function wrestler_signature_move_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'signature_move' => '',
                            ]));

        $this->assertFormError('signature_move');
    }

    /** @test */
    public function wrestler_hired_at_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'hired_at' => '',
                            ]));

        $this->assertFormError('hired_at');
    }

    /** @test */
    public function wrestler_hired_at_must_be_a_date()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.store'), $this->validParams([
                                'hired_at' => 'not-a-date',
                            ]));

        $this->assertFormError('hired_at');
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Wrestler Name',
            'slug' => 'wrestler-slug',
            'hired_at' => '2017-09-08',
            'hometown' => 'Laraville, ON',
            'feet' => 6,
            'inches' => 10,
            'weight' => 175,
            'signature_move' => 'Wrestler Signature Move',
        ], $overrides);
    }

    private function assertFormError($field, $expectedEventCount = 0)
    {
        $this->response->assertStatus(302);
        $this->response->assertRedirect(route('wrestlers.create'));
        $this->response->assertSessionHasErrors($field);
        $this->assertEquals($expectedEventCount, Wrestler::count());
    }
}
