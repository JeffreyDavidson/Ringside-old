<?php

namespace Tests\Unit\Models;

use App\Models\Referee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefereeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_referees_hired_before_a_certain_date()
    {
        $refereeA = factory(Referee::class)->create(['hired_at' => Carbon::parse('2016-12-31')]);
        $refereeB = factory(Referee::class)->create(['hired_at' => Carbon::parse('2014-12-31')]);
        $refereeC = factory(Referee::class)->create(['hired_at' => Carbon::parse('2017-01-01')]);

        $hiredReferees = Referee::hiredBefore(Carbon::parse('2017-01-01'))->get();

        $this->assertTrue($hiredReferees->contains($refereeA));
        $this->assertTrue($hiredReferees->contains($refereeB));
        $this->assertFalse($hiredReferees->contains($refereeC));
    }

    /** @test */
    public function referees_are_marked_as_active_depending_on_hired_date()
    {
        $refereeA = factory(Referee::class)->create(['hired_at' => Carbon::today()->subDays(2)]);
        $refereeB = factory(Referee::class)->create(['hired_at' => Carbon::today()]);
        $refereeC = factory(Referee::class)->create(['hired_at' => Carbon::today()->addDays(2)]);

        $this->assertTrue($refereeA->is_active);
        $this->assertTrue($refereeB->is_active);
        $this->assertFalse($refereeC->is_active);
    }

    /** @test */
    public function it_can_get_active_referees()
    {
        $refereeA = factory(Referee::class)->states('active')->create();
        $refereeB = factory(Referee::class)->states('active')->create();
        $refereeC = factory(Referee::class)->states('inactive')->create();

        $activeReferees = Referee::active()->get();

        $this->assertTrue($activeReferees->contains($refereeA));
        $this->assertTrue($activeReferees->contains($refereeB));
        $this->assertFalse($activeReferees->contains($refereeC));
    }

    /** @test */
    public function it_can_get_inactive_referees()
    {
        $refereeA = factory(Referee::class)->states('inactive')->create();
        $refereeB = factory(Referee::class)->states('inactive')->create();
        $refereeC = factory(Referee::class)->states('active')->create();

        $inactiveReferees = Referee::inactive()->get();

        $this->assertTrue($inactiveReferees->contains($refereeA));
        $this->assertTrue($inactiveReferees->contains($refereeB));
        $this->assertFalse($inactiveReferees->contains($refereeC));
    }

    /** @test */
    public function an_inactive_referee_can_be_activated()
    {
        $referee = factory(Referee::class)->states('inactive')->create();

        $referee->activate();

        $this->assertTrue($referee->is_active);
    }

    /** @test */
    public function an_active_referee_can_be_deactivated()
    {
        $referee = factory(Referee::class)->states('active')->create();

        $referee->deactivate();

        $this->assertFalse($referee->is_active);
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_referee_cannot_be_activated()
    {
        $referee = factory(Referee::class)->states('active')->create();

        $referee->activate();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsInactiveException
     *
     * @test
     */
    public function an_inactive_referee_cannot_be_deactivated()
    {
        $referee = factory(Referee::class)->states('inactive')->create();

        $referee->deactivate();
    }

    /** @test */
    public function an_active_referee_can_retire()
    {
        $referee = factory(Referee::class)->states('active')->create();

        $referee->retire();

        $this->assertEquals(1, $referee->retirements->count());
        $this->assertFalse($referee->is_active);
        $this->assertTrue($referee->isRetired());
        $this->assertNull($referee->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_retired_referee_can_unretire()
    {
        $referee = factory(Referee::class)->states('retired')->create();

        $referee->unretire();

        $this->assertNotNull($referee->retirements()->first()->ended_at);
        $this->assertTrue($referee->is_active);
        $this->assertFalse($referee->isRetired());
    }

    /** @test */
    public function it_can_get_retired_referees()
    {
        $refereeA = factory(Referee::class)->states('retired')->create();
        $refereeB = factory(Referee::class)->states('retired')->create();
        $refereeC = factory(Referee::class)->states('active')->create();

        $retiredReferees = Referee::retired()->get();

        $this->assertTrue($retiredReferees->contains($refereeA));
        $this->assertTrue($retiredReferees->contains($refereeB));
        $this->assertFalse($retiredReferees->contains($refereeC));
    }

    /**
     * @expectedException \App\Exceptions\ModelIsRetiredException
     *
     * @test
     */
    public function a_retired_referee_cannot_retire()
    {
        $referee = factory(Referee::class)->states('retired')->create();

        $referee->retire();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_referee_cannot_unretire()
    {
        $referee = factory(Referee::class)->states('active')->create();

        $referee->unretire();
    }

    /** @test */
    public function a_referee_can_be_suspended()
    {
        $referee = factory(Referee::class)->create();

        $referee->suspend();

        $this->assertEquals(1, $referee->suspensions->count());
        $this->assertFalse($referee->is_active);
        $this->assertNull($referee->suspensions()->first()->ended_at);
        $this->assertTrue($referee->isSuspended());
    }

    /** @test */
    public function a_suspended_referee_can_be_reinstated()
    {
        $referee = factory(Referee::class)->states('suspended')->create();

        $referee->reinstate();

        $this->assertNotNull($referee->suspensions->last()->ended_at);
        $this->assertTrue($referee->is_active);
        $this->assertFalse($referee->isSuspended());
        $this->assertTrue($referee->hasPastSuspensions());
        $this->assertEquals(1, $referee->pastSuspensions->count());
    }

    /** @test */
    public function it_can_get_suspended_referees()
    {
        $refereeA = factory(Referee::class)->states('suspended')->create();
        $refereeB = factory(Referee::class)->states('suspended')->create();
        $refereeC = factory(Referee::class)->states('active')->create();

        $suspendedReferees = Referee::suspended()->get();

        $this->assertTrue($suspendedReferees->contains($refereeA));
        $this->assertTrue($suspendedReferees->contains($refereeB));
        $this->assertFalse($suspendedReferees->contains($refereeC));
    }

    /**
     * @expectedException \App\Exceptions\ModelIsSuspendedException
     *
     * @test
     */
    public function a_suspended_referee_cannot_be_suspended()
    {
        $referee = factory(Referee::class)->states('suspended')->create();

        $referee->suspend();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_referee_cannot_be_reinstated()
    {
        $referee = factory(Referee::class)->states('active')->create();

        $referee->reinstate();
    }
}
