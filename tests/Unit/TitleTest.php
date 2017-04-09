<?php

namespace Tests\Unit;

use App\Exceptions\WrestlerCanNotBeHealedException;
use App\Wrestler;
use App\Manager;
use App\Title;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TitleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_title_can_only_be_defended_at_event_if_it_was_indroduced_before_the_event()
    {
        $title = factory(Title::class)->create(['introduced_at' => Carbon::parse('-3 weeks')]);

        $this->assertGreaterThanOrEqual($title->introduced_at, Carbon::now());
    }

}
