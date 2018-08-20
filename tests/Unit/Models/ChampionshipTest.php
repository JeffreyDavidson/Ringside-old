<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Title;
use App\Models\Championship;
use App\Models\Wrestler;
use Carbon\Carbon;
use App\Collections\ChampionshipCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChampionshipTest extends TestCase
{
    use RefreshDatabase;

    protected $championship;

    public function setUp()
    {
        parent::setUp();

        $this->championship = factory(Championship::class)->create(['won_on' => Carbon::yesterday(), 'lost_on' => Carbon::today()]);
    }

    /** @test */
    public function a_champion_is_a_wrestler()
    {
        $this->assertInstanceOf(Wrestler::class, $this->championship->wrestler);
    }

    /** @test */
    public function a_champion_holds_a_title()
    {
        $this->assertInstanceOf(Title::class, $this->championship->title);
    }

    /** @test */
    public function a_champion_can_lose_a_title()
    {
        $championship = factory(Championship::class)->create();

        $championship->loseTitle(Carbon::now());

        $this->assertNotNull($championship->fresh()->lost_on);
    }

    /** @test */
    public function it_returns_a_new_custom_championship_collection()
    {
        $this->assertInstanceOf(ChampionshipCollection::class, Championship::all());
    }
}
