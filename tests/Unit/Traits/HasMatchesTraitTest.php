<?php

namespace Tests\Feature\Unit;

use Tests\TestCase;
use App\Models\Title;
use App\Models\Wrestler;
use App\Models\Event;
use App\Models\Match;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasMatchesTraitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_with_matches_before_the_current_date_has_past_matches()
    {
        $wrestler = factory(Wrestler::class)->create();
        $event = factory(Event::class)->create(['date' => '2017-10-09']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addWrestler($wrestler);

        $this->assertTrue($wrestler->hasPastMatches());
        $this->assertEquals(1, $wrestler->pastMatches->count());
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
        $event = factory(Event::class)->create(['date' => '2017-10-09']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $match->addTitle($title);

        $this->assertTrue($title->hasPastMatches());
        $this->assertEquals(1, $title->pastMatches->count());
        $this->assertEquals('2017-10-09', $title->firstMatchDate()->toDateString());
    }

    /** @test */
    public function a_title_without_matches_before_current_date_has_no_past_matches()
    {
        $title = factory(Title::class)->create();

        $this->assertFalse($title->hasPastMatches());
    }
}
