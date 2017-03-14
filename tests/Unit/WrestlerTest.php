<?php

namespace Tests\Unit;

use App\TitleHistory;
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
    public function can_get_formatted_height()
    {
        $wrestler = factory(Wrestler::class)->make(['height' => '73']);

        $this->assertEquals('6\'1"', $wrestler->formatted_height);
    }

    /** @test */
    public function can_hire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager);

        $wrestler = Wrestler::with('currentManagers')->find($wrestler->id);

        $this->assertEquals($wrestler->currentManagers->first()->id, $manager->id);
    }

    /** @test */
    public function can_fire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();
        $wrestler->hireManager($manager);
        $wrestler->fireManager($manager);
        Carbon::setTestNow(Carbon::parse('+1 day'));

        $wrestler = Wrestler::with('previousManagers')->find($wrestler->id);

        $this->assertEquals($wrestler->previousManagers()->first()->id, $manager->id);

        Carbon::setTestNow();
    }

    /** @test * */
    public function can_win_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title);

        $this->assertNotNull($wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::today()->toDateString())->first());
    }

    /** @test * */
    public function can_lose_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title);

        Carbon::setTestNow(Carbon::parse('+1 day'));

        $wrestler->loseTitle($title);

        Carbon::setTestNow();

        $this->assertNotNull($wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::today()->toDateString())->first()->pivot->lost_on);
    }

}
