<?php

namespace Tests\Feature\Roster\TagTeam;

use App\Models\Event;
use Facades\MatchFactory;
use App\Models\Roster\TagTeam;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;

class UpdateTagTeamTest extends IntegrationTestCase
{
    private $oldWrestlerIds;
    private $newWrestlerIds;
    
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-roster-member']);

        $this->oldWrestlerIds = factory(Wrestler::class, 2)->create()->modelKeys();
        $this->newWrestlerIds = factory(Wrestler::class, 2)->create()->modelKeys();
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Old Tag Team Name',
            'slug' => 'old-tag-team-slug',
            'signature_move' => 'Old Tag Team Signature Move',
            'hired_at' => '2017-09-08',
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'New Tag Team Name',
            'slug' => 'new-tag-team-slug',
            'signature_move' => 'New Tag Team Signature Move',
            'hired_at' => '2017-10-09',
            'wrestlers' => $this->newWrestlerIds,
        ], $overrides);
    }

    /** @test */
    public function users_who_have_permission_can_update_a_tag_team_with_no_matches()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes())->addWrestlers($this->oldWrestlerIds);

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams());

        $response->assertStatus(302);
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('New Tag Team Name', $tagteam->name);
            $this->assertEquals('new-tag-team-slug', $tagteam->slug);
            $this->assertEquals('New Tag Team Signature Move', $tagteam->signature_move);
            $this->assertEquals('2017-10-09', $tagteam->hired_at->toDateString());
            $this->assertCount(2, $tagteam->currentWrestlers);
            $this->assertTrue($tagteam->currentWrestlers->contains($this->newWrestlerIds[0]));
            $this->assertTrue($tagteam->currentWrestlers->contains($this->newWrestlerIds[1]));
        });
    }

    /** @test */
    public function a_tag_team_hired_at_date_must_be_before_its_first_match()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes(['hired_at' => '2017-09-10']));
        $event = factory(Event::class)->create(['date' => '2017-10-11']);
        $match = MatchFactory::forEvent($event)->withTagTeam($tagteam)->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'hired_at' => '2017-10-01',
        ]));

        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('2017-10-01', $tagteam->hired_at->toDateString());
        });
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_update_a_tag_team()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->unauthorizedUser)->patch(route('tagteams.update', $tagteam->id), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_update_a_tag_team()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->patch(route('tagteams.update', $tagteam->id), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_active_tag_team_that_is_updated_is_redirected_to_active_tag_teams_page()
    {
        $tagteam = factory(TagTeam::class)->states('active')->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams());

        $response->assertRedirect(route('active-tagteams.index'));
    }

    /** @test */
    public function an_inactive_tag_team_that_is_updated_is_redirected_to_inactive_tag_teams_page()
    {
        $tagteam = factory(TagTeam::class)->states('inactive')->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams());

        $response->assertRedirect(route('inactive-tagteams.index'));
    }

    /** @test */
    public function a_retired_tag_team_that_is_updated_is_redirected_to_retired_tag_teams_page()
    {
        $tagteam = factory(TagTeam::class)->states('retired')->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams());

        $response->assertRedirect(route('retired-tagteams.index'));
    }

    /** @test */
    public function tag_team_name_is_required()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('name');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('Old Tag Team Name', $tagteam->name);
        });
    }

    /** @test */
    public function tag_team_name_must_be_a_string()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'name' => '',
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('name');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('Old Tag Team Name', $tagteam->name);
        });
    }

    /** @test */
    public function tag_team_name_must_be_unique()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());
        factory(TagTeam::class)->create(['name' => 'Tag Team Name']);

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'name' => 'Tag Team Name',
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('name');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('Old Tag Team Name', $tagteam->name);
        });
    }

    /** @test */
    public function tag_team_slug_is_required()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'slug' => '',
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('slug');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('old-tag-team-slug', $tagteam->slug);
        });
    }

    /** @test */
    public function tag_team_slug_must_be_a_string()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'slug' => [],
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('slug');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('old-tag-team-slug', $tagteam->slug);
        });
    }

    /** @test */
    public function tag_team_slug_must_be_unique()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());
        factory(TagTeam::class)->create(['slug' => 'tag-team-slug']);

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'slug' => 'tag-team-slug',
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('slug');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('old-tag-team-slug', $tagteam->slug);
        });
    }

    /** @test */
    public function tag_team_signature_move_is_required()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'signature_move' => '',
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('signature_move');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('Old Tag Team Signature Move', $tagteam->signature_move);
        });
    }

    /** @test */
    public function tag_team_signature_move_must_be_a_string()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'signature_move' => [],
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('signature_move');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('Old Tag Team Signature Move', $tagteam->signature_move);
        });
    }

    /** @test */
    public function tag_team_signature_move_must_be_unique()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());
        factory(TagTeam::class)->create(['signature_move' => 'Tag Team Signature Move']);

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'signature_move' => 'Tag Team Signature Move',
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('signature_move');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('Old Tag Team Signature Move', $tagteam->signature_move);
        });
    }

    /** @test */
    public function tag_team_hired_at_date_is_required()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'hired_at' => '',
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('hired_at');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('2017-09-08', $tagteam->hired_at->toDateString());
        });
    }

    /** @test */
    public function tag_team_hired_at_date_must_be_a_string()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'hired_at' => [],
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('hired_at');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('2017-09-08', $tagteam->hired_at->toDateString());
        });
    }

    /** @test */
    public function tag_team_hired_at_date_must_be_a_valid_date_format()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'hired_at' => 'not-a-valid-date',
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('hired_at');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('2017-09-08', $tagteam->hired_at->toDateString());
        });
    }

    /** @test */
    public function tag_team_hired_at_date_must_be_before_first_competed_for_match()
    {
        $tagteam = factory(TagTeam::class)->create($this->oldAttributes());
        $event = factory(Event::class)->create(['date' => '2017-11-09']);
        $match = MatchFactory::forEvent($event)->withCompetitors($tagteam)->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('tagteams.edit', $tagteam->id))->patch(route('tagteams.update', $tagteam->id), $this->validParams([
            'hired_at' => '2017-11-10',
        ]));

        $response->assertRedirect(route('tagteams.edit', $tagteam->id));
        $response->assertSessionHasErrors('hired_at');
        tap($tagteam->fresh(), function ($tagteam) {
            $this->assertEquals('2017-09-08', $tagteam->hired_at->toDateString());
        });
    }
}
