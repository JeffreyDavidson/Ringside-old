<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\Title;
use Carbon\Carbon;
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
    public function a_wrestler_who_is_the_title_champion_can_lose_the_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title, Carbon::yesterday());
        $wrestler->loseTitle($title, Carbon::now());

        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertTrue($wrestler->hasPastTitlesHeld());
            $this->assertEquals(1, $wrestler->pastTitlesHeld->count());
        });
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

        $this->assertEquals(1, $wrestler->currentTitles->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotTitleChampionException
     *
     * @test
     */
    public function a_wrestler_who_does_not_have_the_title_cannot_lose_the_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->loseTitle($title, Carbon::now());

        $this->assertEquals(0, $wrestler->pastTitlesHeld->count());
    }
}