<?php

namespace Tests\Unit;

use App\Models\Champion;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChampionPresenterTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_champions_won_on_date_can_be_formatted()
    {
        $champion = factory(Champion::class)->create(['won_on' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $champion->present()->formattedWonOn);
    }

    /** @test */
    public function a_champions_who_has_lost_their_title_can_be_formatted()
    {
        $champion = factory(Champion::class)->create(['lost_on' => '2017-09-17']);

        $this->assertEquals('September 17, 2017', $champion->present()->formattedLostOn);
    }

    /** @test */
    public function a_champions_who_hasnt_lost_their_title_can_be_formatted()
    {
        $champion = factory(Champion::class)->create(['lost_on' => null]);

        $this->assertEquals('Present', $champion->present()->formattedLostOn);
    }

    /** @test */
    public function a_champions_length_of_reign_can_be_formatted_in_a_readable_format()
    {
        $champion = factory(Champion::class)->create(['won_on' => '2017-08-17', 'lost_on' => '2017-09-17']);

        $this->assertEquals('1 month', $champion->present()->lengthOfReign);
    }

    /** @test */
    public function a_champions_length_of_reign_that_hasnt_lost_there_title_can_be_formatted_in_a_readable_format()
    {
        $champion = factory(Champion::class)->create(['won_on' => '2017-08-17', 'lost_on' => null]);

        $this->assertEquals('1 month', $champion->present()->lengthOfReign);
    }
}
