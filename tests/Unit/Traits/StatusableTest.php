<?php

namespace Tests\Unit\Traits;

class StatusableTest
{
    /** @test */
    public function wrestlers_are_marked_as_active_depending_on_hired_date()
    {
        $wrestlerA = factory(Wrestler::class)->create(['hired_at' => Carbon::today()->subDays(2)]);
        $wrestlerB = factory(Wrestler::class)->create(['hired_at' => Carbon::today()]);
        $wrestlerC = factory(Wrestler::class)->create(['hired_at' => Carbon::today()->addDays(2)]);

        $this->assertTrue($wrestlerA->isActive());
        $this->assertTrue($wrestlerB->isActive());
        $this->assertFalse($wrestlerC->isActive());
    }

    /** @test */
    public function it_can_get_active_wrestlers()
    {
        $activeWrestlerA = factory(Wrestler::class)->states('active')->create();
        $activeWrestlerB = factory(Wrestler::class)->states('active')->create();
        $inactiveWrestler = factory(Wrestler::class)->states('inactive')->create();

        $activeWrestlers = Wrestler::active()->get();

        $this->assertTrue($activeWrestlers->contains($activeWrestlerA));
        $this->assertTrue($activeWrestlers->contains($activeWrestlerB));
        $this->assertFalse($activeWrestlers->contains($inactiveWrestler));
    }

    /** @test */
    public function it_can_get_inactive_wrestlers()
    {
        $inactiveWrestlerA = factory(Wrestler::class)->states('inactive')->create();
        $inactiveWrestlerB = factory(Wrestler::class)->states('inactive')->create();
        $activeWrestler = factory(Wrestler::class)->states('active')->create();

        $inactiveWrestlers = Wrestler::inactive()->get();

        $this->assertTrue($inactiveWrestlers->contains($inactiveWrestlerA));
        $this->assertTrue($inactiveWrestlers->contains($inactiveWrestlerB));
        $this->assertFalse($inactiveWrestlers->contains($activeWrestler));
    }

    /** @test */
    public function it_can_activate_an_inactive_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $wrestler->activate();

        $this->assertTrue($wrestler->isActive());
    }

    /** @test */
    public function it_can_deactivate_an_active_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->deactivate();

        $this->assertFalse($wrestler->isActive());
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_wrestler_cannot_be_activated()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->activate();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsInactiveException
     *
     * @test
     */
    public function an_inactive_wrestler_cannot_be_deactivated()
    {
        $wrestler = factory(Wrestler::class)->states('inactive')->create();

        $wrestler->deactivate();
    }
}