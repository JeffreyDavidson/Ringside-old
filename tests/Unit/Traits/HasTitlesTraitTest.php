<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\Title;
use Carbon\Carbon;
use Facades\ChampionshipFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasTitlesTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_can_win_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title, Carbon::now());

        $this->assertTrue($wrestler->isCurrentlyAChampion());
        $this->assertTrue($wrestler->hasTitle($title));
    }

    /** @test */
    public function a_wrestler_can_have_multiple_titles_at_the_same_time()
    {
        $wrestler = factory(Wrestler::class)->create();
        $titleA = factory(Title::class)->create();
        $titleB = factory(Title::class)->create();

        $wrestler->winTitle($titleA, Carbon::now());
        $wrestler->winTitle($titleB, Carbon::now());

        $this->assertEquals(2, $wrestler->currentTitlesHeld()->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerAlreadyHasTitleException
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
    public function current_titles_held_returns_a_collection_of_active_titles()
    {
        $wrestler = factory(Wrestler::class)->create();
        $currentChampionshipA = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::today()->subMonths(2))->create();
        $currentChampionshipB = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::yesterday())->create();
        $pastChampionship = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::today()->subDays(4))->lostOn(Carbon::yesterday())->create();

        $currentTitlesHeld = $wrestler->currentTitlesHeld();

        $this->assertTrue($currentTitlesHeld->contains('id', $currentChampionshipA->title_id));
        $this->assertTrue($currentTitlesHeld->contains('id', $currentChampionshipB->title_id));
        $this->assertFalse($currentTitlesHeld->contains('id', $pastChampionship->title_id));
    }

    /** @test */
    public function past_titles_held_returns_a_collection_of_past_titles()
    {
        $wrestler = factory(Wrestler::class)->create();
        $pastChampionshipA = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::today()->subMonths(2))->lostOn(Carbon::today()->subMonths(1))->create();
        $pastChampionshipB = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::today()->subWeeks(3))->lostOn(Carbon::today()->subWeeks(2))->create();
        $currentChampionship = ChampionshipFactory::forWrestler($wrestler)->wonOn(Carbon::yesterday())->create();

        $pastTitlesHeld = $wrestler->pastTitlesHeld();

        $this->assertTrue($pastTitlesHeld->contains('id', $pastChampionshipA->title_id));
        $this->assertTrue($pastTitlesHeld->contains('id', $pastChampionshipB->title_id));
        $this->assertFalse($pastTitlesHeld->contains('id', $currentChampionship->title_id));
    }

    /** @test */
    public function a_wrestler_can_hold_many_titles()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->championships);
    }
}
