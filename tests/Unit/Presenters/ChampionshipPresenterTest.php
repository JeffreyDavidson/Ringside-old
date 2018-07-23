<?php

namespace Tests\Unit\Presenters;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Championship;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChampionshipPresenterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_champions_won_on_date_can_be_formatted()
    {
        $champion = factory(Championship::class)->create(['won_on' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $champion->present()->wonOn);
    }

    /** @test */
    public function a_former_champions_lost_on_date_can_be_formatted()
    {
        $champion = factory(Championship::class)->create(['lost_on' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $champion->present()->lostOn);
    }

    /** @test */
    public function a_current_champions_who_hasnt_lost_their_title_can_be_formatted()
    {
        $champion = factory(Championship::class)->create(['lost_on' => null]);

        $this->assertEquals('Present', $champion->present()->lostOn);
    }

    /** @test */
    public function a_champions_length_of_reign_can_be_formatted_in_a_readable_format()
    {
        $champion = factory(Championship::class)->create(['won_on' => Carbon::now()->subDays(200), 'lost_on' => Carbon::now()]);

        $this->assertEquals('200 days', $champion->present()->lengthOfReign);
    }

    /** @test */
    public function a_champions_length_of_reign_that_hasnt_lost_their_title_can_be_formatted_in_a_readable_format()
    {
        $champion = factory(Championship::class)->create(['won_on' => Carbon::now()->subDays(200), 'lost_on' => null]);

        $this->assertEquals('Present', $champion->present()->lengthOfReign);
    }
}
