<?php

namespace Tests\Feature\Unit;

use EventFactory;
use MatchFactory;
use Tests\TestCase;
use App\Models\Title;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HasMatchesTraitTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestler_with_matches_before_current_date_has_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();
        $event = EventFactory::create(['date' => '2017-10-09']);
        $match = MatchFactory::create(['event_id' => $event->id], [$wrestler], [], []);

        $this->assertTrue($wrestler->hasPastMatches());
        $this->assertEquals(1, $wrestler->pastMatches()->count());
        $this->assertEquals('2017-10-09', $wrestler->firstMatchDate()->toDateString());
    }

    /** @test */
    public function a_wrestler_without_matches_before_current_date_has_no_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertFalse($wrestler->hasPastMatches());
    }

    /** @test */
    public function a_title_with_matches_before_current_date_has_past_matches()
    {
        $title = factory(Title::class)->create();
        $event = EventFactory::create(['date' => '2017-10-09']);
        $match = MatchFactory::create(['event_id' => $event->id], [], [], [$title]);

        $this->assertTrue($title->hasPastMatches());
        $this->assertEquals(1, $title->pastMatches()->count());
        $this->assertEquals('2017-10-09', $title->firstMatchDate()->toDateString());
    }

    /** @test */
    public function a_title_without_matches_before_current_date_has_no_past_matches()
    {
        $title = factory(Title::class)->create();

        $this->assertFalse($title->hasPastMatches());
    }
}
