<?php

namespace Tests\Unit\Traits;

use Carbon\Carbon;
use App\Models\Title;
use Facades\MatchFactory;
use Tests\IntegrationTestCase;
use App\Models\Roster\Wrestler;
use App\Traits\CompetitorTrait;
use Facades\ChampionshipFactory;

class CompetitorTraitTest extends IntegrationTestCase
{
    /** @test */
    public function a_wrestler_can_win_a_title()
    {
        $competitor = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title, Carbon::now());

        $this->assertTrue($wrestler->isCurrentlyAChampion());
        $this->assertTrue($wrestler->hasTitle($title));
    }

    /**
     * @expectedException \App\Exceptions\ModelNotTitleChampionException
     *
     * @test
     */
    public function a_wrestler_who_does_not_have_a_current_title_cannot_lose_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->loseTitle($title, Carbon::now());
    }

    /** @test */
    public function current_titles_held_returns_a_collection_of_active_titles()
    {
        $wrestler = factory(Wrestler::class)->create();
        $currentChampionshipA = ChampionshipFactory::current()->forWrestler($wrestler)->create();
        $currentChampionshipB = ChampionshipFactory::current()->forWrestler($wrestler)->create();
        $pastChampionship = ChampionshipFactory::past()->forWrestler($wrestler)->create();

        $currentTitlesHeld = $wrestler->currentTitlesHeld()->get();

        $this->assertTrue($currentTitlesHeld->contains('id', $currentChampionshipA->title_id));
        $this->assertTrue($currentTitlesHeld->contains('id', $currentChampionshipB->title_id));
        $this->assertFalse($currentTitlesHeld->contains('id', $pastChampionship->title_id));
    }

    /** @test */
    public function past_titles_held_returns_a_collection_of_past_titles()
    {
        $wrestler = factory(Wrestler::class)->create();
        $pastChampionshipA = ChampionshipFactory::past()->forWrestler($wrestler)->create();
        $pastChampionshipB = ChampionshipFactory::past()->forWrestler($wrestler)->create();
        $currentChampionship = ChampionshipFactory::current()->forWrestler($wrestler)->create();

        $pastTitlesHeld = $wrestler->pastTitlesHeld()->get();

        $this->assertTrue($pastTitlesHeld->contains('id', $pastChampionshipA->title_id));
        $this->assertTrue($pastTitlesHeld->contains('id', $pastChampionshipB->title_id));
        $this->assertFalse($pastTitlesHeld->contains('id', $currentChampionship->title_id));
    }

    /**
     * @expectedException \App\Exceptions\ModelIsTitleChampionException
     *
     * @test
     */
    public function a_wrestler_who_has_a_title_cannot_win_the_same_title_without_losing_it()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();
        $wrestler->winTitle($title, Carbon::yesterday());

        $wrestler->winTitle($title, Carbon::now());
    }

    /** @test */
    public function it_can_retrieve_a_wrestlers_scheduled_matches()
    {
        $wrestler = factory(Wrestler::class)->create();
        $scheduledMatchA = MatchFactory::scheduled()->withCompetitor($wrestler)->create();
        $scheduledMatchB = MatchFactory::scheduled()->withCompetitor($wrestler)->create();
        $pastMatch = MatchFactory::past()->withCompetitor($wrestler)->create();

        $scheduledMatches = $wrestler->scheduledMatches()->get();

        $this->assertTrue($scheduledMatches->contains($scheduledMatchA));
        $this->assertTrue($scheduledMatches->contains($scheduledMatchB));
        $this->assertFalse($scheduledMatches->contains($pastMatch));
    }

    /** @test */
    public function a_wrestler_without_matches_before_current_date_has_no_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertFalse($wrestler->hasPastMatches());
    }

    /** @test */
    public function it_can_retrieve_a_wrestlers_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();
        $pastMatchA = MatchFactory::past()->withCompetitor($wrestler)->create();
        $pastMatchB = MatchFactory::past()->withCompetitor($wrestler)->create();
        $scheduledMatch = MatchFactory::scheduled()->withCompetitor($wrestler)->create();

        $pastMatches = $wrestler->pastMatches()->get();

        $this->assertTrue($pastMatches->contains($pastMatchA));
        $this->assertTrue($pastMatches->contains($pastMatchB));
        $this->assertFalse($pastMatches->contains($scheduledMatch));
    }
}
