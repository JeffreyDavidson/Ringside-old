<?php

namespace Tests\Unit\Repositories;

use App\Models\Title;
use App\Models\Champion;
use App\Models\Wrestler;
use Carbon\Carbon;
use Tests\TestCase;
use App\Repositories\ChampionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => today()->subMonth()]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => today()->subMonths(2)]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerB->id, 'title_id' => $title->id, 'won_on' => today()->subMonths(3)]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerC->id, 'title_id' => $title->id, 'won_on' => today()->subMonths(4)]);
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
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler A']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler B']);
        $wrestlerC = factory(Wrestler::class)->create(['name' => 'Wrestler C']);
        $title = factory(Title::class)->create();

        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'successful_defenses' => 3]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerB->id, 'title_id' => $title->id, 'successful_defenses' => 6]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerC->id, 'title_id' => $title->id, 'successful_defenses' => 5]);

        $champion = ChampionRepository::mostTitleDefenses($title);

        $this->assertEquals($wrestlerB->id, $champion->wrestler->id);
    }
}
