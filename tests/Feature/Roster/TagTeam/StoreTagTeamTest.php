<?php

namespace Tests\Feature\Roster\TagTeam;

use App\Models\Roster\TagTeam;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;

class StoreTagTeamTest extends IntegrationTestCase
{
    private $wrestlers;

    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['create-roster-member']);

        $this->wrestlerIds = factory(Wrestler::class, 2)->create()->modelKeys();
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Tag Team Name',
            'slug' => 'tag-team-name',
            'signature_move' => 'Tag Team Signature Move',
            'hired_at' => '2017-09-08',
            'wrestlers' => $this->wrestlerIds,
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_store_a_tag_team_with_new_wrestlers()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams());

        $response->assertStatus(302);
        tap(TagTeam::first(), function ($tagteam) {
            $this->assertEquals('Tag Team Name', $tagteam->name);
            $this->assertEquals('Tag Team Signature Move', $tagteam->signature_move);
            $this->assertEquals('2017-09-08', $tagteam->hired_at->toDateString());
            $this->assertCount(2, $tagteam->wrestlers);
            $this->assertTrue($tagteam->wrestlers->contains($this->wrestlerIds[0]));
            $this->assertTrue($tagteam->wrestlers->contains($this->wrestlerIds[1]));
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_store_a_tagteam()
    {
        $response = $this->actingAs($this->unauthorizedUser)->post(route('tagteams.store'), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_store_a_tagteam()
    {
        $response = $this->post(route('tagteams.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function tag_team_name_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'name' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function tag_team_name_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'name' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function tag_team_name_must_be_unique()
    {
        factory(TagTeam::class)->create(['name' => 'Tag Team Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'name' => 'Tag Team Name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function tag_team_slug_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'slug' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function tag_team_slug_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'slug' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function tag_team_slug_must_be_unique()
    {
        factory(TagTeam::class)->create(['slug' => 'tag-team-name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'slug' => 'tag-team-name',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('slug');
    }

    /** @test */
    public function tag_team_signature_move_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'signature_move' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('signature_move');
    }

    /** @test */
    public function tag_team_signature_move_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'signature_move' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('signature_move');
    }

    /** @test */
    public function tag_team_signature_move_must_be_unique()
    {
        factory(TagTeam::class)->create(['signature_move' => 'Tag Team Signature Move']);

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'signature_move' => 'Tag Team Signature Move',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('signature_move');
    }

    /** @test */
    public function tag_team_hired_at_date_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'hired_at' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('hired_at');
    }

    /** @test */
    public function tag_team_hired_at_date_must_be_a_string()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'hired_at' => [],
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('hired_at');
    }

    /** @test */
    public function tag_team_hired_at_date_must_be_a_valid_date()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'hired_at' => 'not-a-valid-date',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('hired_at');
    }

    /** @test */
    public function tag_team_wrestlers_array_is_required()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'wrestlers' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('wrestlers');
    }

    /** @test */
    public function tag_team_wrestlers_array_must_be_an_array()
    {
        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $this->validParams([
            'wrestlers' => 'not-an-array',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('wrestlers');
    }

    /** @test */
    public function tag_team_wrestlers_array_must_contain_exactly_two_wrestlers()
    {
        $tagteam = $this->validParams();
        data_set($tagteam, 'wrestlers', [$this->wrestlerIds[0]]);

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $tagteam);

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('wrestlers');
    }

    /** @test */
    public function tag_team_wrestlers_must_be_an_integer()
    {
        $tagteam = $this->validParams();
        data_set($tagteam, 'wrestlers', ['not-an-integer', $this->wrestlerIds[0]]);

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $tagteam);

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('wrestlers.*');
    }

    /** @test */
    public function tag_team_wrestlers_must_exist_in_the_database()
    {
        $tagteam = $this->validParams();
        data_set($tagteam, 'wrestlers', [99, $this->wrestlerIds[0]]);

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.create'))->post(route('tagteams.store'), $tagteam);

        $response->assertStatus(302);
        $response->assertRedirect(route('tagteams.create'));
        $response->assertSessionHasErrors('wrestlers.*');
    }
}
