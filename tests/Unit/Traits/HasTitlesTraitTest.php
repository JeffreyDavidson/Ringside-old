<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Wrestler;
use App\Models\Title;
use Carbon\Carbon;
use TitleFactory;
use PHPUnit\Framework\Assert;
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
            $this->assertEquals(1, $wrestler->pastTitlesHeld()->count());
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

        $this->assertEquals(1, $wrestler->currentTitlesHeld()->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotTitleChampionException
     *
     * @test
     */
    public function a_wrestler_who_does_not_have_a_title_cannot_lose_the_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->loseTitle($title, Carbon::now());

        $this->assertEquals(0, $wrestler->pastTitlesHeld()->count());
    }

    /** @test */
    public function current_titles_held_returns_a_collection_of_active_titles()
    {
        $wrestler = factory(Wrestler::class)->create();
        $currentTitleA = TitleFactory::createReignForWrestlerBetweenDates($wrestler, Carbon::today()->subMonths(2), NULL);
        $currentTitleB = TitleFactory::createReignForWrestlerBetweenDates($wrestler, Carbon::yesterday(), NULL);
        $pastTitle = TitleFactory::createReignForWrestlerBetweenDates($wrestler, Carbon::today()->subDays(4), Carbon::yesterday());

        $currentTitlesHeld = $wrestler->currentTitlesHeld();

        $this->assertTrue($currentTitlesHeld->contains('id', $currentTitleA->id));
        $this->assertTrue($currentTitlesHeld->contains('id', $currentTitleB->id));
        $this->assertFalse($currentTitlesHeld->contains('id', $pastTitle->id));
    }

    /** @test */
    public function past_titles_held_returns_a_collection_of_past_titles()
    {
        $wrestler = factory(Wrestler::class)->create();
        $pastTitleA = TitleFactory::createReignForWrestlerBetweenDates($wrestler, Carbon::today()->subMonths(2), Carbon::today()->subMonths(1));
        $pastTitleB = TitleFactory::createReignForWrestlerBetweenDates($wrestler, Carbon::today()->subWeeks(3), Carbon::today()->subWeeks(2));
        $currentTitle = TitleFactory::createReignForWrestlerBetweenDates($wrestler, Carbon::yesterday(), NULL);

        $pastTitlesHeld = $wrestler->pastTitlesHeld();

        $this->assertTrue($pastTitlesHeld->contains('id', $pastTitleA->id));
        $this->assertTrue($pastTitlesHeld->contains('id', $pastTitleB->id));
        $this->assertFalse($pastTitlesHeld->contains('id', $currentTitle->id));
    }
}