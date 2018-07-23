<?php

namespace Tests\Unit\Repositories;

use App\Models\Title;
use Facades\ChampionshipFactory;
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
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $wrestlerC = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        ChampionshipFactory::forWrestler($wrestlerA)->forTitle($title)->withSuccessfulTitleDefenses(3)->create();
        ChampionshipFactory::forWrestler($wrestlerB)->forTitle($title)->withSuccessfulTitleDefenses(6)->create();
        ChampionshipFactory::forWrestler($wrestlerC)->forTitle($title)->withSuccessfulTitleDefenses(5)->create();

        $champions = $this->repository->mostTitleDefenses($title);

        $this->assertTrue($champions->pluck('wrestler_id')->contains($wrestlerB->id));
    }

    /** @test */
    public function it_can_get_the_champion_with_the_most_title_reigns()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $wrestlerC = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        ChampionshipFactory::forWrestler($wrestlerA)->forTitle($title)->create();
        ChampionshipFactory::forWrestler($wrestlerB)->forTitle($title)->create();
        ChampionshipFactory::forWrestler($wrestlerA)->forTitle($title)->create();
        ChampionshipFactory::forWrestler($wrestlerC)->forTitle($title)->create();
        ChampionshipFactory::forWrestler($wrestlerA)->forTitle($title)->create();

        $wrestlers = $this->repository->mostTitleReigns($title);

        $this->assertTrue($wrestlers->contains($wrestlerA->id));
    }

    /** @test */
    public function it_can_get_the_champion_with_the_longest_title_reign()
    {
        $wrestlerA = factory(Wrestler::class)->create();
        $wrestlerB = factory(Wrestler::class)->create();
        $wrestlerC = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        ChampionshipFactory::forWrestler($wrestlerA)->forTitle($title)->wonOn(Carbon::parse('2018-01-01'))->lostOn(Carbon::parse('2018-01-10'))->create();
        ChampionshipFactory::forWrestler($wrestlerB)->forTitle($title)->wonOn(Carbon::parse('2018-01-10'))->lostOn(Carbon::parse('2018-01-19'))->create();
        ChampionshipFactory::forWrestler($wrestlerC)->forTitle($title)->wonOn(Carbon::parse('2018-01-19'))->lostOn(Carbon::parse('2018-01-20'))->create();

        $champions = $this->repository->longestTitleReign($title);

        $this->assertTrue($champions->pluck('wrestler_id')->contains($wrestlerA->id));
        $this->assertTrue($champions->pluck('wrestler_id')->contains($wrestlerB->id));
    }
}
