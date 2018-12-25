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
use Illuminate\Database\Eloquent\SoftDeletes;

class TagTeamTest extends IntegrationTestCase
{
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
}
