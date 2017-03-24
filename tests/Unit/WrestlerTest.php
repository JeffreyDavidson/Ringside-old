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

    /** @test */
    public function it_can_get_formatted_height()
    {
        $wrestler = make(Wrestler::class, ['height' => '73']);

        $this->assertEquals('6\'1"', $wrestler->formatted_height);
    }

    /** @test */
    public function it_can_have_managers()
    {
        $wrestler = create(Wrestler::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->managers);
    }

    /** @test */
    public function it_can_hire_a_manager()
    {
        $wrestler = create(Wrestler::class);
        $manager = create(Manager::class);

        $wrestler->hireManager($manager);

        $this->assertEquals($wrestler->currentManagers->first()->id, $manager->id);
    }

    /** @test */
    public function it_can_fire_a_manager()
    {
        $wrestler = create(Wrestler::class);
        $manager = create(Manager::class);

        $wrestler->hireManager($manager);
        $wrestler->fireManager($manager);

        Carbon::setTestNow(Carbon::parse('+1 day'));

        $this->assertEquals($wrestler->previousManagers()->first()->id, $manager->id);

        Carbon::setTestNow();
    }

    /** @test */
    public function it_can_have_titles()
    {
        $wrestler = create(Wrestler::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->titles);
    }

    /** @test * */
    public function it_can_win_a_title()
    {
        $wrestler = create(Wrestler::class);
        $title = create(Title::class);

        $wrestler->winTitle($title);

        $this->assertNotNull($wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::today()->toDateString())->first());
    }

    /** @test * */
    public function it_can_lose_a_title()
    {
        $wrestler = create(Wrestler::class);
        $title = create(Title::class);

        $wrestler->winTitle($title);

        Carbon::setTestNow(Carbon::parse('+1 day'));

        $wrestler->loseTitle($title);

        Carbon::setTestNow();

        $this->assertNotNull($wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::today()->toDateString())->first()->pivot->lost_on);
    }

    /** @test * */
    public function it_can_show_titles_won()
    {
        $wrestler = create(Wrestler::class);
        $title1 = create(Title::class);
        $title2 = create(Title::class);

        $wrestler->winTitle($title1);

        Carbon::setTestNow(Carbon::parse('+1 day'));

        $wrestler->loseTitle($title1);
        $wrestler->winTitle($title2);

        Carbon::setTestNow(Carbon::parse('+2 day'));

        $wrestler->loseTitle($title2);
        $wrestler->winTitle($title1);

        Carbon::setTestNow();

        $this->assertEquals(2, $wrestler->groupedTitles()->first()->count());
        $this->assertEquals(1, $wrestler->groupedTitles()->last()->count());
    }

}
