<?php

namespace Tests\Feature\Wrestler;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddWrestlerTest extends TestCase
{
    use RefreshDatabase;

    private $response;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-wrestler', 'store-wrestler']);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Wrestler Name',
            'slug' => 'wrestler-slug',
            'status_id' => 1,
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

    /** @test */
    public function users_who_have_permission_can_view_the_add_wrestler_page()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.create'));

        $response->assertSuccessful();
        $response->assertViewIs('wrestlers.create');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_wrestler_page()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('wrestlers.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_have_permission_can_create_a_wrestler()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams());

        tap(Wrestler::first(), function ($wrestler) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect(route('wrestlers.index'));

            $this->assertEquals('Wrestler Name', $wrestler->name);
            $this->assertEquals('wrestler-slug', $wrestler->slug);
            $this->assertEquals('1', $wrestler->status_id);
            $this->assertEquals('2017-09-08', $wrestler->hired_at->toDateString());
            $this->assertEquals('Laraville, ON', $wrestler->hometown);
            $this->assertEquals(82, $wrestler->height);
            $this->assertEquals(175, $wrestler->weight);
            $this->assertEquals('Wrestler Signature Move', $wrestler->signature_move);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_create_a_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->post(route('wrestlers.index'), $this->validParams());

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
    public function guests_cannot_create_a_wrestler()
    {
        $response = $this->post(route('wrestlers.index'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function wrestler_name_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
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
                            ->post(route('wrestlers.index'), $this->validParams([
                                'name' => 'Wrestler Name',
                            ]));

        $this->assertFormError('name', 1);
    }

    /** @test */
    public function wrestler_slug_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
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
                            ->post(route('wrestlers.index'), $this->validParams([
                                'slug' => 'wrestler-slug',
                            ]));

        $this->assertFormError('slug', 1);
    }

    /** @test */
    public function wrestler_status_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'status_id' => '',
                            ]));

        $this->assertFormError('status_id');
    }

    /** @test */
    public function wrestler_status_must_exist_in_database()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'status_id' => 99,
                            ]));

        $this->assertFormError('status_id');
    }

    /** @test */
    public function wrestler_hometown_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'hometown' => '',
                            ]));

        $this->assertFormError('hometown');
    }

    /** @test */
    public function wrestler_feet_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'feet' => '',
                            ]));

        $this->assertFormError('feet');
    }

    /** @test */
    public function wrestler_feet_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'feet' => 'abc',
                            ]));

        $this->assertFormError('feet');
    }

    /** @test */
    public function wrestler_inches_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'inches' => '',
                            ]));

        $this->assertFormError('inches');
    }

    /** @test */
    public function wrestler_inches_must_have_a_value_smaller_than_twelve()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'inches' => '12',
                            ]));

        $this->assertFormError('inches');
    }

    /** @test */
    public function wrestler_weight_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'weight' => '',
                            ]));

        $this->assertFormError('weight');
    }

    /** @test */
    public function wrestler_weight_must_be_an_integer()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'weight' => 'abc',
                            ]));

        $this->assertFormError('weight');
    }

    /** @test */
    public function wrestler_signature_move_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'signature_move' => '',
                            ]));

        $this->assertFormError('signature_move');
    }

    /** @test */
    public function wrestler_hired_at_is_required()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'hired_at' => '',
                            ]));

        $this->assertFormError('hired_at');
    }

    /** @test */
    public function wrestler_hired_at_must_be_a_date()
    {
        $this->response = $this->actingAs($this->authorizedUser)
                            ->from(route('wrestlers.create'))
                            ->post(route('wrestlers.index'), $this->validParams([
                                'hired_at' => 'not-a-date',
                            ]));

        $this->assertFormError('hired_at');
    }
}
