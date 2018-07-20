<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Title;
use App\Models\Champion;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChampionTest extends TestCase
{
    use RefreshDatabase;

    protected $champion;

    public function setUp()
    {
        parent::setUp();

        $this->champion = factory(Champion::class)->create(['won_on' => Carbon::yesterday(), 'lost_on' => Carbon::today()]);
    }

    /** @test */
    public function a_champion_is_a_wrestler()
    {
        $this->assertInstanceOf(Wrestler::class, $this->champion->wrestler);
    }

    /** @test */
    public function a_champion_holds_a_title()
    {
        $this->assertInstanceOf(Title::class, $this->champion->title);
    }
}
