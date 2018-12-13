<?php

namespace Tests\Unit\Models\Roster;

use Carbon\Carbon;
use App\Traits\Hireable;
use App\Traits\Retirable;
use App\Traits\Manageable;
use App\Traits\Statusable;
use App\Traits\Suspendable;
use App\Interfaces\Competitor;
use App\Models\Roster\TagTeam;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;
use App\Traits\CompetitorTrait;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagTeamTest extends IntegrationTestCase
{
    /** @test */
    public function a_tag_team_has_a_name()
    {
        $tagteam = factory(TagTeam::class)->create(['name' => 'Tag Team Name']);

        $this->assertEquals('Tag Team Name', $tagteam->name);
    }

    /** @test */
    public function a_tag_team_has_a_slug()
    {
        $tagteam = factory(TagTeam::class)->create(['slug' => 'tag-team-slug']);

        $this->assertEquals('tag-team-slug', $tagteam->slug);
    }

    /** @test */
    public function a_tag_team_has_a_signature_move()
    {
        $tagteam = factory(TagTeam::class)->create(['signature_move' => 'Tag Team Signature Move']);

        $this->assertEquals('Tag Team Signature Move', $tagteam->signature_move);
    }

    /** @test */
    public function a_tag_team_has_an_is_active_field()
    {
        $tagteam = factory(TagTeam::class)->create(['is_active' => true]);

        $this->assertTrue($tagteam->is_active);
    }

    /** @test */
    public function a_tag_team_has_a_hired_at_date()
    {
        $tagteam = factory(TagTeam::class)->create(['hired_at' => Carbon::parse('2018-10-01')]);

        $this->assertEquals('2018-10-01', $tagteam->hired_at->toDateString());
    }

    /** @test */
    public function a_tag_team_implements_the_competitor_interface()
    {
        $this->assertTrue(in_array(Competitor::class, class_implements(TagTeam::class)));
    }

    /** @test */
    public function a_tag_team_uses_the_competitor_trait()
    {
        $this->assertTrue(in_array(CompetitorTrait::class, class_uses(TagTeam::class)));
    }

    /** @test */
    public function a_tag_team_uses_the_statusable_trait()
    {
        $this->assertTrue(in_array(Statusable::class, class_uses(TagTeam::class)));
    }

    /** @test */
    public function a_tag_team_uses_the_retirable_trait()
    {
        $this->assertTrue(in_array(Retirable::class, class_uses(TagTeam::class)));
    }

    /** @test */
    public function a_tag_team_uses_the_suspendable_trait()
    {
        $this->assertTrue(in_array(Suspendable::class, class_uses(TagTeam::class)));
    }

    /** @test */
    public function a_tag_team_uses_the_manageable_trait()
    {
        $this->assertTrue(in_array(Manageable::class, class_uses(TagTeam::class)));
    }

    /** @test */
    public function a_tag_team_uses_the_hireable_trait()
    {
        $this->assertTrue(in_array(Hireable::class, class_uses(TagTeam::class)));
    }

    /** @test */
    public function a_tag_team_uses_the_presentable_trait()
    {
        $this->assertTrue(in_array(Presentable::class, class_uses(TagTeam::class)));
    }

    /** @test */
    public function a_tag_team_uses_the_soft_deletes_trait()
    {
        $this->assertTrue(in_array(SoftDeletes::class, class_uses(TagTeam::class)));
    }

    /** @test */
    public function it_can_get_a_tag_teams_current_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $wrestlerC = factory(Wrestler::class)->create();
        $tagteam = factory(TagTeam::class)->create();

        $tagteam->wrestlers()->attach([$wrestlerA->id => ['joined_on' => Carbon::now()], $wrestlerB->id => ['joined_on' => Carbon::now()]]);

        $this->assertTrue($tagteam->currentWrestlers->contains($wrestlerA));
        $this->assertTrue($tagteam->currentWrestlers->contains($wrestlerB));
        $this->assertFalse($tagteam->currentWrestlers->contains($wrestlerC));
    }

    /** @test */
    public function a_tag_team_can_add_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $wrestlerC = factory(Wrestler::class)->create();
        $tagteam = factory(TagTeam::class)->create();

        $tagteam->addWrestlers([$wrestlerA, $wrestlerB]);

        $this->assertTrue($tagteam->currentWrestlers->contains($wrestlerA));
        $this->assertTrue($tagteam->currentWrestlers->contains($wrestlerB));
        $this->assertFalse($tagteam->currentWrestlers->contains($wrestlerC));
    }

    /** @test */
    public function a_tag_team_can_sync_wrestlers()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $wrestlerC = factory(Wrestler::class)->create();
        $tagteam = factory(TagTeam::class)->create();

        $tagteam->addWrestlers([$wrestlerA, $wrestlerB]);

        $tagteam->syncWrestlers([$wrestlerA, $wrestlerC]);

        $this->assertTrue($tagteam->currentWrestlers->contains($wrestlerA));
        $this->assertFalse($tagteam->currentWrestlers->contains($wrestlerB));
        $this->assertTrue($tagteam->currentWrestlers->contains($wrestlerC));
    }

    /** @test */
    public function a_tag_team_hired_at_date_is_added_to_dates_array()
    {
        $tagteam = factory(TagTeam::class)->create();

        $this->assertTrue(in_array('hired_at', $tagteam->getDates()));
    }

    /** @test */
    public function a_tag_team_is_active_field_is_boolean_type_and_added_to_casts_array()
    {
        $tagteam = factory(TagTeam::class)->create();

        $this->assertTrue($tagteam->hasCast('is_active', 'boolean'));
    }
}
