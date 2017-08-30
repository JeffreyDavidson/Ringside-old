<?php

namespace Tests\Unit;

use App\Models\Title;
use App\Models\Wrestler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HasTitlesTraitTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestler_can_win_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title);

        $this->assertTrue($wrestler->isCurrentlyAChampion());
        $this->assertTrue($wrestler->fresh()->hasTitle($title));
    }

    /** @test */
    public function a_wrestler_can_lose_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title);
        $wrestler->loseTitle($title);

        $this->assertTrue($wrestler->hasPreviousTitlesHeld());
        $this->assertEquals(1, $wrestler->previousTitlesHeld->count());
    }

    /** @test */
    public function a_wrestler_can_have_multiple_titles_at_the_same_time()
    {
        $wrestler = factory(Wrestler::class)->create();
        $titleA = factory(Title::class)->create();
        $titleB = factory(Title::class)->create();

        $wrestler->winTitle($titleA);
        $wrestler->winTitle($titleB);

        $this->assertEquals(2, $wrestler->currentTitlesHeld()->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerIsTitleChampionException
     *
     * @test */
    public function a_wrestler_who_who_has_a_title_cannot_win_the_same_title_without_losing_it()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();
        $wrestler->winTitle($title);

        $wrestler->winTitle($title);

        $this->assertEquals(1, $wrestler->currentTitles->count());
    }

    /**
     * @expectedException \App\Exceptions\WrestlerNotSuspendedException
     *
     * @test
     */
    public function a_wrestler_who_is_not_suspended_cannot_be_renewed()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->renew();

        $this->assertEquals(0, $wrestler->retirements->count());
    }
}
