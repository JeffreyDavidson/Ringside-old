<?php

namespace Tests\Unit\Traits;

class RetirableTest
{
    /** @test */
    public function an_active_wrestler_can_retire()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->retire();

        $this->assertEquals(1, $wrestler->retirements->count());
        $this->assertFalse($wrestler->isActive());
        $this->assertTrue($wrestler->isRetired());
        $this->assertNull($wrestler->retirements()->first()->ended_at);
    }

    /** @test */
    public function a_retired_wrestler_can_unretire()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->unretire();

        $this->assertNotNull($wrestler->retirements()->first()->ended_at);
        $this->assertTrue($wrestler->isActive());
        $this->assertFalse($wrestler->isRetired());
    }

    /** @test */
    public function it_can_get_retired_wrestlers()
    {
        $retiredWrestlerA = factory(Wrestler::class)->states('retired')->create();
        $retiredWrestlerB = factory(Wrestler::class)->states('retired')->create();
        $activeWrestler = factory(Wrestler::class)->states('active')->create();

        $retiredWrestlers = Wrestler::retired()->get();

        $this->assertTrue($retiredWrestlers->contains($retiredWrestlerA));
        $this->assertTrue($retiredWrestlers->contains($retiredWrestlerB));
        $this->assertFalse($retiredWrestlers->contains($activeWrestler));
    }

    /**
     * @expectedException \App\Exceptions\ModelIsRetiredException
     *
     * @test
     */
    public function a_retired_wrestler_cannot_retire()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->retire();
    }

    /**
     * @expectedException \App\Exceptions\ModelIsActiveException
     *
     * @test
     */
    public function an_active_wrestler_cannot_unretire()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $wrestler->unretire();
    }
}