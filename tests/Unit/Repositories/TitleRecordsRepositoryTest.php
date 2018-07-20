<?php

namespace Tests\Unit\Repositories;

use App\Models\Title;
use App\Models\Champion;
use App\Models\Wrestler;
use Carbon\Carbon;
use Tests\TestCase;
use App\Repositories\TitleRecordsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TitleRecordsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private $repository;

    public function setUp()
    {
        parent::setUp();
        // $this->repository = app(TitleRecordsRepository::class);
        $this->repository = new TitleRecordsRepository();
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

        $champions = $this->repository->mostTitleDefenses($title);

        $this->assertTrue($champions->pluck('wrestler_id')->contains($wrestlerB->id));
    }

    /** @test */
    public function it_can_get_the_champion_with_the_most_title_reigns()
    {
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler A']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler B']);
        $wrestlerC = factory(Wrestler::class)->create(['name' => 'Wrestler C']);
        $title = factory(Title::class)->create();
;
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => today()]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerB->id, 'title_id' => $title->id, 'won_on' => today()->subMonth()]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => today()->subMonths(2)]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerB->id, 'title_id' => $title->id, 'won_on' => today()->subMonths(3)]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => today()->subMonths(3)]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerC->id, 'title_id' => $title->id, 'won_on' => today()->subMonths(4)]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => today()->subMonths(5)]);

        $wrestlers = $this->repository->mostTitleReigns($title);

        $this->assertTrue($wrestlers->contains($wrestlerA->id));
    }

    /** @test */
    public function it_can_get_the_champion_with_the_longest_title_reign()
    {
        $wrestlerA = factory(Wrestler::class)->create(['name' => 'Wrestler 1']);
        $wrestlerB = factory(Wrestler::class)->create(['name' => 'Wrestler 2']);
        $wrestlerC = factory(Wrestler::class)->create(['name' => 'Wrestler 3']);
        $title = factory(Title::class)->create();

        factory(Champion::class)->create(['wrestler_id' => $wrestlerA->id, 'title_id' => $title->id, 'won_on' => Carbon::parse('2018-01-03'), 'lost_on' => Carbon::parse('2018-01-05')]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerB->id, 'title_id' => $title->id, 'won_on' => Carbon::parse('2017-10-16'), 'lost_on' => Carbon::parse('2018-01-10')]);
        factory(Champion::class)->create(['wrestler_id' => $wrestlerC->id, 'title_id' => $title->id, 'won_on' => Carbon::parse('2018-01-10'), 'lost_on' => Carbon::parse('2018-04-06')]);

        $champions = $this->repository->longestTitleReign($title);

        $this->assertTrue($champions->pluck('wrestler_id')->contains($wrestlerC->id));
    }
}
