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
    public function a_wrestler_can_win_a_title()
    {
        $wrestler = create(Wrestler::class);
        $title = create(Title::class);

        $wrestler->winTitle($title);

        $this->assertNotNull($wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::today()->toDateString())->first());
    }

    /** @test * */
    public function a_wrestler_can_lose_a_title()
    {
        $wrestler = create(Wrestler::class);
        $title = create(Title::class);

        $wrestler->winTitle($title);

        Carbon::setTestNow(Carbon::parse('+1 day'));

        $wrestler->loseTitle($title);

        Carbon::setTestNow();

        $this->assertNotNull($wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::today()->toDateString())->first()->lost_on);
    }

    /** @test * */
    public function it_can_count_number_of_times_a_wrestler_has_won_a_title()
    {
        $wrestler = create(Wrestler::class);
        $title = create(Title::class);

        $wrestler->winTitle($title);

        Carbon::setTestNow(Carbon::parse('+1 day'));

        $wrestler->loseTitle($title);
        $wrestler->winTitle($title);

        Carbon::setTestNow(Carbon::parse('+2 day'));

        $wrestler->loseTitle($title);
        $wrestler->winTitle($title);

        Carbon::setTestNow(Carbon::parse('+3 day'));

        $wrestler->loseTitle($title);

        Carbon::setTestNow();

        $this->assertEquals(3, $wrestler->titles()->where('title_id', $title->id)->count());
    }

    /** @test */
    public function it_can_have_matches()
    {
        $wrestler = create(Wrestler::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->matches);
    }

}
