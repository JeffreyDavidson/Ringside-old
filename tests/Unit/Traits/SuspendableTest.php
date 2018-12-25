<?php

namespace Tests\Unit\Traits;

class SuspendableTest
{
    /** @test */
    public function it_can_get_suspended_wrestlers()
    {
        $suspendedWrestlerA = factory(Wrestler::class)->states('suspended')->create();
        $suspendedWrestlerB = factory(Wrestler::class)->states('suspended')->create();
        $activeWrestler = factory(Wrestler::class)->states('active')->create();

        $suspendedWrestlers = Wrestler::suspended()->get();

        $this->assertTrue($suspendedWrestlers->contains($suspendedWrestlerA));
        $this->assertTrue($suspendedWrestlers->contains($suspendedWrestlerB));
        $this->assertFalse($suspendedWrestlers->contains($activeWrestler));
    }

    /** @test */
    public function an_active_wrestler_can_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->suspend();

        $this->assertEquals(1, $wrestler->suspensions->count());
        $this->assertFalse($wrestler->isActive());
        $this->assertNull($wrestler->suspensions()->first()->ended_at);
        $this->assertTrue($wrestler->isSuspended());
    }

    /** @test */
    public function a_suspended_wrestler_can_be_reinstated()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->reinstate();

        $this->assertNotNull($wrestler->suspensions->last()->ended_at);
        $this->assertTrue($wrestler->isActive());
        $this->assertFalse($wrestler->isSuspended());
        $this->assertTrue($wrestler->hasPastSuspensions());
        $this->assertEquals(1, $wrestler->pastSuspensions->count());
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_wrestler_cannot_be_reinstated()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->reinstate();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsSuspendedException
     *
     * @test
     */
    public function a_suspended_wrestler_cannot_be_suspended()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->suspend();
    }
}