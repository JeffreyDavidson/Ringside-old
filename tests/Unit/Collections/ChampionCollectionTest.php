<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Champion;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Collections\ChampionCollection;

class ChampionCollectionTest extends TestCase
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
        factory(Champion::class, 2)->create(['title_id' => $titleA->id]);
        factory(Champion::class, 4)->create(['title_id' => $titleB->id]);

        $groups = Champion::all()->groupByTitle();

        $this->assertCount(2, $groups[$titleA->id]);
        $this->assertCount(4, $groups[$titleB->id]);
    }
}
