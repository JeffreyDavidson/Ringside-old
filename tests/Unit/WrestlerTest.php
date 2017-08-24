<?php

namespace Tests\Unit;

use App\Exceptions\WrestlerCanNotBeHealedException;
use App\Exceptions\WrestlerCanNotBeInjuredException;
use App\Models\Wrestler;
use App\Models\Manager;
use App\Models\Title;
use App\Models\WrestlerStatus;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WrestlerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_have_many_managers()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager);

        $this->assertCount(1, $wrestler->managers);
    }

    /** @test */
    public function can_hire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager);

        $this->assertCount(1, $wrestler->managers);
    }

    /** @test */
    public function can_fire_a_manager()
    {
        $wrestler = factory(Wrestler::class)->create();
        $manager = factory(Manager::class)->create();

        $wrestler->hireManager($manager, Carbon::parse('yesterday'));
        $wrestler->fireManager($manager);

        $this->assertEquals(1, $wrestler->previousManagers()->count());
    }

    /** @test */
    public function can_have_injuries()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->injuries);
    }

    /** @test */
    public function can_have_titles()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->titles);
    }

    /** @test * */
    public function can_win_a_title()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title);

        $this->assertNotNull($wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::today()->toDateString())->first());
    }

    /** @test * */
    public function can_lose_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title, Carbon::parse('-3 days'));

        $this->assertNotNull($wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::parse('-3 days')->toDateString())->first());

        $wrestler->loseTitle($title);

        $this->assertNotNull($wrestler->titles()->where('title_id', $title->id)->whereDate('won_on', Carbon::parse('-3 days')->toDateString())->first()->lost_on);
    }

    /** @test */
    public function can_be_injured()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $this->assertCount(0, $wrestler->injuries);

        $wrestler->injure();

        $this->assertEquals(3, $wrestler->fresh()->status());
        $this->assertCount(1, $wrestler->fresh()->injuries);
    }

    /** @test * */
    public function it_can_count_number_of_times_a_wrestler_has_won_a_title()
    {
        $wrestler = factory(Wrestler::class)->create();
        $title = factory(Title::class)->create();

        $wrestler->winTitle($title, Carbon::parse('-3 days'));

        $wrestler->loseTitle($title, Carbon::parse('-3 days'));
        $wrestler->winTitle($title, Carbon::parse('-2 days'));

        $wrestler->loseTitle($title, Carbon::parse('-2 days'));  // Error

        $this->assertEquals(2, $wrestler->titles()->where('title_id', $title->id)->count());
    }

    /** @test */
    public function can_have_matches()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->matches);
    }

    /** @test */
    public function a_non_active_wrestler_cannot_be_injured()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => collect(WrestlerStatus::INACTIVE, WrestlerStatus::INJURED, WrestlerStatus::SUSPENDED, WrestlerStatus::RETIRED)->random()]);

        $this->assertCount(0, $wrestler->injuries);

        try {
            $wrestler->injure();
        } catch (WrestlerCanNotBeInjuredException $e) {
            $this->assertCount(0, $wrestler->fresh()->injuries);
            return;
        }

        $this->fail('This wrestler cannot be injured.');
    }

    /** @test */
    public function an_injured_wrestler_can_be_healed()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();
        $wrestler->injure();
        $this->assertCount(1, $wrestler->injuries);

        $wrestler->heal();

        $this->assertEquals(1, $wrestler->fresh()->status());
        $this->assertNotNull($wrestler->fresh()->injuries->last()->healed_at);
    }

    /** @test */
    public function a_wrestler_that_is_not_injured_cannot_be_healed()
    {
        $wrestler = factory(Wrestler::class)->create(['status_id' => collect(WrestlerStatus::ACTIVE, WrestlerStatus::INACTIVE, WrestlerStatus::SUSPENDED, WrestlerStatus::RETIRED)->random()]);
        $this->assertCount(0, $wrestler->injuries);

        try {
            $wrestler->heal();
        } catch (WrestlerCanNotBeHealedException $e) {
            return;
        }

        $this->fail('This wrestler is not currently injured.');

    }

    /** @test */
    public function a_wrestler_can_get_active_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $activeWrestlers = Wrestler::active()->get();

        $this->assertTrue($activeWrestlers->contains($wrestler));
    }

    /** @test */
    public function it_can_get_inactive_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $inactiveWrestlers = Wrestler::inactive()->get();

        $this->assertTrue($inactiveWrestlers->contains($wrestler));
    }

    /** @test */
    public function it_can_get_injured_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->injure();

        $injuredWrestlers = Wrestler::injured()->get();

        $this->assertTrue($injuredWrestlers->contains($wrestler));
    }

    /** @test */
    public function it_can_get_suspended_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $activeWrestlers = Wrestler::suspended()->get();

        $this->assertTrue($activeWrestlers->contains($wrestler));
    }

    /** @test */
    public function it_can_get_retired_wrestlers()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $retiredWrestlers = Wrestler::retired()->get();

        $this->assertTrue($retiredWrestlers->contains($wrestler));
    }

    /** @test */
    public function a_wrestlers_bio_can_format_its_height()
    {
        $bio = factory(Wrestler::class)->make(['height' => '73']);

        $this->assertEquals('6\'1"', $bio->height);
    }

}
