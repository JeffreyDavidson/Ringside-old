<?php

namespace Tests\Unit;

use App\TitleHistory;
use App\Wrestler;
use App\Title;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TitleHistoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_titles_history_belongs_to_a_wrestler()
    {
        $titleHistory = factory(TitleHistory::class)->create();

        $this->assertInstanceOf(Wrestler::class, $titleHistory->wrestler);
    }

    /** @test */
    public function a_titles_history_belongs_to_a_title()
    {
        $titleHistory = factory(TitleHistory::class)->create();

        $this->assertInstanceOf(Title::class, $titleHistory->title);
    }
}
