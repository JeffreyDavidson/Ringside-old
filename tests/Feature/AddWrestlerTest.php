<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddWrestlerTest extends TestCase
{
    use RefreshDatabase;

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

    /** @test */
    public function users_who_have_permission_can_view_the_add_wrestler_form()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->get(route('wrestlers.create'));

        $response->assertSuccessful();
        $response->assertViewHas('statuses');
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
            $this->assertEquals('1', $wrestler->status());
            $this->assertEquals('2017-09-08', $wrestler->hired_at->toDateString());
            $this->assertEquals('Laraville, ON', $wrestler->hometown);
            $this->assertEquals(82, $wrestler->height);
            $this->assertEquals(175, $wrestler->weight);
            $this->assertEquals('Wrestler Signature Move', $wrestler->signature_move);
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_add_wrestler_form()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->get(route('wrestlers.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_create_a_wrestler()
    {
        $response = $this->actingAs($this->unauthorizedUser)
                        ->post(route('wrestlers.index'), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_add_wrestler_form()
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
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'name' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_name_must_be_unique()
    {
        factory(Wrestler::class)->create(['name' => 'Wrestler Name']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'name' => 'Wrestler Name',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Wrestler::count());
    }

    /** @test */
    public function wrestler_slug_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'slug' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_slug_must_be_unique()
    {
        factory(Wrestler::class)->create(['slug' => 'wrestler-slug']);

        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'slug' => 'wrestler-slug',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('slug');
        $this->assertEquals(1, Wrestler::count());
    }

    /** @test */
    public function wrestler_status_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'status_id' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('status_id');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_status_must_exist_in_database()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'status_id' => 99,
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('status_id');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_hometown_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'hometown' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hometown');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_feet_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'feet' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('feet');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_feet_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'feet' => 'abc',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('feet');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_inches_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'inches' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('inches');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_inches_must_have_a_value_smaller_than_12()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'inches' => '12',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('inches');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_weight_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'weight' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('weight');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_weight_must_be_an_integer()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'weight' => 'abc',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('weight');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_signature_move_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'signature_move' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('signature_move');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_hired_at_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'hired_at' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hired_at');
        $this->assertEquals(0, Wrestler::count());
    }

    /** @test */
    public function wrestler_hired_at_must_be_a_date()
    {
        $response = $this->actingAs($this->authorizedUser)
                        ->from(route('wrestlers.create'))
                        ->post(route('wrestlers.index'), $this->validParams([
                            'hired_at' => 'not-a-date',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('wrestlers.create'));
        $response->assertSessionHasErrors('hired_at');
        $this->assertEquals(0, Wrestler::count());
    }
}
