<?php

namespace Tests\Unit;

use App\Models\Champion;
use App\Models\Match;
use App\Models\Referee;
use App\Models\Stipulation;
use App\Models\Wrestler;
use Carbon\Carbon;
use stdClass;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChampionPresenterTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_champions_won_on_date_can_be_formatted()
    {
        $champion = factory(Champion::class)->create(['won_on' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $champion->present()->wonOn);
    }

    /** @test */
    public function a_former_champions_lost_on_date_can_be_formatted()
    {
        $champion = factory(Champion::class)->create(['lost_on' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $champion->present()->lostOn);
    }

    /** @test */
    public function a_current_champions_who_hasnt_lost_their_title_can_be_formatted()
    {
        $champion = factory(Champion::class)->create(['lost_on' => NULL]);

        $this->assertEquals('Present', $champion->present()->lostOn);
    }

    /** @test */
    public function a_champions_length_of_reign_can_be_formatted_in_a_readable_format()
    {
        $champion = factory(Champion::class)->create(['won_on' => Carbon::now()->subDays(200), 'lost_on' => Carbon::now()]);

        $this->assertEquals('200 days', $champion->present()->lengthOfReign);
    }

    /** @test */
    public function a_champions_length_of_reign_that_hasnt_lost_there_title_can_be_formatted_in_a_readable_format()
    {
        $champion = factory(Champion::class)->create(['won_on' => Carbon::now()->subDays(200), 'lost_on' => NULL]);

        $this->assertEquals('Present', $champion->present()->lengthOfReign);
    }
}
