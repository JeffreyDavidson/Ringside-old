<?php

namespace Tests\Unit;

use App\Models\Title;
use App\Models\Champion;
use App\Models\Wrestler;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TitleChampionsCollectionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestlers_titles_can_be_grouped()
    {
        $wrestler = factory(Wrestler::class)->create();
        $titleA = factory(Title::class)->create();
        $titleB = factory(Title::class)->create();
        factory(Champion::class)->create([
            'wrestler_id' => $wrestler->id,
            'title_id' => $titleA->id,
            'won_on' => Carbon::parse('2 days ago')
        ]);
        factory(Champion::class)->create([
            'wrestler_id' => $wrestler->id,
            'title_id' => $titleA->id,
            'won_on' => Carbon::parse('1 days ago')
        ]);
        factory(Champion::class)->create([
            'wrestler_id' => $wrestler->id,
            'title_id' => $titleB->id,
            'won_on' => Carbon::parse('today')
        ]);
    }
}
