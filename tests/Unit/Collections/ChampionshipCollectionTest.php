<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Championship;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Collections\ChampionshipCollection;

class ChampionshipCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_all_champions_grouped_by_title()
    {
        $titleA = factory(Title::class)->create();
        $titleB = factory(Title::class)->create();
        factory(Championship::class, 2)->create(['title_id' => $titleA->id]);
        factory(Championship::class, 4)->create(['title_id' => $titleB->id]);

        $groups = Championship::all()->groupByTitle();

        $this->assertCount(2, $groups[$titleA->id]);
        $this->assertCount(4, $groups[$titleB->id]);
    }
}
