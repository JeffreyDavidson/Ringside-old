<?php

namespace Tests\Unit;

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

    /** @test */
    public function it_can_retrieve_current_champions()
    {
        $titleA = factory(Title::class)->create();
        $titleB = factory(Title::class)->create();
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $wrestlerC = factory(Wrestler::class)->create();
        $wrestlerD = factory(Wrestler::class)->create();
        factory(Champion::class)->create(['title_id' => $titleA->id, 'wrestler_id' => $wrestlerA->id, 'won_on' => '2017-01-16', 'lost_on' => '2017-02-16']);
        factory(Champion::class)->create(['title_id' => $titleA->id, 'wrestler_id' => $wrestlerB->id, 'won_on' => '2017-02-16', 'lost_on' => NULL]);
        factory(Champion::class)->create(['title_id' => $titleB->id, 'wrestler_id' => $wrestlerC->id, 'won_on' => '2017-03-16', 'lost_on' => '2017-04-16']);
        factory(Champion::class)->create(['title_id' => $titleB->id, 'wrestler_id' => $wrestlerD->id, 'won_on' => '2017-04-16', 'lost_on' => NULL]);

        $currentChampions = Champion::current()->get();

        $this->assertEquals(2, $currentChampions->count());
        $this->assertTrue($currentChampions->contains('wrestler_id', $wrestlerB->id));
        $this->assertTrue($currentChampions->contains('wrestler_id', $wrestlerD->id));
        $this->assertFalse($currentChampions->contains('wrestler_id', $wrestlerA->id));
        $this->assertFalse($currentChampions->contains('wrestler_id', $wrestlerC->id));
    }
}
