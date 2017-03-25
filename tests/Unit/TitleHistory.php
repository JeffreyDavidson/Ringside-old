<?php

namespace Tests\Unit;

use App\TitleHistory;
use App\Wrestler;
use App\Title;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TitleHistory extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_a_wrestler()
    {
        $titleHistory = create(TitleHistory::class);

        $this->assertInstanceOf(App\Wrestler::class, $titleHistory->wrestler);
    }

    /** @test */
    public function it_belongs_to_a_title()
    {
        $titleHistory = create(TitleHistory::class);

        $this->assertInstanceOf(App\Title::class, $titleHistory->title);
    }
}
