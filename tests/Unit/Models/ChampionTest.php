<?php

namespace Tests\Unit;

use App\Models\Champion;
use App\Models\Title;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChampionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_title_can_belong_to_many_wrestlers()
    {
        $title = factory(Title::class)->create();
        factory(Champion::class)->create(['title_id' => $title->id, 'wrestler_id' => 1, 'won_on' => '2017-01-16', 'lost_on' => '2017-02-16']);
        factory(Champion::class)->create(['title_id' => $title->id, 'wrestler_id' => 2, 'won_on' => '2017-02-16', 'lost_on' => '2017-03-16']);
        factory(Champion::class)->create(['title_id' => $title->id, 'wrestler_id' => 3, 'won_on' => '2017-03-16', 'lost_on' => '2017-04-16']);

        //dd($title->champions);
        $this->assertCount(3, $title->champions()->count());
    }
}
