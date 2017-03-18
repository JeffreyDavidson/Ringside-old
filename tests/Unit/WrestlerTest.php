<?php

namespace Tests\Unit;

use App\Wrestler;
use App\Manager;
use App\Title;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WrestlerTest extends TestCase
{
    use DatabaseMigrations;

    protected $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->wrestler = factory(Wrestler::class)->create();
    }

    /** @test */
    public function it_can_get_formatted_height()
    {
        $wrestler = factory(Wrestler::class)->make(['height' => '73']);

        $this->assertEquals('6\'1"', $wrestler->formatted_height);
    }

    /** @test */
    public function it_can_have_managers()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->wrestler->managers);
    }

    /** @test */
    public function it_can_hire_a_manager()
    {
        $manager = factory(Manager::class)->create();

        $this->wrestler->hireManager($manager);

        $this->assertEquals($this->wrestler->currentManagers->first()->id, $manager->id);
    }

    /** @test */
    public function it_can_have_titles()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->wrestler->titles);
    }

    /** @test */
    public function it_can_fire_a_manager()
    {
        $manager = factory(Manager::class)->create();
        $this->wrestler->hireManager($manager);
        $this->wrestler->fireManager($manager);
        Carbon::setTestNow(Carbon::parse('+1 day'));

        $this->assertEquals($this->wrestler->previousManagers()->first()->id, $manager->id);

        Carbon::setTestNow();
    }

    /** @test * */
    public function it_can_win_a_title()
    {
        $title = factory(Title::class)->create();

        $this->wrestler->winTitle($title);

        $this->assertNotNull($this->wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::today()->toDateString())->first());
    }

    /** @test * */
    public function it_can_lose_a_title()
    {
        $title = factory(Title::class)->create();

        $this->wrestler->winTitle($title);

        Carbon::setTestNow(Carbon::parse('+1 day'));

        $this->wrestler->loseTitle($title);

        Carbon::setTestNow();

        $this->assertNotNull($this->wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::today()->toDateString())->first()->pivot->lost_on);
    }

    /** @test * */
    public function it_can_show_grouped_titles()
    {
        $title1 = factory(Title::class)->create();
        $title2 = factory(Title::class)->create();

        $this->wrestler->winTitle($title1);

        Carbon::setTestNow(Carbon::parse('+1 day'));

        $this->wrestler->loseTitle($title1);
        $this->wrestler->winTitle($title2);

        Carbon::setTestNow(Carbon::parse('+2 day'));

        $this->wrestler->loseTitle($title2);
        $this->wrestler->winTitle($title1);

        Carbon::setTestNow();

//        dd($this->wrestler->groupedTitles()->count());

        $this->assertEquals(2, $this->wrestler->groupedTitles()->first()->count());
        $this->assertEquals(1, $this->wrestler->groupedTitles()->last()->count());
    }

}
