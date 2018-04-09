<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Champion;
use App\Models\Wrestler;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\WrestlerNotTitleChampionException;
use App\Repositories\ChampionRepository;
use Carbon\Carbon;

class ChampionRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_the_champion_with_the_most_title_reigns()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $wrestlerC = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();
;
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => today()]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => today()->addMonth()]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => today()->addMonths(2)]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerB->id, 'title_id' => $title->id, 'won_on' => today()->addMonths(3)]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerC->id, 'title_id' => $title->id, 'won_on' => today()->addMonths(4)]);

        $champion = ChampionRepository::mostTitleReigns($title);

        $this->assertEquals($wrestlerA->id, $champion->wrestler->id);
    }

    /** @test */
    public function it_can_get_the_champion_with_the_longest_title_reign()
    {
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler 1']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler 2']);
        $wrestlerC = factory(Wrestler::class)->create(['name' => 'Wrestler 3']);
        $title = factory(Title::class)->create();
;
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => Carbon::parse('2018-01-03'), 'lost_on' => Carbon::parse('2018-01-05')]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerB->id, 'title_id' => $title->id, 'won_on' => Carbon::parse('2018-01-05'), 'lost_on' => Carbon::parse('2018-01-10')]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerC->id, 'title_id' => $title->id, 'won_on' => Carbon::parse('2018-01-10'), 'lost_on' => Carbon::parse('2018-04-06')]);

        $champion = ChampionRepository::longestTitleReign($title);

        $this->assertEquals($wrestlerC->id, $champion->wrestler->id);
    }

    /** @test */
    public function it_can_get_the_champion_with_the_most_title_defenses()
    {
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler 1']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler 2']);
        $wrestlerC = factory(Wrestler::class)->create(['name' => 'Wrestler 3']);
        $title = factory(Title::class)->create();
;
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => Carbon::parse('2018-01-03'), 'lost_on' => Carbon::parse('2018-01-05')]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerB->id, 'title_id' => $title->id, 'won_on' => Carbon::parse('2018-01-05'), 'lost_on' => Carbon::parse('2018-01-10')]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerC->id, 'title_id' => $title->id, 'won_on' => Carbon::parse('2018-01-10'), 'lost_on' => Carbon::parse('2018-04-06')]);

        $champion = ChampionRepository::longestTitleReign($title);

        $this->assertEquals($wrestlerC->id, $champion->wrestler->id);
    }
}
